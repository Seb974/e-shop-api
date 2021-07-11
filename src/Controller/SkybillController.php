<?php

namespace App\Controller;

use App\Entity\OrderEntity;
use App\Service\Stripe\Stripe;
use App\Service\Order\Calculator;
use App\Service\Request\PostRequest;
use App\Service\Chronopost\Chronopost;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * SkybillController
 *
 * Informations :
 * Contains actions to obtain printable label for orders destinated to be exported.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 * @IsGranted("ROLE_PICKER")
 */
class SkybillController extends AbstractController
{
    /**
     * @Route("/api/skybills/{id}", name="skybill-create", methods={"POST"})
     *
     * Informations :
     * Create the skybill in ZPL encoded format
     */
    public function create(OrderEntity $order, Chronopost $chronopost, Request $request, PostRequest $postRequest): JsonResponse
    {
        $skybill = $chronopost->getSkybill($order->getReservationNumber());
        $skybill = mb_convert_encoding($skybill, 'UTF-8', 'UTF-8');
        // $data = $postRequest->getData($request);
        // $amount = $calculator->getTotalCost($data);
        // $stripe->getClientSecret($amount)
        // ['zpl' => $skybill]
        return new JsonResponse( $skybill );
    }
}