<?php

namespace App\Service\Axonaut;

use App\Entity\User;
use App\Entity\Product;
use App\Entity\OrderEntity;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class AxonautInvoice
{
    private $key;
    private $domain;
    private $client;

    public function __construct($key, $domain, HttpClientInterface $client)
    {
        $this->key = $key;
        $this->domain = $domain;
        $this->client = $client;
    }

    public function createInvoices($invoices)
    {
        $contents = [];
        foreach ($invoices as $invoice) {
            $customer = $invoice['customer'];
            $metas = $invoice['metas'];
            $orders = $invoice['orders'];
            $axonautInvoice = $this->getAxonautInvoice($customer, $metas, $orders);
            $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautInvoice];
            $response = $this->client->request('POST', $this->domain . 'invoices', $parameters);
            $content = $response->toArray();
            $contents[] = $content;
        }
        return $content;
    }

    // public function updateProduct($product)
    // {
    //     $axonautId = $product->getAccountingId();
    //     if (is_null($axonautId))
    //         return $this->createInvoice($product);

    //     $axonautProduct = $this->getAxonautInvoice($product);
    //     $parameters = [ 'headers' => ['userApiKey' => $this->key], 'body' => $axonautProduct];
    //     $response = $this->client->request('PATCH', $this->domain . 'products/' . $axonautId, $parameters);
    //     $content = $response->toArray();
    //     return $content['id'];
    // }

    private function getAxonautInvoice($customer, $metas, $orders)
    {
        
        return [
            'company_id' => $customer,
         // 'date' => 
            'products' => [
                [
                    'id' => 0,
                    // 'internal_id' => '',
                    // 'product_code' => '',
                    'name' => '',
                    'price' => 0,
                    'tax_rate' => 0,
                    'quantity' => 0,
                    'description' => '',
                    // 'chapter' => '',
                    'discount_percent' => 0,
                    'discount_flat' => 0,
                    // 'unit_job_costing' => 0
                ]
            ],
            'delivery_address' => [
                'company_name' => '',
                'contact_name' => '',
                'street' => $metas->getAddress(),
                'zip_code' => $metas->getZipcode(),
                'city' => $metas->getCity(),
                'region' => '',
                'country' => ''
            ],
            // 'mandatory_mentions' => '',
            // 'theme_id' => 0,
            'allow_gocardless_payment' => true,
            'allow_stripe_payment' => true,
            // 'service_start_date' => '',
            // 'service_end_date' => '',
            // 'order_number' => ''
        ];

        
    }

    private function getItems($orders)
    {

    }




    public function createQuote(OrderEntity $order, User $user)
    {
        $products = $this->getProducts($order);
        $parameters = [
            'headers' => ['userApiKey' => $this->key],
            'body'    => [
                // 'company_id'     => $user->getCharacteristic(),
                'online_payment' => false,
                'products'       => $products
            ],
       ];
       $this->client->request('POST', $this->domain . 'quotations', $parameters);
    }

    public function createOrder(OrderEntity $order, User $user)
    {
        $now = new \DateTime();
        $lastDayMonth = date_modify($now, 'last day of this month');	
        $now_ts = $now->getTimestamp();
        $lastDayMonth_ts = $lastDayMonth->getTimestamp();
        $products = $this->getProducts($order);
        $parameters = [
            'headers' => ['userApiKey' => $this->key],
            'body'    => [
                'name'                          => $user->getName(),
                // 'company_id'                    => $user->getCharacteristic(),
                'start_date_ts'                 => $now_ts,
                'end_date_ts'                   => $now_ts,
                'first_invoice_planned_date_ts' => $lastDayMonth_ts,
                'invoice_frequency_in_months'   => 0,
                // 'user_id'                       => 0,
                'products'                      => $products
            ],
       ];
        $response = $this->client->request('POST', $this->domain . 'contracts', $parameters);
        $content = $response->getContent();
        $content = $response->toArray();
        return $content;

    }

    // public function createInvoice(OrderEntity $order, User $user)
    // {
    //     $products = $this->getProducts($order);
    //     $parameters = [
    //         'headers' => ['userApiKey' => $this->key],
    //         'body'    => [
    //             'employee_email' => '',
    //             'contract_id' => 0,
    //             'order_number' => '',
    //             'products'       => $products
    //         ],
    //    ];
    //    $this->client->request('POST', $this->domain . 'invoices', $parameters);
    // }

    private function getProducts(OrderEntity $order)
    {
        $formattedItems = [];
        foreach ($order->getItems() as $item) {
            $product = $item->getProduct();
            $formattedItem = [
                // 'id' => 2219794,
                'internal_id'      => ''.$product->getId(),
                'name'             => $product->getName(),
                'price'            => $item->getPrice(),
                'tax_rate'         => $product->getTax()->getRate(),
                'quantity'         => $item->getQuantity(),
                // 'discount_percent' => 0,
                // 'discount_flat'    => 0
            ];
            $formattedItems[] = $formattedItem;
        }
        return $formattedItems;
    }
}