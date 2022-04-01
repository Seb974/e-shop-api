<?php

namespace App\Service\Email;

use App\Repository\PlatformRepository;
use Swift_Attachment;

class BackupSender
{
    private $sender;
    private $mailer;
    private $receptor;
    private $platformRepository;

    public function __construct($sender, $receptor, \Swift_Mailer $mailer, PlatformRepository $platformRepository)
    {
        $this->sender       = $sender;
        $this->mailer       = $mailer;
        $this->receptor     = $receptor;
        $this->platformRepository = $platformRepository;
    }

    public function send(string $file)
    {
        try {
            $status = 'done';
            $platform = $this->getPlatform();
            $message = new \Swift_Message();
            $message->setSubject('Backup du ' . (new \DateTime())->format('d/m/Y'))
                    ->setFrom([$this->sender => $platform->getName()])
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

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}