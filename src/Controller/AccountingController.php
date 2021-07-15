<?php

namespace App\Controller;

use App\Entity\OrderEntity;
use App\Service\Axonaut\AxonautUser;
use App\Repository\ProductRepository;
use App\Service\Axonaut\AxonautProduct;
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
}