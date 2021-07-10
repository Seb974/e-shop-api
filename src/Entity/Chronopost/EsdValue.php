<?php

namespace App\Entity\Chronopost;

use DateTimeInterface;

class EsdValue
{
   /*
    * @var dateTime 
    */
    private $closingDateTime;

   /*
    * @var float 
    */
    private $height;

   /*
    * @var float 
    */
    private $length;

   /*
    * @var float 
    */
    private $width;

   /*
    * @var dateTime 
    */
    private $retrievalDateTime;

   /*
    * @var string 
    */
    private $shipperBuildingFloor;

   /*
    * @var string 
    */
    private $shipperCarriesCode;

   /*
    * @var string 
    */
    private $shipperServiceDirection;

   /*
    * @var string 
    */
    private $specificInstructions;

   /*
    * @var int 
    */
    private $ltAImprimerParChronopost;

   /*
    * @var int 
    */
    private $nombreDePassageMaximum;

   /*
    * @var string 
    */
    private $refEsdClient;

    public function getClosingDateTime(): ?\DateTimeInterface
    {
        return $this->closingDateTime;
    }

    public function setClosingDateTime(?\DateTimeInterface $closingDateTime): self
    {
        $this->closingDateTime = $closingDateTime;

        return $this;
    }

    public function getHeight(): ?float
    {
        return $this->height;
    }

    public function setHeight(?float $height): self
    {
        $this->height = $height;

        return $this;
    }

    public function getLength(): ?float
    {
        return $this->length;
    }

    public function setLength(?float $length): self
    {
        $this->length = $length;

        return $this;
    }

    public function getWidth(): ?float
    {
        return $this->width;
    }

    public function setWidth(?float $width): self
    {
        $this->width = $width;

        return $this;
    }

    public function getRetrievalDateTime(): ?\DateTimeInterface
    {
        return $this->retrievalDateTime;
    }

    public function setRetrievalDateTime(?\DateTimeInterface $retrievalDateTime): self
    {
        $this->retrievalDateTime = $retrievalDateTime;

        return $this;
    }

    public function getShipperBuildingFloor(): ?string
    {
        return $this->shipperBuildingFloor;
    }

    public function setShipperBuildingFloor(?string $shipperBuildingFloor): self
    {
        $this->shipperBuildingFloor = $shipperBuildingFloor;

        return $this;
    }

    public function getShipperCarriesCode(): ?string
    {
        return $this->shipperCarriesCode;
    }

    public function setShipperCarriesCode(?string $shipperCarriesCode): self
    {
        $this->shipperCarriesCode = $shipperCarriesCode;

        return $this;
    }

    public function getShipperServiceDirection(): ?string
    {
        return $this->shipperServiceDirection;
    }

    public function setShipperServiceDirection(?string $shipperServiceDirection): self
    {
        $this->shipperServiceDirection = $shipperServiceDirection;

        return $this;
    }

    public function getSpecificInstructions(): ?string
    {
        return $this->specificInstructions;
    }

    public function setSpecificInstructions(?string $specificInstructions): self
    {
        $this->specificInstructions = $specificInstructions;

        return $this;
    }

    public function getLtAImprimerParChronopost(): ?int
    {
        return $this->ltAImprimerParChronopost;
    }

    public function setLtAImprimerParChronopost(?int $ltAImprimerParChronopost): self
    {
        $this->ltAImprimerParChronopost = $ltAImprimerParChronopost;

        return $this;
    }

    public function getNombreDePassageMaximum(): ?int
    {
        return $this->nombreDePassageMaximum;
    }

    public function setNombreDePassageMaximum(?int $nombreDePassageMaximum): self
    {
        $this->nombreDePassageMaximum = $nombreDePassageMaximum;

        return $this;
    }

    public function getRefEsdClient(): ?string
    {
        return $this->refEsdClient;
    }

    public function setRefEsdClient(?string $refEsdClient): self
    {
        $this->refEsdClient = $refEsdClient;

        return $this;
    }
}
