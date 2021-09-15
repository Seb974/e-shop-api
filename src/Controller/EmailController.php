<?php

namespace App\Controller;

use App\Service\Request\PostRequest;
use App\Service\Email\Mailer as EmailMailer;
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
 * @IsGranted("ROLE_ADMIN")
 */
class EmailController extends AbstractController
{
    /**
     * @Route("/api/email/send", name="send-email", methods={"POST"})
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
}