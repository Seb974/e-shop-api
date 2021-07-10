<?php

namespace App\Entity\Chronopost;

class CancelSkybill
{
   /*
    * @var int
    */
    private $accountNumber;

   /*
    * @var string
    */
    private $password;

   /*
    * @var string
    */
    private $language;

   /*
    * @var string
    */
    private $skybillNumber;


    public function getAccountNumber(): ?int
    {
        return $this->accountNumber;
    }

    public function setAccountNumber(?int $accountNumber): self
    {
        $this->accountNumber = $accountNumber;

        return $this;
    }

    public function setPassword(?string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setLanguage(?string $language): self
    {
        $this->language = $language;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setSkybillNumber(?string $skybillNumber): self
    {
        $this->skybillNumber = $skybillNumber;

        return $this;
    }

    public function getSkybillNumber(): ?string
    {
        return $this->skybillNumber;
    }
}