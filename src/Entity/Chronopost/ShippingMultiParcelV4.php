<?php

namespace App\Entity\Chronopost;

use App\Entity\Chronopost\EsdValue;
use App\Entity\Chronopost\RefValue;
use App\Entity\Chronopost\HeaderValue;
use App\Entity\Chronopost\ShipperValue;
use App\Entity\Chronopost\SkybillValue;
use App\Entity\Chronopost\CustomerValue;
use App\Entity\Chronopost\RecipientValue;
use App\Entity\Chronopost\SkybillParamsValue;

class ShippingMultiParcelV4
{
   /*
    * @var EsdWithRefClientValue 
    */
    private $esdValue;

   /*
    * @var HeaderValue 
    */
    private $headerValue;

   /*
    * @var ShipperValue
    */
    private $shipperValue;

   /*
    * @var CustomerValue
    */
    private $customerValue;

   /*
    * @var RecipientValue
    */
    private $recipientValue;

   /*
    * @var RefValue
    */
    private $refValue;

   /*
    * @var SkybillWithDimensionsValue
    */
    private $skybillValue;

   /*
    * @var SkybillParamsValue
    */
    private $skybillParamsValue;

   /*
    * @var string
    */
    private $password;

   /*
    * @var string
    */
    private $modeRetour;

   /*
    * @var int
    */
    private $numberOfParcel;

   /*
    * @var string
    */
    private $version;

   /*
    * @var string
    */
    private $multiParcel;

    public function getEsdValue(): ?EsdValue
    {
        return $this->esdValue;
    }

    public function setEsdValue(?EsdValue $esdValue): self
    {
        $this->esdValue = $esdValue;

        return $this;
    }

    public function getHeaderValue(): ?HeaderValue
    {
        return $this->headerValue;
    }

    public function setHeaderValue(?HeaderValue $headerValue): self
    {
        $this->headerValue = $headerValue;

        return $this;
    }

    public function getShipperValue(): ?ShipperValue
    {
        return $this->shipperValue;
    }

    public function setShipperValue(?ShipperValue $shipperValue): self
    {
        $this->shipperValue = $shipperValue;

        return $this;
    }

    public function getCustomerValue(): ?CustomerValue
    {
        return $this->customerValue;
    }

    public function setCustomerValue(?CustomerValue $customerValue): self
    {
        $this->customerValue = $customerValue;

        return $this;
    }

    public function getRecipientValue(): ?RecipientValue
    {
        return $this->recipientValue;
    }

    public function setRecipientValue(?RecipientValue $recipientValue): self
    {
        $this->recipientValue = $recipientValue;

        return $this;
    }

    public function getRefValue(): ?RefValue
    {
        return $this->refValue;
    }

    public function setRefValue(?RefValue $refValue): self
    {
        $this->refValue = $refValue;

        return $this;
    }

    public function getSkybillValue(): ?SkybillValue
    {
        return $this->skybillValue;
    }

    public function setSkybillValue(?SkybillValue $skybillValue): self
    {
        $this->skybillValue = $skybillValue;

        return $this;
    }

    public function getSkybillParamsValue(): ?SkybillParamsValue
    {
        return $this->skybillParamsValue;
    }

    public function setSkybillParamsValue(?SkybillParamsValue $skybillParamsValue): self
    {
        $this->skybillParamsValue = $skybillParamsValue;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getModeRetour(): ?string
    {
        return $this->modeRetour;
    }

    public function setModeRetour(?string $modeRetour): self
    {
        $this->modeRetour = $modeRetour;

        return $this;
    }

    public function getNumberOfParcel(): ?int
    {
        return $this->numberOfParcel;
    }

    public function setNumberOfParcel(?int $numberOfParcel): self
    {
        $this->numberOfParcel = $numberOfParcel;

        return $this;
    }

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(?string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getMultiParcel(): ?string
    {
        return $this->multiParcel;
    }

    public function setMultiParcel(?string $multiParcel): self
    {
        $this->multiParcel = $multiParcel;

        return $this;
    }

}