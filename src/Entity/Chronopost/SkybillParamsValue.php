<?php

namespace App\Entity\Chronopost;

class SkybillParamsValue
{
   /*
    * @var string
    */
    private $mode;
    
   /*
    * @var string
    */
    private $duplicata;

   /*
    * @var int
    */
    private $withReservation;

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getDuplicata(): ?string
    {
        return $this->duplicata;
    }

    public function setDuplicata(?string $duplicata): self
    {
        $this->duplicata = $duplicata;

        return $this;
    }

    public function getWithReservation(): ?int
    {
        return $this->withReservation;
    }

    public function setWithReservation(?int $withReservation): self
    {
        $this->withReservation = $withReservation;

        return $this;
    }
}