<?php

namespace App\Entity\Chronopost;

class SkybillValue
{
   /*
    * @var string
    */
    private $bulkNumber;

   /*
    * @var string
    */
    private $codCurrency;

   /*
    * @var float
    */
    private $codValue;

   /*
    * @var string
    */
    private $content1;

   /*
    * @var string
    */
    private $content2;

   /*
    * @var string
    */
    private $content3;

   /*
    * @var string
    */
    private $content4;

   /*
    * @var string
    */
    private $content5;

   /*
    * @var string
    */
    private $customsCurrency;

   /*
    * @var float
    */
    private $customsValue;

   /*
    * @var string
    */
    private $evtCode;

   /*
    * @var string
    */
    private $insuredCurrency;

   /*
    * @var float
    */
    private $insuredValue;

   /*
    * @var string
    */
    private $masterSkybillNumber;

   /*
    * @var string
    */
    private $objectType;

   /*
    * @var string
    */
    private $portCurrency;

   /*
    * @var string
    */
    private $portValue;

   /*
    * @var string
    */
    private $productCode;

   /*
    * @var string
    */
    private $service;

   /*
    * @var dateTime
    */
    private $shipDate;

   /*
    * @var int
    */
    private $shipHour;

   /*
    * @var string
    */
    private $skybillRank;

   /*
    * @var float
    */
    private $weight;

   /*
    * @var string
    */
    private $weightUnit;

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
    * @var string
    */
    private $as;

    public function getBulkNumber(): ?string
    {
        return $this->bulkNumber;
    }

    public function setBulkNumber(?string $bulkNumber): self
    {
        $this->bulkNumber = $bulkNumber;

        return $this;
    }

    public function getCodCurrency(): ?string
    {
        return $this->codCurrency;
    }

    public function setCodCurrency(?string $codCurrency): self
    {
        $this->codCurrency = $codCurrency;

        return $this;
    }

    public function getCodValue(): ?float
    {
        return $this->codValue;
    }

    public function setCodValue(?float $codValue): self
    {
        $this->codValue = $codValue;

        return $this;
    }

    public function getContent1(): ?string
    {
        return $this->content1;
    }

    public function setContent1(?string $content1): self
    {
        $this->content1 = $content1;

        return $this;
    }

    public function getContent2(): ?string
    {
        return $this->content2;
    }

    public function setContent2(?string $content2): self
    {
        $this->content2 = $content2;

        return $this;
    }

    public function getContent3(): ?string
    {
        return $this->content3;
    }

    public function setContent3(?string $content3): self
    {
        $this->content3 = $content3;

        return $this;
    }

    public function getContent4(): ?string
    {
        return $this->content4;
    }

    public function setContent4(?string $content4): self
    {
        $this->content4 = $content4;

        return $this;
    }

    public function getContent5(): ?string
    {
        return $this->content5;
    }

    public function setContent5(?string $content5): self
    {
        $this->content5 = $content5;

        return $this;
    }

    public function getCustomsCurrency(): ?string
    {
        return $this->customsCurrency;
    }

    public function setCustomsCurrency(?string $customsCurrency): self
    {
        $this->customsCurrency = $customsCurrency;

        return $this;
    }

    public function getCustomsValue(): ?float
    {
        return $this->customsValue;
    }

    public function setCustomsValue(?float $customsValue): self
    {
        $this->customsValue = $customsValue;

        return $this;
    }

    public function getEvtCode(): ?string
    {
        return $this->evtCode;
    }

    public function setEvtCode(?string $evtCode): self
    {
        $this->evtCode = $evtCode;

        return $this;
    }

    public function getInsuredCurrency(): ?string
    {
        return $this->insuredCurrency;
    }

    public function setInsuredCurrency(?string $insuredCurrency): self
    {
        $this->insuredCurrency = $insuredCurrency;

        return $this;
    }

    public function getInsuredValue(): ?float
    {
        return $this->insuredValue;
    }

    public function setInsuredValue(?float $insuredValue): self
    {
        $this->insuredValue = $insuredValue;

        return $this;
    }

    public function getMasterSkybillNumber(): ?string
    {
        return $this->masterSkybillNumber;
    }

    public function setMasterSkybillNumber(?string $masterSkybillNumber): self
    {
        $this->masterSkybillNumber = $masterSkybillNumber;

        return $this;
    }

    public function getObjectType(): ?string
    {
        return $this->objectType;
    }

    public function setObjectType(?string $objectType): self
    {
        $this->objectType = $objectType;

        return $this;
    }

    public function getPortCurrency(): ?string
    {
        return $this->portCurrency;
    }

    public function setPortCurrency(?string $portCurrency): self
    {
        $this->portCurrency = $portCurrency;

        return $this;
    }

    public function getPortValue(): ?string
    {
        return $this->portValue;
    }

    public function setPortValue(?string $portValue): self
    {
        $this->portValue = $portValue;

        return $this;
    }

    public function getProductCode(): ?string
    {
        return $this->productCode;
    }

    public function setProductCode(?string $productCode): self
    {
        $this->productCode = $productCode;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getShipDate(): ?\DateTimeInterface
    {
        return $this->shipDate;
    }

    public function setShipDate(?\DateTimeInterface $shipDate): self
    {
        $this->shipDate = $shipDate;

        return $this;
    }

    public function getShipHour(): ?int
    {
        return $this->shipHour;
    }

    public function setShipHour(?int $shipHour): self
    {
        $this->shipHour = $shipHour;

        return $this;
    }

    public function getSkybillRank(): ?string
    {
        return $this->skybillRank;
    }

    public function setSkybillRank(?string $skybillRank): self
    {
        $this->skybillRank = $skybillRank;

        return $this;
    }

    public function getWeight(): ?float
    {
        return $this->weight;
    }

    public function setWeight(?float $weight): self
    {
        $this->weight = $weight;

        return $this;
    }

    public function getWeightUnit(): ?string
    {
        return $this->weightUnit;
    }

    public function setWeightUnit(?string $weightUnit): self
    {
        $this->weightUnit = $weightUnit;

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

    public function getAs(): ?string
    {
        return $this->as;
    }

    public function setAs(?string $as): self
    {
        $this->as = $as;

        return $this;
    }
}
