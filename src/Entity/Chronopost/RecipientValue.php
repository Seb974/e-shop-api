<?php

namespace App\Entity\Chronopost;

class RecipientValue
{
   /*
    * @var string
    */
    private $recipientAdress1;

   /*
    * @var string
    */
    private $recipientAdress2;

   /*
    * @var string
    */
    private $recipientCity;

   /*
    * @var string
    */
    private $recipientCivility;

   /*
    * @var string
    */
    private $recipientContactName;

   /*
    * @var string
    */
    private $recipientCountry;

   /*
    * @var string
    */
    private $recipientCountryName;

   /*
    * @var string
    */
    private $recipientEmail;

   /*
    * @var string
    */
    private $recipientMobilePhone;

   /*
    * @var string
    */
    private $recipientName;

   /*
    * @var string
    */
    private $recipientName2;

   /*
    * @var string
    */
    private $recipientPhone;

   /*
    * @var int
    */
    private $recipientPreAlert;
    
   /*
    * @var string
    */
    private $recipientZipCode;

   /*
    * @var string
    */
    private $recipientType;

    public function getRecipientAdress1(): ?string
    {
        return $this->recipientAdress1;
    }

    public function setRecipientAdress1(?string $recipientAdress1): self
    {
        $this->recipientAdress1 = $recipientAdress1;

        return $this;
    }

    public function getRecipientAdress2(): ?string
    {
        return $this->recipientAdress2;
    }

    public function setRecipientAdress2(?string $recipientAdress2): self
    {
        $this->recipientAdress2 = $recipientAdress2;

        return $this;
    }

    public function getRecipientCity(): ?string
    {
        return $this->recipientCity;
    }

    public function setRecipientCity(?string $recipientCity): self
    {
        $this->recipientCity = $recipientCity;

        return $this;
    }

    public function getRecipientCivility(): ?string
    {
        return $this->recipientCivility;
    }

    public function setRecipientCivility(?string $recipientCivility): self
    {
        $this->recipientCivility = $recipientCivility;

        return $this;
    }

    public function getRecipientContactName(): ?string
    {
        return $this->recipientContactName;
    }

    public function setRecipientContactName(?string $recipientContactName): self
    {
        $this->recipientContactName = $recipientContactName;

        return $this;
    }

    public function getRecipientCountry(): ?string
    {
        return $this->recipientCountry;
    }

    public function setRecipientCountry(?string $recipientCountry): self
    {
        $this->recipientCountry = $recipientCountry;

        return $this;
    }

    public function getRecipientCountryName(): ?string
    {
        return $this->recipientCountryName;
    }

    public function setRecipientCountryName(?string $recipientCountryName): self
    {
        $this->recipientCountryName = $recipientCountryName;

        return $this;
    }

    public function getRecipientEmail(): ?string
    {
        return $this->recipientEmail;
    }

    public function setRecipientEmail(?string $recipientEmail): self
    {
        $this->recipientEmail = $recipientEmail;

        return $this;
    }

    public function getRecipientMobilePhone(): ?string
    {
        return $this->recipientMobilePhone;
    }

    public function setRecipientMobilePhone(?string $recipientMobilePhone): self
    {
        $this->recipientMobilePhone = $recipientMobilePhone;

        return $this;
    }

    public function getRecipientName(): ?string
    {
        return $this->recipientName;
    }

    public function setRecipientName(?string $recipientName): self
    {
        $this->recipientName = $recipientName;

        return $this;
    }

    public function getRecipientName2(): ?string
    {
        return $this->recipientName2;
    }

    public function setRecipientName2(?string $recipientName2): self
    {
        $this->recipientName2 = $recipientName2;

        return $this;
    }

    public function getRecipientPhone(): ?string
    {
        return $this->recipientPhone;
    }

    public function setRecipientPhone(?string $recipientPhone): self
    {
        $this->recipientPhone = $recipientPhone;

        return $this;
    }

    public function getRecipientPreAlert(): ?int
    {
        return $this->recipientPreAlert;
    }

    public function setRecipientPreAlert(?int $recipientPreAlert): self
    {
        $this->recipientPreAlert = $recipientPreAlert;

        return $this;
    }

    public function getRecipientZipCode(): ?string
    {
        return $this->recipientZipCode;
    }

    public function setRecipientZipCode(?string $recipientZipCode): self
    {
        $this->recipientZipCode = $recipientZipCode;

        return $this;
    }

    public function getRecipientType(): ?string
    {
        return $this->recipientType;
    }

    public function setRecipientType(?string $recipientType): self
    {
        $this->recipientType = $recipientType;

        return $this;
    }
}