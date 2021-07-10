<?php

namespace App\Entity\Chronopost;

class CustomerValue
{
   /* 
    * @var string
    */
    private $customerAdress1;

   /* 
    * @var string
    */
    private $customerAdress2;

   /* 
    * @var string
    */
    private $customerCity;

   /* 
    * @var string
    */
    private $customerCivility;

   /* 
    * @var string
    */
    private $customerContactName;

   /* 
    * @var string
    */
    private $customerCountry;

   /* 
    * @var string
    */
    private $customerCountryName;

   /* 
    * @var string
    */
    private $customerEmail;

   /* 
    * @var string
    */
    private $customerMobilePhone;

   /* 
    * @var string
    */
    private $customerName;

   /* 
    * @var string
    */
    private $customerName2;
    
   /* 
    * @var string
    */
    private $customerPhone;

   /* 
    * @var int
    */
    private $customerPreAlert;

   /* 
    * @var string
    */
    private $customerZipCode;

   /* 
    * @var string
    */
    private $printAsSender;

    public function getCustomerAdress1(): ?string
    {
        return $this->customerAdress1;
    }

    public function setCustomerAdress1(?string $customerAdress1): self
    {
        $this->customerAdress1 = $customerAdress1;

        return $this;
    }

    public function getCustomerAdress2(): ?string
    {
        return $this->customerAdress2;
    }

    public function setCustomerAdress2(?string $customerAdress2): self
    {
        $this->customerAdress2 = $customerAdress2;

        return $this;
    }

    public function getCustomerCity(): ?string
    {
        return $this->customerCity;
    }

    public function setCustomerCity(?string $customerCity): self
    {
        $this->customerCity = $customerCity;

        return $this;
    }

    public function getCustomerCivility(): ?string
    {
        return $this->customerCivility;
    }

    public function setCustomerCivility(?string $customerCivility): self
    {
        $this->customerCivility = $customerCivility;

        return $this;
    }

    public function getCustomerContactName(): ?string
    {
        return $this->customerContactName;
    }

    public function setCustomerContactName(?string $customerContactName): self
    {
        $this->customerContactName = $customerContactName;

        return $this;
    }

    public function getCustomerCountry(): ?string
    {
        return $this->customerCountry;
    }

    public function setCustomerCountry(?string $customerCountry): self
    {
        $this->customerCountry = $customerCountry;

        return $this;
    }

    public function getCustomerCountryName(): ?string
    {
        return $this->customerCountryName;
    }

    public function setCustomerCountryName(?string $customerCountryName): self
    {
        $this->customerCountryName = $customerCountryName;

        return $this;
    }

    public function getCustomerEmail(): ?string
    {
        return $this->customerEmail;
    }

    public function setCustomerEmail(?string $customerEmail): self
    {
        $this->customerEmail = $customerEmail;

        return $this;
    }

    public function getCustomerMobilePhone(): ?string
    {
        return $this->customerMobilePhone;
    }

    public function setCustomerMobilePhone(?string $customerMobilePhone): self
    {
        $this->customerMobilePhone = $customerMobilePhone;

        return $this;
    }

    public function getCustomerName(): ?string
    {
        return $this->customerName;
    }

    public function setCustomerName(?string $customerName): self
    {
        $this->customerName = $customerName;

        return $this;
    }

    public function getCustomerName2(): ?string
    {
        return $this->customerName2;
    }

    public function setCustomerName2(?string $customerName2): self
    {
        $this->customerName2 = $customerName2;

        return $this;
    }

    public function getCustomerPhone(): ?string
    {
        return $this->customerPhone;
    }

    public function setCustomerPhone(?string $customerPhone): self
    {
        $this->customerPhone = $customerPhone;

        return $this;
    }

    public function getCustomerPreAlert(): ?int
    {
        return $this->customerPreAlert;
    }

    public function setCustomerPreAlert(?int $customerPreAlert): self
    {
        $this->customerPreAlert = $customerPreAlert;

        return $this;
    }

    public function getCustomerZipCode(): ?int
    {
        return $this->customerZipCode;
    }

    public function setCustomerZipCode(?int $customerZipCode): self
    {
        $this->customerZipCode = $customerZipCode;

        return $this;
    }

    public function getPrintAsSender(): ?string
    {
        return $this->printAsSender;
    }

    public function setPrintAsSender(?string $printAsSender): self
    {
        $this->printAsSender = $printAsSender;

        return $this;
    }
}