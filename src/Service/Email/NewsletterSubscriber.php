<?php

namespace App\Service\Email;

class NewsletterSubscriber
{
    private $sender;
    private $mailer;
    private $appName;
    private $receptor;

    public function __construct($sender, $appName, $receptor, \Swift_Mailer $mailer)
    {
        $this->sender       = $sender;
        $this->mailer       = $mailer;
        $this->appName      = $appName;
        $this->receptor     = $receptor;
    }

    public function add(string $email, string $name = "")
    {
        try {
            $status = 'done';
            $namePart = strlen($name) > 0 ? "Nom # " . $name . "\n": "";

            $message = new \Swift_Message();
            $message->setSubject('Création d\'un prospect')
                    ->setFrom([$this->sender => $this->appName])
                    ->setTo([$this->receptor])
                    ->setBody($namePart . "Email # " . $email)
                    ;
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $status = 'failed';
        } finally {
            return $status;
        }
    }
}