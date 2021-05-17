<?php

namespace App\Controller;

use App\Repository\UserRepository;
use App\Service\Request\PostRequest;
use App\Service\User\OrdersAnonymizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
/**
 * SecurityController
 *
 * Informations :
 * Contains authentication actions
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class SecurityController extends AbstractController
{
    /**
     * @Route("/logout", name="app_logout", methods={"GET"})
     *
     * Informations :
     * Removes the authentication cookies on the browser
     * (BEARER and mercureAuthorization)
     */
    public function logout(): Response
    {
        throw new \Exception('Unreachable code');
    }

    /**
     * @Route("/api/reset-password", name="app_update_password", methods={"POST"})
     *
     * Informations :
     * Checks if the credentials sent by user are good and updates password if it is.
     */
    public function updatePassword(Request $request, PostRequest $postRequest, UserPasswordEncoderInterface $encoder, UserRepository $userRepository)
    {
        $data = $postRequest->getData($request);
        $user = $userRepository->findOneBy(['email' => $data->get('username')]);
        $em = $this->getDoctrine()->getManager();
        if ( !$encoder->isPasswordValid($user, $data->get('passwords')['current']) ) {
            return new JsonResponse(['isAuthenticated' => false]);
        }
        $hash = $encoder->encodePassword($user, $data->get('passwords')['newPassword']);
        $user->setPassword($hash);
        $em->flush();
        return new JsonResponse(['isAuthenticated' => true]);
    }

    /**
     * @Route("/api/delete-account", name="app_delete_account", methods={"POST"})
     *
     * Informations :
     * Checks if the credentials sent by user are good and delete account if it is.
     */
    public function deleteAccount(Request $request, PostRequest $postRequest, UserPasswordEncoderInterface $encoder, UserRepository $userRepository, OrdersAnonymizer $ordersAnonymizer)
    {
        $data = $postRequest->getData($request);
        $user = $userRepository->findOneBy(['email' => $data->get('username')]);
        $em = $this->getDoctrine()->getManager();
        if ( !$encoder->isPasswordValid($user, $data->get('password')) ) {
            return new JsonResponse(['isAuthenticated' => false]);
        }
        $ordersAnonymizer->anonymize($user);
        $em->remove($user);
        $em->flush();
        return new JsonResponse(['isAuthenticated' => true]);
    }
}
