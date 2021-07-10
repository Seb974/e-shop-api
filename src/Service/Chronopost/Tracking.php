<?php

namespace App\Service\Chronopost;

use App\Entity\OrderEntity;
use App\Entity\Chronopost\TrackSkybill;
use App\Entity\Chronopost\CancelSkybill;
use Symfony\Component\Filesystem\Filesystem;

class Tracking
{
    private $rootPath;
    private $password;
    private $fileSystem;
    private $accountNumber;
    private $trackingService;

    public function __construct($accountNumber, $password, $rootPath)
    {
        $this->trackingService = new \SoapClient('https://ws.chronopost.fr/tracking-cxf/TrackingServiceWS?wsdl');
        $this->trackingService->soap_defencoding = 'UTF-8';
        $this->trackingService->decode_utf8 = false;
        $this->fileSystem = new Filesystem();
        $this->accountNumber = $accountNumber;
        $this->password = $password;
        $this->rootPath = $rootPath;
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
        $results = [];
        try {
            foreach ($order->getTrackIds() as $skybillNumber) {
                try {
                    $params = $this->getCancelParams($skybillNumber);
                    $results[] = $this->trackingService->cancelSkybill($params);
                } catch (\SoapFault $soapFault) { 
                    return $soapFault; 
                }
            }
            $fileName = $this->rootPath . '/public/uploads/etiquettes/' . $order->getReservationNumber() .'.zpl';
            if (file_exists($fileName))
                $this->fileSystem->remove($fileName);
    
            return $results;

        } catch (\Exception $e) { 
            return $e->getMessage();
        }
    }

    public function trackSkybill($skybillNumber)
    {
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

    private function getCancelParams($skyBill)
    {
        $cancel = new CancelSkybill();
        $cancel->setAccountNumber($this->accountNumber)
               ->setPassword($this->password)
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