<?php

namespace App\Entity\Chronopost;

class TrackSkybill
{
   /*
    * @var string
    */
    private $language;

   /*
    * @var string
    */
    private $skybillNumber;

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getSkybillNumber(): ?string
    {
        return $this->skybillNumber;
    }

    public function setSkybillNumber(?string $skybillNumber): self
    {
        $this->skybillNumber = $skybillNumber;

        return $this;
    }
}
