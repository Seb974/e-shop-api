<?php

namespace App\Service\Email;

use Swift_Attachment;

class BackupSender
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

    public function send(string $file)
    {
        try {
            $status = 'done';
            $message = new \Swift_Message();
            $message->setSubject('Backup du ' . (new \DateTime())->format('d/m/Y'))
                    ->setFrom([$this->sender => $this->appName])
                    ->setTo([$this->receptor])
                    ->setBody("Backup de la base de donnée du " . (new \DateTime())->format('d/m/Y'))
                    ->attach(Swift_Attachment::fromPath($file))
                    ;
            $this->mailer->send($message);
        } catch (\Exception $e) {
            $status = 'failed';
        } finally {
            return $status;
        }
    }
}