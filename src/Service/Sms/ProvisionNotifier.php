<?php

namespace App\Service\Sms;

class ProvisionNotifier
{
    private $sms;

    public function __construct(Sms $sms)
    {
        $this->sms = $sms;
    }

    public function notifyOrder($provision)
    {
        $message = $this->getOrderMessage($provision);
        if (strlen($message) > 0)
            $this->sms->sendTo($provision->getSupplier()->getPhone(), $message);
    }

    private function getOrderMessage($provision)
    {
        if (count($provision->getGoods()) > 0) {
            $message = $this->getIntro($provision);
            foreach($provision->getGoods() as $good) {
                $message .= $this->getFormattedRow($good);
            }
        }
        return $message;
    }

    private function getFormattedRow($good)
    {
        $product = $this->getProductName($good);
        return " - " . $product . " : " . $good->getQuantity() . $good->getUnit() . "\n";
    }

    private function getIntro($provision)
    {
        $supplierName = $provision->getSupplier()->getName();
        $provisionDate = date_format($provision->getProvisionDate(), 'd/m/Y');
        $address = $provision->getReceiveMode() === "livraison" ? " au " . $provision->getMetas()->getAddress() . " " . $provision->getMetas()->getAddress2() . " " . $provision->getMetas()->getZipcode() . " - " . $provision->getMetas()->getCity() : "";
        return "Bonjour " . $supplierName . ",\nVoici ci-dessous notre commande pour ". ($provision->getReceiveMode() === "livraison" ? "livraison" : "récupération sur place") ." le ". $provisionDate . $address . " :\n";
    }

    private function getProductName($good) {
        $variationName = is_null($good->getVariation()) ? "" : " - " . $good->getVariation()->getColor();
        $sizeName = is_null($good->getSize()) ? "" : " " . $good->getSize()->getName();
        return $good->getProduct()->getName() . $variationName . $sizeName;
    }
}