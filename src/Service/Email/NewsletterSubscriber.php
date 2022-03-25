<?php

namespace App\Service\Email;

use App\Repository\PlatformRepository;

class NewsletterSubscriber
{
    private $sender;
    private $mailer;
    private $appName;
    private $platformRepository;

    public function __construct($sender, $appName, \Swift_Mailer $mailer, PlatformRepository $platformRepository)
    {
        $this->sender             = $sender;
        $this->mailer             = $mailer;
        $this->appName            = $appName;
        $this->platformRepository = $platformRepository;
    }

    public function add(string $email, string $name = "")
    {
        $platform = $this->getPlatform();
        if ($platform->getHasAxonautLink() && !is_null($platform->getAxonautKey())) {
            try {
                $status = 'done';
                $namePart = strlen($name) > 0 ? "Nom # " . $name . "\n": "";

                $message = new \Swift_Message();
                $message->setSubject('Création d\'un prospect')
                        ->setFrom([$this->sender => $this->appName])
                        ->setTo([$platform->getAxonautEmail()])
                        ->setBody($namePart . "Email # " . $email)
                        ;
                $this->mailer->send($message);
            } catch (\Exception $e) {
                $status = 'failed';
            } finally {
                return $status;
            }
        }
        return 'failed';
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}