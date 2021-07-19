<?php

namespace App\Service\Axonaut;

use App\Entity\Catalog;
use App\Entity\Provision;
use App\Repository\CatalogRepository;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Expense
{
    private $key;
    private $domain;
    private $client;
    private $catalogRepository;

    public function __construct($key, $domain, HttpClientInterface $client, CatalogRepository $catalogRepository)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
        $this->catalogRepository = $catalogRepository;
    }

    public function createExpense(Provision $provision)
    {
        $axonautExpense = $this->getAxonautExpense($provision);
        $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautExpense];
        $response = $this->client->request('POST', $this->domain . 'expenses', $parameters);
        $content = $response->toArray();
        return $content;
    }

    private function getAxonautExpense(Provision $provision)
    {
        $catalog = $this->getDefaultCatalog();
        $supplier = $provision->getSupplier();
        $provisionDate = $provision->getProvisionDate();
        return [
            'supplier_id' => $supplier->getAccountingId(),
            'title' => $supplier->getName() . ' - Le ' . $provisionDate->format('d/m/Y'),
            'date' => $provisionDate->format(\DateTime::RFC3339),
            'expense_lines' => $this->getPurchases($provision, $catalog)
        ];
    }

    private function getPurchases(Provision $provision, Catalog $catalog)
    {
        $purchases = [];
        foreach ($provision->getGoods() as $good) {
            if ($good->getReceived() > 0) {
                $product = $good->getProduct();
                $purchases[] = [
                        'internal_id' => $product->getAccountingId(),
                        'name' => $product->getName(),
                        'price' => $good->getPrice(),
                        'tax_rate' => $this->getTaxRate($product, $catalog),
                        'quantity' => $good->getReceived()
                ];
            }
        }
        return $purchases;
    }

    private function getTaxRate($product, $catalog)
    {
        $selectedTaxRate = 0;
        $taxes = $product->getTax()->getCatalogTaxes();
        foreach ($taxes as $tax) {
            if ($tax->getCatalog()->getId() === $catalog->getId()) {
                $selectedTaxRate = $tax->getPercent();
                break;
            }
        }
        return $selectedTaxRate * 100;
    }

    private function getDefaultCatalog()
    {
        return $this->catalogRepository->findOneBy(['isDefault' => true]);
    }
}