<?php

namespace App\Service\Email;

use App\Entity\ResetPassword;
use App\Repository\PlatformRepository;
use App\Service\Email\Mailer as EmailMailer;

class ResetPasswordNotifier
{
    private $mailer;
    private $publicRoot;
    private $platformRepository;

    public function __construct($publicRoot, EmailMailer $mailer, PlatformRepository $platformRepository)
    {
        $this->mailer = $mailer;
        $this->publicRoot = $publicRoot;
        $this->platformRepository = $platformRepository;
    }

    public function notify(ResetPassword $reset)
    {
        $platform = $this->getPlatform();
        return $this->mailer->sendMessage(
            $reset->getEmail(),
            $platform->getName() . " : Demande de réinitialisation de mot de passe.",
            "email/reset.html.twig",
            ['reset' => $reset, 'name' => $platform->getName(), 'root' => $this->publicRoot]
        );
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}