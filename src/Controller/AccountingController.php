<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\OrderEntity;
use App\Service\Request\PostRequest;
use App\Service\Axonaut\User as AxonautUser;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Axonaut\Invoice as AxonautInvoice;
use App\Service\User\RolesManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * AccountingController
 *
 * Informations :
 * Contains actions to communicate with Axonaut.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class AccountingController extends AbstractController
{
    /**
     * @Route("/api/accounting/user/{id}", name="anonymous-user-create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     *
     * Informations :
     * Create an anonymous user to send order to Axonaut
     */
    public function createUserForAnonymousOrder(OrderEntity $order, AxonautUser $axonaut): JsonResponse
    {
        $id = $axonaut->createUser($order);
        return new JsonResponse($id);
    }

    /**
     * @Route("/api/accounting/invoices", name="invoices-create", methods={"POST"})
     * @IsGranted("ROLE_ADMIN")
     *
     * Informations :
     * Create invoices for orders sent using POST method
     */
    public function createInvoices(Request $request, PostRequest $postRequest, AxonautInvoice $axonaut): JsonResponse
    {
        $data = $postRequest->getData($request);
        $invoices = $data->all();
        $axonautInvoices = $axonaut->createInvoices($invoices);
        return new JsonResponse($axonautInvoices);
    }

    /**
     * @Route("/api/accounting/{id}/invoices", name="invoices-get", methods={"POST"})
     * @IsGranted("ROLE_SUPERVISOR")
     *
     * Informations :
     * Get invoices depending on user's role : Admins have access to all invoices whereas supervisors are restricted to invoices of the users they supervize
     */
    public function getInvoices(User $user, Request $request, PostRequest $postRequest, RolesManager $rolesManager, AxonautInvoice $axonaut): JsonResponse
    {
        $data = $postRequest->getData($request);
        $dates = $data->all();

        $from = new \DateTime($dates['from']);
        $to = new \DateTime($dates['to']);

        return $rolesManager->isUserGranted($user, "ROLE_ADMIN") ?
                new JsonResponse($axonaut->getAllInvoices($from, $to)) :
                new JsonResponse($axonaut->getInvoicesForUser($user->getAccountingId(), $from, $to));
    }

    /**
     * @Route("/api/accounting/payments", name="invoices-payment-create", methods={"POST"})
     * @IsGranted("ROLE_SUPERVISOR")
     *
     * Informations :
     * Get invoices depending on user's role : Admins have access to all invoices whereas supervisors are restricted to invoices of the users they supervize
     */
    public function createPayment(Request $request, PostRequest $postRequest, AxonautInvoice $axonaut): JsonResponse
    {
        $data = $postRequest->getData($request);
        $parameters = $data->all();
        return new JsonResponse($axonaut->createPayment($parameters['bills'], $parameters['paymentId']));
    }
}