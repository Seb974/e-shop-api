<?php

namespace App\Service\Seller;

class SellerAccount
{
    public function dispatchTurnover($order)
    {
        $sellers = $this->getSellers($order);
        foreach ($sellers as $seller) {
            $ownerPart = $seller->getOwnerRate() / 100;
            $totals = $this->getSellerTotals($seller, $order->getItems());
            $seller->setTurnover($totals['HT'])
                   ->setTurnoverTTC($totals['TTC'])
                   ->setTotalToPay($totals['HT'] * (1 - $ownerPart))
                   ->setTotalToPayTTC($totals['TTC'] * (1 - $ownerPart));
        }
    }

    public function getSellerTotals($seller, $items)
    {
        $totalHT = $seller->getTurnover();
        $totalTTC = $seller->getTurnoverTTC();
        foreach ($items as $item) {
            if ($seller->getId() === $item->getProduct()->getSeller()->getId()) {
                $itemCost = $item->getDeliveredQty() * $item->getPrice();
                $totalHT += $itemCost;
                $totalTTC += $itemCost * (1 + $item->getTaxRate());
            }
        }
        return ['HT' => $totalHT, 'TTC' => $totalTTC];
    }

    private function getSellers($order)
    {
        $sellers = [];
        foreach ($order->getItems() as $item) {
            $seller = $item->getProduct()->getSeller();
            $index = $this->getSellerIndex($seller, $sellers);
            $sellers[$index] = $seller;
        }
        return $sellers;
    }

    private function getSellerIndex($seller, $sellers)
    {
        $index = count($sellers);
        foreach ($sellers as $key => $selected) {
            if ($selected->getId() === $seller->getId()) {
                $index = $key;
                break;
            }
        }
        return $index;
    }
}