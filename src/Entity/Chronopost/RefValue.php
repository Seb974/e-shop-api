<?php

namespace App\Entity\Chronopost;

class RefValue
{
   /*
    * @var string
    */
    private $customerSkybillNumber;

   /*
    * @var string
    */
    private $recipientRef;
    
   /*
    * @var string
    */
    private $shipperRef;

    public function getCustomerSkybillNumber(): ?string
    {
        return $this->customerSkybillNumber;
    }

    public function setCustomerSkybillNumber(?string $customerSkybillNumber): self
    {
        $this->customerSkybillNumber = $customerSkybillNumber;

        return $this;
    }

    public function getRecipientRef(): ?string
    {
        return $this->recipientRef;
    }

    public function setRecipientRef(?string $recipientRef): self
    {
        $this->recipientRef = $recipientRef;

        return $this;
    }

    public function getShipperRef(): ?string
    {
        return $this->shipperRef;
    }

    public function setShipperRef(?string $shipperRef): self
    {
        $this->shipperRef = $shipperRef;

        return $this;
    }
}