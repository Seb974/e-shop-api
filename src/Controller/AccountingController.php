<?php

namespace App\Controller;

use App\Entity\OrderEntity;
use App\Service\Request\PostRequest;
use App\Service\Axonaut\User as AxonautUser;
use App\Service\Axonaut\Invoice as AxonautInvoice;
use App\Service\Axonaut\Product as AxonautProduct;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
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
 * @IsGranted("ROLE_ADMIN")
 */
class AccountingController extends AbstractController
{
    /**
     * @Route("/api/accounting/user/{id}", name="anonymous-user-create", methods={"POST"})
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
     *
     * Informations :
     * Create invoices for orders sent using POST method
     */
    public function createInvoices(Request $request, PostRequest $postRequest, AxonautInvoice $axonaut): JsonResponse
    {
        $data = $postRequest->getData($request);
        $invoices = $data->all();
        $axonautInvoices = $axonaut->createInvoices($invoices);
        $axonautPayments = $axonaut->updateAllStatuses($invoices, $axonautInvoices);
        return new JsonResponse($axonautInvoices);
    }
}