<?php

namespace App\Controller;

use App\Service\Request\PostRequest;
use App\Service\Email\Mailer as EmailMailer;
use App\Service\Email\NewsletterSubscriber;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * EmailController
 *
 * Informations :
 * Contains email actions
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class EmailController extends AbstractController
{
    /**
     * @Route("/api/email/send", name="send-email", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     * 
     * Informations :
     * Send an email
     */
    public function send(Request $request, PostRequest $postRequest, EmailMailer $mailer): JsonResponse
    {
        $data = $postRequest->getData($request);
        $email = $data->all();

        $status = $mailer->sendMessage(
            $email['email'],
            $email['subject'],
            "email/email.html.twig",
            ['subject' => strval($email['subject']), 'message' => strval($email['message']), 'name' => $this->getParameter('app.name')]
        );

        return new JsonResponse(['done' => $status]);
    }

    /**
     * @Route("/api/newsletter/subscribe", name="newsletter-subscribe", methods={"POST"})
     *
     * Informations :
     * Send an email
     */
    public function subscribeToNewsletter(Request $request, PostRequest $postRequest, NewsletterSubscriber $newsletterSubscriber): JsonResponse
    {
        $status = 'failed';
        $requestData = $postRequest->getData($request);
        $data = $requestData->all();

        $at = strpos($data['email'], '@');
        if ($at !== false) {
            $name = substr($data['email'], 0, $at);
            $formattedName = str_replace(['.', '_', '-'], " ", $name);

            $status = $newsletterSubscriber->add($data['email'], $formattedName);
        }


        return new JsonResponse(['status' => $status]);
    }
}