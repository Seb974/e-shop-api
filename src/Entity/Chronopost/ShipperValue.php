<?php

namespace App\Entity\Chronopost;

class ShipperValue
{
   /*
    * @var string
    */
    private $shipperAdress1;

   /*
    * @var string
    */
    private $shipperAdress2;

   /*
    * @var string
    */
    private $shipperCity;

   /*
    * @var string
    */
    private $shipperCivility;

   /*
    * @var string
    */
    private $shipperContactName;

   /*
    * @var string
    */
    private $shipperCountry;

   /*
    * @var string
    */
    private $shipperCountryName;

   /*
    * @var string
    */
    private $shipperEmail;

   /*
    * @var string
    */
    private $shipperMobilePhone;

   /*
    * @var string
    */
    private $shipperName;

   /*
    * @var string
    */
    private $shipperName2;

   /*
    * @var string
    */
    private $shipperPhone;

   /*
    * @var int
    */
    private $shipperPreAlert;

   /*
    * @var string
    */
    private $shipperZipCode;

   /*
    * @var string
    */
    private $shipperType;

    public function getShipperAdress1(): ?string
    {
        return $this->shipperAdress1;
    }

    public function setShipperAdress1(?string $shipperAdress1): self
    {
        $this->shipperAdress1 = $shipperAdress1;

        return $this;
    }

    public function getShipperAdress2(): ?string
    {
        return $this->shipperAdress2;
    }

    public function setShipperAdress2(?string $shipperAdress2): self
    {
        $this->shipperAdress2 = $shipperAdress2;

        return $this;
    }

    public function getShipperCity(): ?string
    {
        return $this->shipperCity;
    }

    public function setShipperCity(?string $shipperCity): self
    {
        $this->shipperCity = $shipperCity;

        return $this;
    }

    public function getShipperCivility(): ?string
    {
        return $this->shipperCivility;
    }

    public function setShipperCivility(?string $shipperCivility): self
    {
        $this->shipperCivility = $shipperCivility;

        return $this;
    }

    public function getShipperContactName(): ?string
    {
        return $this->shipperContactName;
    }

    public function setShipperContactName(?string $shipperContactName): self
    {
        $this->shipperContactName = $shipperContactName;

        return $this;
    }

    public function getShipperCountry(): ?string
    {
        return $this->shipperCountry;
    }

    public function setShipperCountry(?string $shipperCountry): self
    {
        $this->shipperCountry = $shipperCountry;

        return $this;
    }

    public function getShipperCountryName(): ?string
    {
        return $this->shipperCountryName;
    }

    public function setShipperCountryName(?string $shipperCountryName): self
    {
        $this->shipperCountryName = $shipperCountryName;

        return $this;
    }

    public function getShipperEmail(): ?string
    {
        return $this->shipperEmail;
    }

    public function setShipperEmail(?string $shipperEmail): self
    {
        $this->shipperEmail = $shipperEmail;

        return $this;
    }

    public function getShipperMobilePhone(): ?string
    {
        return $this->shipperMobilePhone;
    }

    public function setShipperMobilePhone(?string $shipperMobilePhone): self
    {
        $this->shipperMobilePhone = $shipperMobilePhone;

        return $this;
    }

    public function getShipperName(): ?string
    {
        return $this->shipperName;
    }

    public function setShipperName(?string $shipperName): self
    {
        $this->shipperName = $shipperName;

        return $this;
    }

    public function getShipperName2(): ?string
    {
        return $this->shipperName2;
    }

    public function setShipperName2(?string $shipperName2): self
    {
        $this->shipperName2 = $shipperName2;

        return $this;
    }

    public function getShipperPhone(): ?string
    {
        return $this->shipperPhone;
    }

    public function setShipperPhone(?string $shipperPhone): self
    {
        $this->shipperPhone = $shipperPhone;

        return $this;
    }

    public function getShipperPreAlert(): ?int
    {
        return $this->shipperPreAlert;
    }

    public function setShipperPreAlert(?int $shipperPreAlert): self
    {
        $this->shipperPreAlert = $shipperPreAlert;

        return $this;
    }

    public function getShipperZipCode(): ?string
    {
        return $this->shipperZipCode;
    }

    public function setShipperZipCode(?string $shipperZipCode): self
    {
        $this->shipperZipCode = $shipperZipCode;

        return $this;
    }

    public function getShipperType(): ?string
    {
        return $this->shipperType;
    }

    public function setShipperType(?string $shipperType): self
    {
        $this->shipperType = $shipperType;

        return $this;
    }
}