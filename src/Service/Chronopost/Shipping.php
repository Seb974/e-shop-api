<?php

namespace App\Service\Chronopost;

use App\Entity\Catalog;
use App\Entity\Platform;
use App\Entity\OrderEntity;
use App\Entity\Chronopost\EsdValue;
use App\Entity\Chronopost\RefValue;
use App\Repository\CatalogRepository;
use App\Entity\Chronopost\HeaderValue;
use App\Repository\PlatformRepository;
use App\Entity\Chronopost\ShipperValue;
use App\Entity\Chronopost\SkybillValue;
use App\Service\Package\PackagePlanner;
use App\Entity\Chronopost\CustomerValue;
use App\Service\Chronopost\Declarations;
use App\Entity\Chronopost\RecipientValue;
use App\Entity\Chronopost\SkybillParamsValue;
use App\Entity\Chronopost\ShippingMultiParcelV4;
use App\Entity\Chronopost\GetReservedSkybillWithTypeAndModeAuth;

class Shipping
{
    private $declarations;
    private $contractNumber;
    private $shippingService;
    private $packagesPlanner;
    private $catalogRepository;
    private $platformRepository;

    public function __construct($contractNumber, PackagePlanner $packagesPlanner, Declarations $declarations, PlatformRepository $platformRepository, CatalogRepository $catalogRepository)
    {
        $this->shippingService = new \SoapClient('https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl');
        $this->shippingService->soap_defencoding = 'UTF-8';
        $this->platformRepository = $platformRepository;
        $this->catalogRepository = $catalogRepository;
        $this->shippingService->decode_utf8 = false;
        $this->packagesPlanner = $packagesPlanner;
        $this->contractNumber = $contractNumber;
        $this->declarations = $declarations;
    }

    public function getReservationNumbers(OrderEntity $order)
    {
        $platform = $this->getPlatform();
        $catalog = $order->getCatalog();
        if (!is_null($platform->getChronopostNumber()) && !is_null($platform->getChronopostPassword()) && $catalog->getDeliveredByChronopost()) {
            try {
                $packagesPlan = $this->packagesPlanner->getParcelPlan($order);
                $params = $this->getBillParams($order, $packagesPlan, $platform);
                $reservation = $this->shippingService->shippingMultiParcelV4($params);
                return $reservation;
            } catch (\SoapFault $soapFault) { 
                return $soapFault;
            }
        }
        return null;
    }

    public function getSkybill($reservationNumber)
    {
        $platform = $this->getPlatform();
        if (!is_null($platform->getChronopostNumber()) && !is_null($platform->getChronopostPassword())) {
            try {
                $params = $this->getReservationParams($reservationNumber, $platform);
                $encodedResult = $this->shippingService->getReservedSkybillWithTypeAndModeAuth($params);
                return base64_decode($encodedResult->return->skybill, false);
    
            } catch (\SoapFault $soapFault) { 
                return $soapFault; 
            }
        }
        return null;
    }

    private function getBillParams(OrderEntity $order, array $packagesPlan, Platform $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        $catalog = $this->getDefaultCatalog();
        $packages = $this->getPackages($order);
        $skybillValue = count($packages) > 1 ? 
            $this->getSkybillValueForMultiParcels($order, $packagesPlan) : 
            $this->getSkybillValueForOneParcel($order, $packagesPlan);
        $params = new ShippingMultiParcelV4();
        $params->setEsdValue($this->getEsdValue($order))
               ->setHeaderValue($this->getHeaderValue($platform))
               ->setShipperValue($this->getShipperValue($platform, $catalog))
               ->setCustomerValue($this->getCustomerValue($platform, $catalog))
               ->setRecipientValue($this->getRecipientValue($order))
               ->setRefValue($this->getRefValue($order))
               ->setSkybillValue($skybillValue)
               ->setSkybillParamsValue($this->getSkybillParamsValue())
               ->setPassword($platform->getChronopostPassword())      // $this->password
               ->setModeRetour('2')
               ->setNumberOfParcel(count($packages))
               ->setVersion('2.0')
               ->setMultiparcel(count($packages) > 1 ? 'Y' : 'N');
        return $params;
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }

    private function getDefaultCatalog()
    {
        return $this->catalogRepository->findOneBy(['isDefault' => true]);
    }

    private function getPackages($order)
    {
        $packagesArray = [];
        $packages = $order->getPackages();
        foreach ($packages as $package) {
            $container = $package->getContainer();
            for ($i = 0; $i < $package->getQuantity(); $i++) {
                $packagesArray[] = [
                    'quantity' => 1,
                    'max' => $container->getMax(),
                    'height' => $container->getHeight(),
                    'length' => $container->getLength(),
                    'width' => $container->getWidth()
                ];
            }
        }
        return $packagesArray;
    }

    private function getEsdValue(OrderEntity $order)
    {
        $esd = new EsdValue();
        $esd->setClosingDateTime(date_time_set($order->getDeliveryDate(), 17, 0, 0, 0))
            ->setRetrievalDateTime(date_time_set($order->getDeliveryDate(), 10, 0, 0, 0))
            ->setHeight(0)
            ->setLength(0)
            ->setWidth(0)
            ->setShipperBuildingFloor('')
            ->setShipperCarriesCode('')
            ->setShipperServiceDirection('')
            ->setSpecificInstructions('')
            ->setLtAImprimerParChronopost(0)
            ->setNombreDePassageMaximum(1)
            ->setRefEsdClient('');
        return $esd;
    }

    private function getHeaderValue(Platform $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        $header = new HeaderValue();
        $header->setAccountNumber($platform->getChronopostNumber())    // $this->accountNumber
               ->setIdEmit('CHRFR')
               ->setSubAccount(0);
        return $header;
    }

    private function getShipperValue(Platform $platform, Catalog $catalog)
    {
        $contact = ($platform->getPickers())[0];
        $shipper = new ShipperValue();
        $shipper->setShipperAdress1($platform->getMetas()->getAddress())
                ->setShipperAdress2(is_null($platform->getMetas()->getAddress2()) ? '' : $platform->getMetas()->getAddress2())
                ->setShipperCity($platform->getMetas()->getCity())
                ->setShipperCivility('M')
                ->setShipperContactName($platform->getName())
                ->setShipperCountry($catalog->getCode())
                ->setShipperCountryName($catalog->getName())
                ->setShipperEmail($contact->getEmail())
                ->setShipperMobilePhone($platform->getMetas()->getPhone())
                ->setShipperName($platform->getName())
                ->setShipperName2($contact->getName())
                ->setShipperPhone($platform->getMetas()->getPhone())
                ->setShipperPreAlert(0)
                ->setShipperZipCode($platform->getMetas()->getZipcode())
                ->setShipperType('1');  // strpos($this->userService->getRole($order->getUser()), "ROLE_USER") !== false ? '1' : '2'
        return $shipper;
    }

    private function getCustomerValue(Platform $platform, Catalog $catalog) 
    {
        $contact = ($platform->getPickers())[0];
        $customer = new CustomerValue();
        $customer->setCustomerAdress1($platform->getMetas()->getAddress())
                 ->setCustomerAdress2(is_null($platform->getMetas()->getAddress2()) ? '' : $platform->getMetas()->getAddress2())
                 ->setCustomerCity($platform->getMetas()->getCity())
                 ->setCustomerCivility('M')
                 ->setCustomerContactName($platform->getName())
                 ->setCustomerCountry($catalog->getCode())
                 ->setCustomerCountryName($catalog->getName())
                 ->setCustomerEmail($contact->getEmail())
                 ->setCustomerMobilePhone($platform->getMetas()->getPhone())
                 ->setCustomerName($platform->getName())
                 ->setCustomerName2($contact->getName())
                 ->setCustomerPhone($platform->getMetas()->getPhone())
                 ->setCustomerZipCode($platform->getMetas()->getZipcode())
                 ->setCustomerPreAlert(0)
                 ->setPrintAsSender('N');
        return $customer;
    }

    private function getRecipientValue(OrderEntity $order)
    {
        $recipient = new RecipientValue();
        $recipient->setRecipientAdress1($order->getMetas()->getAddress())
                  ->setRecipientAdress2(is_null($order->getMetas()->getAddress2()) ? '' : $order->getMetas()->getAddress2())
                  ->setRecipientCity($order->getMetas()->getCity())
                  ->setRecipientCivility('M')
                  ->setRecipientContactName($order->getName())
                  ->setRecipientCountry($order->getCatalog()->getCode())
                  ->setRecipientCountryName($order->getCatalog()->getName())
                  ->setRecipientEmail($order->getEmail())
                  ->setRecipientMobilePhone($order->getMetas()->getPhone())
                  ->setRecipientName($order->getName())
                  ->setRecipientName2('')
                  ->setRecipientPhone($order->getMetas()->getPhone())
                  ->setRecipientZipCode($order->getMetas()->getZipcode())
                  ->setRecipientPreAlert(0)
                  ->setRecipientType('1');      // strpos($this->userService->getRole($order->getUser()), "ROLE_USER") !== false ? '1' : '2'
        return $recipient;
    }

    private function getRefValue(OrderEntity $order)
    {
        $ref = new RefValue();
        $ref->setCustomerSkybillNumber(str_pad($order->getId(), 10, "0", STR_PAD_LEFT))
            ->setRecipientRef('')
            ->setShipperRef('');
        return $ref;
    }

    private function getSkybillParamsValue()
    {
        $skybillParams = new SkybillParamsValue();
        $skybillParams->setMode('ZPL')
                      ->setDuplicata('N')
                      ->setWithReservation(1);
        return $skybillParams;
    }

    private function getSkybillValueForOneParcel(OrderEntity $order, array $packagesPlan)
    {
        $skybills = [];
        $packages = $order->getPackages();
        $skybill = new SkybillValue();
        $skybill->setBulkNumber('1')
                ->setCodCurrency('EUR')
                ->setCodValue(0)
                ->setCustomsCurrency('EUR')
                ->setCustomsValue($packagesPlan[0]['cost'])
                ->setEvtCode('DC')
                ->setInsuredCurrency('EUR')
                ->setInsuredValue($packagesPlan[0]['cost'] * 100)
                ->setMasterSkybillNumber('')
                ->setObjectType('MAR')
                ->setPortCurrency('EUR')
                ->setPortValue('')
                ->setProductCode($this->contractNumber)
                ->setService('0')
                ->setShipDate($order->getDeliveryDate())
                ->setShipHour(10)
                ->setSkybillRank('1')
                ->setWeight($packages[0]->getContainer()->getMax())
                ->setWeightUnit('KGM')
                ->setHeight($packages[0]->getContainer()->getHeight())
                ->setLength($packages[0]->getContainer()->getLength())
                ->setWidth($packages[0]->getContainer()->getWidth())
                ->setAs('');
        $this->declarations->setContent($skybill, $order, $packagesPlan[0]);
        $skybills[] = $skybill;
        return $skybill;
    }

    private function getSkybillValueForMultiParcels(OrderEntity $order, array $packagesPlan)
    {
        $skybills = [];
        $packages = $this->getPackages($order);
        foreach ($packages as $i => $package) {
            $skybill = new SkybillValue();
            $skybill->setBulkNumber(count($packages))
                    ->setCodCurrency('EUR')
                    ->setCodValue(0)
                    ->setCustomsCurrency('EUR')
                    ->setCustomsValue($packagesPlan[$i]['cost'])
                    ->setEvtCode('DC')
                    ->setInsuredCurrency('EUR')
                    ->setInsuredValue($packagesPlan[$i]['cost'] * 100)
                    ->setMasterSkybillNumber('')
                    ->setObjectType('MAR')
                    ->setPortCurrency('EUR')
                    ->setPortValue('')
                    ->setProductCode($this->contractNumber)
                    ->setService('0')
                    ->setShipDate($order->getDeliveryDate())
                    ->setShipHour(10)
                    ->setSkybillRank($i + 1)
                    ->setWeight($package['max'])
                    ->setWeightUnit('KGM')
                    ->setHeight($package['height'])
                    ->setLength($package['length'])
                    ->setWidth($package['width'])
                    ->setAs('');
            $this->declarations->setContent($skybill, $order, $packagesPlan[$i]);
            $skybills[] = $skybill;
        }
        return $skybills;
    }

    public function getReservationParams($reservationNumber, Platform $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        $reservedSkybill = new GetReservedSkybillWithTypeAndModeAuth();
        $reservedSkybill->setReservationNumber($reservationNumber)
                        ->setMode('ZPL')
                        ->setNumberSearch($reservationNumber)
                        ->setAccountNumber($platform->getChronopostNumber())    // $this->accountNumber
                        ->setPassword($platform->getChronopostPassword());     // $this->password
        return $reservedSkybill;
    }
}