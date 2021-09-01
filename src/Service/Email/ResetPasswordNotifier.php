<?php

namespace App\Service\Email;

use App\Entity\ResetPassword;
use App\Service\Email\Mailer as EmailMailer;

class ResetPasswordNotifier
{
    private $mailer;
    private $appName;
    private $publicRoot;

    public function __construct($appName, $publicRoot, EmailMailer $mailer)
    {
        $this->mailer = $mailer;
        $this->appName = $appName;
        $this->publicRoot = $publicRoot;
    }

    public function notify(ResetPassword $reset)
    {
        return $this->mailer->sendMessage(
            $reset->getEmail(),
            $this->appName . " : Demande de réinitialisation de mot de passe.",
            "email/reset.html.twig",
            ['reset' => $reset, 'name' => $this->appName, 'root' => $this->publicRoot]
        );
    }
}