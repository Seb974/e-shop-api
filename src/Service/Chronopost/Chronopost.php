<?php

namespace App\Service\Chronopost;

use App\Entity\OrderEntity;
use App\Service\Chronopost\Shipping;
use App\Service\Chronopost\Tracking;

class Chronopost
{
    private $shipping;
    private $tracking;

    public function __construct(Shipping $shipping, Tracking $tracking)
    {
        $this->shipping = $shipping;
        $this->tracking = $tracking;
    }

    public function setReservationNumbers(OrderEntity &$order)
    {
        $reservation = $this->shipping->getReservationNumbers($order);
        if (isset($reservation->return)) {
            dump($reservation);
            dump($reservation->return);
            $results = $reservation->return->resultMultiParcelValue;
            $reservationNumber = $reservation->return->reservationNumber;
            $trackIds = $this->tracking->getTrackIds($results, $order);

            $order->setReservationNumber($reservationNumber);
            $order->setTrackIds($trackIds);
        }
    }

    public function getSkybill($reservationNumber)
    {
        return $this->shipping->getSkybill($reservationNumber);
    }

    public function cancelSkybill($order)
    {
        return $this->tracking->cancelSkybill($order);
    }

    public function trackSkybill($skybillNumber)
    {
        return $this->tracking->trackSkybill($skybillNumber);
    }

}