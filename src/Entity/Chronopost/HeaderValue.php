<?php

namespace App\Entity\Chronopost;

class HeaderValue
{
   /*
    * @var int
    */
    private $accountNumber;

   /*
    * @var string
    */
    private $idEmit;

   /*
    * @var int
    */
    private $subAccount;

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function getIdEmit(): ?string
    {
        return $this->idEmit;
    }

    public function setIdEmit(?string $idEmit): self
    {
        $this->idEmit = $idEmit;

        return $this;
    }

    public function getSubAccount(): ?int
    {
        return $this->subAccount;
    }

    public function setSubAccount(?int $subAccount): self
    {
        $this->subAccount = $subAccount;

        return $this;
    }
}