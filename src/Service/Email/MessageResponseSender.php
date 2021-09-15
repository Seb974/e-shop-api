<?php

namespace App\Service\Email;

use App\Entity\Message;
use App\Service\Email\Mailer as EmailMailer;

class MessageResponseSender
{
    private $mailer;
    private $appName;

    public function __construct($appName, EmailMailer $mailer)
    {
        $this->mailer = $mailer;
        $this->appName = $appName;
    }

    public function reply(Message $message)
    {
        return $this->mailer->sendMessage(
            $message->getEmail(),
            $this->appName . " : Réponse à votre sollicitation",
            "email/message.html.twig",
            ['message' => $message, 'name' => $this->appName]
        );
    }
}