<?php

namespace App\Service\Chronopost;

use App\Entity\OrderEntity;
use App\Entity\Chronopost\TrackSkybill;
use App\Entity\Chronopost\CancelSkybill;
use App\Entity\Platform;
use App\Repository\PlatformRepository;
use Symfony\Component\Filesystem\Filesystem;

class Tracking
{
    // private $rootPath;
    // private $fileSystem;
    private $trackingService;
    private $platformRepository;

    public function __construct(PlatformRepository $platformRepository)
    {
        $this->trackingService = new \SoapClient('https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl');
        $this->trackingService->soap_defencoding = 'UTF-8';
        $this->trackingService->decode_utf8 = false;
        $this->platformRepository = $platformRepository;
        // $this->fileSystem = new Filesystem();
        // $this->rootPath = $rootPath;
    }

    public function getTrackIds($reservationResult, OrderEntity $order)
    {
        $trackIds = [];
        if (count($order->getPackages()) > 1) {
            foreach ($reservationResult as $reservation) {
                $trackIds[] = $reservation->skybillNumber;
            }
        } else {
            $trackIds[] = $reservationResult->skybillNumber;
        }
        return $trackIds;
    }

    public function cancelSkybill(OrderEntity $order)
    {
        $platform = $this->getPlatform();
        if (!is_null($platform->getChronopostNumber()) && !is_null($platform->getChronopostPassword())) {
            $results = [];
            try {
                foreach ($order->getTrackIds() as $skybillNumber) {
                    try {
                        $params = $this->getCancelParams($skybillNumber, $platform);
                        $results[] = $this->trackingService->cancelSkybill($params);
                    } catch (\SoapFault $soapFault) { 
                        return $soapFault; 
                    }
                }
                return $results;

            } catch (\Exception $e) { 
                return $e->getMessage();
            }
        }
        return null;
    }

    public function trackSkybill($skybillNumber)
    {
        $platform = $this->getPlatform();
        if (!is_null($platform->getChronopostNumber()) && !is_null($platform->getChronopostPassword())) {
            try {
                $params = $this->getTrackingParams($skybillNumber);
                $response = $this->trackingService->trackSkybillV2($params);
                if ( isset($response) ) {
                    return $response->return;
                }

            } catch (\Exception $e) { 
                return $e->getMessage(); 
            }
        }
        return null;
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }

    private function getCancelParams($skyBill, Platform $loadedPlatform = null)
    {
        $platform = !is_null($loadedPlatform) ? $loadedPlatform : $this->getPlatform();
        $cancel = new CancelSkybill();
        $cancel->setAccountNumber($platform->getChronopostNumber())
               ->setPassword($platform->getChronopostPassword())
               ->setLanguage('fr_FR')
               ->setSkybillNumber($skyBill);
        return $cancel;
    }

    private function getTrackingParams($skybillNumber)
    {
        $track = new TrackSkybill();
        $track->setLanguage('fr_FR')
              ->setSkybillNumber($skybillNumber);
        return $track;
    }

}