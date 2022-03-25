<?php

namespace App\EventSubscriber\Provision;

use App\Entity\Product;
use App\Entity\Provision;
use App\Service\Axonaut\Expense;
use App\Service\Stock\StockManager;
use App\Service\Hiboutik\Product as Hiboutik;
use App\Service\Sms\ProvisionNotifier as SmsNotifier;
use App\Service\Email\ProvisionNotifier as EmailNotifier;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\ViewEvent;
use ApiPlatform\Core\EventListener\EventPriorities;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class ProvisionCreationSubscriber implements EventSubscriberInterface 
{
    private $axonaut;
    private $hiboutik;
    private $stockManager;
    private $smsNotifier;
    private $emailNotifier;

    public function __construct(SmsNotifier $smsNotifier, EmailNotifier $emailNotifier, StockManager $stockManager, Expense $axonaut, Hiboutik $hiboutik)
    {
        $this->axonaut = $axonaut;
        $this->hiboutik = $hiboutik;
        $this->stockManager = $stockManager;
        $this->smsNotifier = $smsNotifier;
        $this->emailNotifier = $emailNotifier;
    }

    public static function getSubscribedEvents()
    {
        return [ KernelEvents::VIEW => ['fitOrder', EventPriorities::PRE_WRITE] ];
    }

    public function fitOrder(ViewEvent $event)
    {
        $result = $event->getControllerResult();
        $request = $event->getRequest();
        $method = $request->getMethod();

        if ( $result instanceof Provision ) {
            if ( $method === "POST" ) {
                $status = !is_null($result->getStatus()) ? $result->getStatus() : "ORDERED";
                if ($status === "ORDERED") {
                    if (str_contains(strtoupper($result->getSendingMode()), "SMS"))
                        $this->smsNotifier->notifyOrder($result);
                    if (str_contains(strtoupper($result->getSendingMode()), "EMAIL"))
                        $this->emailNotifier->notify($result);
                }
                $result->setStatus($status)
                       ->setIntegrated(false);
            }
            else if ( $method === "PUT" && $result->getStatus() === "ORDERED" && !$result->getIntegrated() ) {
                $this->integrateProvision($result);
                $this->axonaut->createExpense($result);
                $result->setStatus("RECEIVED")
                       ->setIntegrated(true);
            }
        }
    }

    private function integrateProvision($provision)
    {
        $supplier = $provision->getSupplier();
        foreach ($provision->getGoods() as $good) {
            $product = $good->getProduct();
            $this->updateProductCost($product, $provision, $good->getPrice());
            $this->stockManager->addToStock($good, $provision);
            $this->updateCost($good, $supplier);
        }
    }

    private function updateCost($good, $supplier) {
        $product = $good->getProduct();
        foreach ($product->getCosts() as $cost) {
            if ($cost->getSupplier()->getId() == $supplier->getId() && $cost->getValue() !== $good->getPrice()) {
                $cost->setValue($good->getPrice());
                break;
            }
        }
    }

    private function updateProductCost(Product $product, Provision $provision, float $price)
    {
        $store = $provision->getStore();
        if (!is_null($store)) {
            $hiboutikProduct = $this->hiboutik->getHiboutikProductByProductId($store, $product->getId());
            $this->hiboutik->updateProductCost($store, $hiboutikProduct["product_id"], $price);
        } else {
            $product->setLastCost($price);
        }
    }
}