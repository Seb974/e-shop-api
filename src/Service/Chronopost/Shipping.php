<?php

namespace App\Service\Chronopost;

use App\Entity\Catalog;
use App\Entity\Platform;
use App\Entity\OrderEntity;
use App\Service\Chronopost\Ticket;
use App\Entity\Chronopost\EsdValue;
use App\Entity\Chronopost\RefValue;
use App\Repository\CatalogRepository;
use App\Entity\Chronopost\HeaderValue;
use App\Repository\PlatformRepository;
use App\Entity\Chronopost\ShipperValue;
use App\Entity\Chronopost\SkybillValue;
use App\Service\Package\PackagePlanner;
use App\Entity\Chronopost\CustomerValue;
use App\Entity\Chronopost\RecipientValue;
use App\Entity\Chronopost\SkybillParamsValue;
use App\Entity\Chronopost\ShippingMultiParcelV4;
use App\Entity\Chronopost\GetReservedSkybillWithTypeAndModeAuth;

class Shipping
{
    private $password;
    private $accountNumber;
    private $contractNumber;
    private $shippingService;
    private $packagesPlanner;
    private $catalogRepository;
    private $platformRepository;
    private $ticket;

    public function __construct($contractNumber, $accountNumber, $password, PackagePlanner $packagesPlanner, PlatformRepository $platformRepository, CatalogRepository $catalogRepository, Ticket $ticket)
    {
        $this->shippingService = new \SoapClient('https://ws.chronopost.fr/shipping-cxf/ShippingServiceWS?wsdl');
        $this->shippingService->soap_defencoding = 'UTF-8';
        $this->platformRepository = $platformRepository;
        $this->catalogRepository = $catalogRepository;
        $this->shippingService->decode_utf8 = false;
        $this->packagesPlanner = $packagesPlanner;
        $this->contractNumber = $contractNumber;
        $this->accountNumber = $accountNumber;
        $this->password = $password;
        $this->ticket = $ticket;
    }

    public function getReservationNumbers(OrderEntity $order)
    {
        try {
            $packagesPlan = $this->packagesPlanner->getParcelPlan($order);
            $params = $this->getBillParams($order, $packagesPlan);
            $reservation = $this->shippingService->shippingMultiParcelV4($params);
            return $reservation;
        } catch (\SoapFault $soapFault) { 
            return $soapFault;
        }
    }

    public function getSkybill($reservationNumber)
    {
        try {
            $params = $this->getReservationParams($reservationNumber);
            $encodedResult = $this->shippingService->getReservedSkybillWithTypeAndModeAuth($params);
            $skybill = base64_decode($encodedResult->return->skybill, false);
            
            // return $this->ticket->getPrintableZPL($skybill, $reservationNumber);
            return $skybill;

        } catch (\SoapFault $soapFault) { 
            return $soapFault; 
        }
    }

    private function getBillParams(OrderEntity $order, array $packagesPlan)
    {
        $platform = $this->getPlatform();
        $catalog = $this->getDefaultCatalog();
        $packages = $this->getPackages($order);
        $skybillValue = count($packages) > 1 ? 
            $this->getSkybillValueForMultiParcels($order, $packagesPlan) : 
            $this->getSkybillValueForOneParcel($order, $packagesPlan);
        $params = new ShippingMultiParcelV4();
        $params->setEsdValue($this->getEsdValue($order))
               ->setHeaderValue($this->getHeaderValue())
               ->setShipperValue($this->getShipperValue($platform, $catalog))
               ->setCustomerValue($this->getCustomerValue($platform, $catalog))
               ->setRecipientValue($this->getRecipientValue($order))
               ->setRefValue($this->getRefValue($order))
               ->setSkybillValue($skybillValue)
               ->setSkybillParamsValue($this->getSkybillParamsValue())
               ->setPassword($this->password)
               ->setModeRetour('2')
               ->setNumberOfParcel(count($packages))
               ->setVersion('2.0')
               ->setMultiparcel(count($packages) > 1 ? 'Y' : 'N');
        return $params;
    }

    private function getPlatform()
    {
        $platforms = $this->platformRepository->findAll();
        return $platforms[0];
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

    private function getHeaderValue()
    {
        $header = new HeaderValue();
        $header->setAccountNumber($this->accountNumber)
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
                ->setContent1('')
                ->setContent2('')
                ->setContent3('')
                ->setContent4('')
                ->setContent5('')
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
        $skybills[] = $skybill;
        // $this->setDeclarations($skybills, $packagesPlan);
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
                    ->setContent1('')
                    ->setContent2('')
                    ->setContent3('')
                    ->setContent4('')
                    ->setContent5('')
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
            $skybills[] = $skybill;
        }
        // $this->setDeclarations($skybills, $packagesPlan);
        return $skybills;
    }

    public function getReservationParams($reservationNumber)
    {
        $reservedSkybill = new GetReservedSkybillWithTypeAndModeAuth();
        $reservedSkybill->setReservationNumber($reservationNumber)
                        ->setMode('ZPL')
                        ->setNumberSearch($reservationNumber)
                        ->setAccountNumber($this->accountNumber)
                        ->setPassword($this->password);
        return $reservedSkybill;
    }
}