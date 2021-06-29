<?php

namespace App\Service\Deliverer;

class DelivererAccount
{
    public function dispatchTurnover($touring, $action = "INCREASE")
    {
        $deliverer = $touring->getDeliverer();
        if (!$deliverer->getIsIntern()) {
            $ownerPart = $deliverer->getOwnerRate() / 100;
            $touringCost = $this->getDeliveryCosts($touring, $deliverer, $action);
            $deliverer->setTurnover($touringCost['HT'])
                      ->setTurnoverTTC($touringCost['TTC'])
                      ->setTotalToPay($touringCost['HT'] * (1 - $ownerPart))
                      ->setTotalToPayTTC($touringCost['TTC'] * (1 - $ownerPart));
        }
    }

    private function getDeliveryCosts($touring, $deliverer, $action = "INCREASE")
    {
        $taxRate = $this->getTaxRate($deliverer);
        $deliveryCount = count($touring->getOrderEntities());
        $totalHT =  $action === "INCREASE" ? 
                    $deliverer->getTurnover() + ($deliveryCount * $deliverer->getCost()) : 
                    $deliverer->getTurnover() - ($deliveryCount * $deliverer->getCost());
        $totalTTC = $action === "INCREASE" ? 
                    $deliverer->getTurnoverTTC() + ($totalHT * (1 + $taxRate)) : 
                    $deliverer->getTurnoverTTC() - ($deliveryCount * $deliverer->getCost() * (1 + $taxRate));
        return ['HT' => $totalHT >= 0 ? $totalHT : 0, 'TTC' => $totalTTC >= 0 ? $totalTTC : 0];
    }

    private function getTaxRate($deliverer)
    {
        $taxRate = 0;
        foreach ($deliverer->getTax()->getCatalogTaxes() as $catalogTax) {
            if ($catalogTax->getCatalog()->getId() === $deliverer->getCatalog()->getId()) {
                $taxRate = $catalogTax->getPercent();
                break;
            }
        }
        return $taxRate;
    }
}