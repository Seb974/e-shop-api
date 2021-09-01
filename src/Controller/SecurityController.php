<?php

namespace App\Controller;

use App\Entity\Meta;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\Email\ResetPasswordNotifier;
use App\Service\Request\PostRequest;
use App\Service\Security\TokenGenerator;
use App\Service\User\OrdersAnonymizer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
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

    /**
     * @Route("/api/forgot-password", name="app_forgot_password", methods={"POST"})
     *
     * Informations :
     * Send an email with a token to reset password.
     */
    public function forgotPassword(Request $request, PostRequest $postRequest, UserRepository $userRepository, TokenGenerator $tokenGenerator, ResetPasswordNotifier $notifier)
    {
        $data = $postRequest->getData($request);
        $user = $userRepository->findOneBy(['email' => $data->get('username')]);
        $em = $this->getDoctrine()->getManager();
        if ( is_null($user) )
            return new JsonResponse(['error' => 'The email you entered doesn\'t exist'], 404);

        $reset = new ResetPassword();
        $reset->setEmail($data->get('username'))
              ->setToken($tokenGenerator->generate(15))
              ->setIsUsed(false);

        $em->persist($reset);
        $em->flush();

        return new JsonResponse($notifier->notify($reset));
    }

    /**
     * @Route("/api/reset_account_password/{id}", name="app_reset_account_password", methods={"POST"})
     *
     * Informations :
     * Updates the password
     */
    public function resetAccountPassword(ResetPassword $resetPassword, Request $request, PostRequest $postRequest, UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $data = $postRequest->getData($request);
        $user = $userRepository->findOneBy(['email' => $resetPassword->getEmail()]);
        $hash = $encoder->encodePassword($user, $data->get('password'));
        $resetPassword->setIsUsed(true);
        $user->setPassword($hash);

        $em = $this->getDoctrine()->getManager();
        $em->flush();

        return new JsonResponse(['success' => true]);
    }

    /**
     * @Route("/api/facebook_login", name="app_facebook_login", methods={"POST"})
     *
     * Informations :
     * Authenticate a user using Facebook
     */
    public function logWithFacebook(Request $request, PostRequest $postRequest, UserRepository $userRepository, JWTTokenManagerInterface $JWTManager, UserPasswordEncoderInterface $encoder)
    {
        $data = $postRequest->getData($request);
        $user = $userRepository->findOneBy(['email' => $data->get('email')]);

        if (is_null($user))
            $user = $this->createNewFacebookUser($data, $encoder);

        $authenticationSuccessHandler = $this->container->get('lexik_jwt_authentication.handler.authentication_success');
        return $authenticationSuccessHandler->handleAuthenticationSuccess($user, $JWTManager->create($user));
    }

    private function createNewFacebookUser($data, $encoder)
    {
        $metas = new Meta();
        $user = new User();
        $user->setName($data->get('name'))
             ->setEmail($data->get('email'))
             ->setPassword($encoder->encodePassword($user, $data->get('userID')))
             ->setMetas($metas);

        $em = $this->getDoctrine()->getManager();
        $em->persist($user);
        $em->flush();

        return $user;
    }
}