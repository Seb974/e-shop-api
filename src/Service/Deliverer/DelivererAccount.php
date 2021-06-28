<?php

namespace App\Service\Deliverer;

class DelivererAccount
{
    public function dispatchTurnover($touring)
    {
        $deliverer = $touring->getDeliverer();
        if (!$deliverer->getIsIntern()) {
            $ownerPart = $deliverer->getOwnerRate() / 100;
            $touringCost = $this->getDeliveryCosts($touring, $deliverer);
            $deliverer->setTurnover($touringCost['HT'])
                      ->setTurnoverTTC($touringCost['TTC'])
                      ->setTotalToPay($touringCost['HT'] * (1 - $ownerPart))
                      ->setTotalToPayTTC($touringCost['TTC'] * (1 - $ownerPart));
            ;
        }
    }

    private function getDeliveryCosts($touring, $deliverer)
    {
        $taxRate = $this->getTaxRate($deliverer);
        $deliveryCount = count($touring->getOrderEntities());
        $totalHT = $deliverer->getTurnover() + ($deliveryCount * $deliverer->getCost());
        $totalTTC = $deliverer->getTurnoverTTC() + ($totalHT * (1 + $taxRate));

        return ['HT' => $totalHT, 'TTC' => $totalTTC];
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