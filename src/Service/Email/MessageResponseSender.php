<?php

namespace App\Service\Email;

use App\Entity\Message;
use App\Repository\PlatformRepository;
use App\Service\Email\Mailer as EmailMailer;

class MessageResponseSender
{
    private $mailer;
    private $platformRepository;

    public function __construct(EmailMailer $mailer, PlatformRepository $platformRepository)
    {
        $this->mailer = $mailer;
        $this->platformRepository = $platformRepository;
    }

    public function reply(Message $message)
    {
        $platform = $this->getPlatform();
        return $this->mailer->sendMessage(
            $message->getEmail(),
            $platform->getName() . " : Réponse à votre sollicitation",
            "email/message.html.twig",
            ['message' => $message, 'name' => $platform->getName()]
        );
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}