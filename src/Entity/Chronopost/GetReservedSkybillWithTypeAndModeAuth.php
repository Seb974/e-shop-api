<?php

namespace App\Entity\Chronopost;

class GetReservedSkybillWithTypeAndModeAuth
{
   /*
    * @var string
    */
    private $reservationNumber;

   /*
    * @var string
    */
    private $mode;

   /*
    * @var string
    */
    private $numberSearch;

   /*
    * @var int
    */
    private $accountNumber;

   /*
    * @var string
    */
    private $password;

    public function getReservationNumber(): ?string
    {
        return $this->reservationNumber;
    }

    public function setReservationNumber(?string $reservationNumber): self
    {
        $this->reservationNumber = $reservationNumber;

        return $this;
    }

    public function getMode(): ?string
    {
        return $this->mode;
    }

    public function setMode(?string $mode): self
    {
        $this->mode = $mode;

        return $this;
    }

    public function getNumberSearch(): ?string
    {
        return $this->numberSearch;
    }

    public function setNumberSearch(?string $numberSearch): self
    {
        $this->numberSearch = $numberSearch;

        return $this;
    }

    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

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
}