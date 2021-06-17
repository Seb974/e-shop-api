<?php

namespace App\Service\Sms;

class OrdersNotifier
{
    private $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function notify($order)
    {
        $message = $this->getMessage($order);
        if (strlen($message) > 0)
            $this->sms->sendTo($order->getMetas()->getPhone(), $message);
    }

    private function getMessage($order)
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