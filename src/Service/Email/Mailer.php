<?php

namespace App\Service\Email;

use App\Repository\PlatformRepository;

class Mailer
{
    private $sender;
    private $mailer;
    private $templating;
    private $platformRepository;

    public function __construct($sender, \Swift_Mailer $mailer, \Twig\Environment $templating, PlatformRepository $platformRepository)
    {
        $this->sender       = $sender;
        $this->mailer       = $mailer;
        $this->templating   = $templating;
        $this->platformRepository = $platformRepository;
    }

    public function sendMessage($sendTo, string $subject, string $template, array $args = [])
    {
        try {
            $platform = $this->getPlatform();
            $message = new \Swift_Message();
            $message->setSubject($subject)
                    ->setFrom([$this->sender => $platform->getName()])
                    ->setTo($sendTo)
                    ->setBody($this->templating->render($template, $args), 'text/html');
            $return = $this->mailer->send($message);
            $status = $return !== false && $return > 0 ? 'done' : 'failed';
        } catch (\Exception $e) {
            $status = 'failed';
            dump($e->getMessage());
        } finally {
            return $status;
        }
    }

    private function getPlatform()
    {
        return $this->platformRepository->find(1);
    }
}