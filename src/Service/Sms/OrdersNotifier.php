<?php

namespace App\Service\Sms;

use App\Repository\RelaypointRepository;

class OrdersNotifier
{
    private $sms;
    private $relaypointRepository;

    public function __construct(Sms $sms, RelaypointRepository $relaypointRepository)
    {
        $this->sms = $sms;
        $this->relaypointRepository = $relaypointRepository;
    }

    public function notifySoldOut($order)
    {
        $message = $this->getSoldOutMessage($order);
        if (strlen($message) > 0)
            $this->sms->sendTo($order->getMetas()->getPhone(), $message);
    }

    public function notifyRelaypointArrivals($order)
    {
        $message = $this->getAvailabilityMessage($order);
        $this->sms->sendTo($order->getMetas()->getPhone(), $message);
    }

    private function getSoldOutMessage($order)
    {
        $message = "";
        $items = $this->getSoldsOut($order);
        if (count($items) > 0) {
            $message = $this->getIntro($order);
            foreach ($items as $item) {
                $message .= $this->getFormattedRow($item);
            }
        }
        return $message;
    }

    private function getAvailabilityMessage($order)
    {
        $relaypoint = $this->relaypointRepository->findOneBy(["metas" => $order->getMetas()]);

        $message = "Bonjour " . $order->getName() . "\n";
        $message .= "Votre commande N°" . str_pad($order->getId(), 10, "0", STR_PAD_LEFT) . 
                    " est disponible au point relais \"" . $relaypoint->getName() . "\"\n" .
                    "Bonne dégustation et à très bientôt sur fraispei.re.";
        return $message;
    }

    private function getSoldsOut($order)
    {
        $soldsOut = [];
        if (!$order->getIsRemains()) {
            foreach ($order->getItems() as $item) {
                if (!$item->getIsAdjourned() && $item->getPreparedQty() < ($item->getOrderedQty() * 0.8)) {
                    $soldsOut[] = $item;
                }
            }
        }
        return $soldsOut;
    }

    private function getFormattedRow($item)
    {
        $unit = $item->getProduct()->getUnit();
        $appreciation = 
            $item->getPreparedQty() <= 0 ? "rupture totale" :
            "livré " . $item->getPreparedQty() . " sur " . $item->getOrderedQty() . " " . $unit;
        return " - " . $item->getProduct()->getName() . " : " . $appreciation . "\n";
    }

    private function getIntro()
    {
        return "Bonjour,\nVotre commande est prête !\n" . 
        "Nous n'avons pas pu vous fournir les produits suivants dans les quantités demandées : \n";
    }
}