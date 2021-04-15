<?php

namespace App\Service\Product;

use Symfony\Component\Security\Core\Security;

/**
 * SerializerSubscriber
 *
 * Informations :
 * The unique public method 'filter' of this service sets the 'price' variable into 
 * the product entity returned by the api, and slice the prices array for users that 
 * are not granted to ROLE_TEAM.
 *
 * This 'price' variable is defined by filtering the prices array using the group that 
 * the current user belong to.
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class PriceFilter
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function filter($response)
    {
        if ( !array_key_exists('hydra:member', $response) ) {
            $this->setPrice($response);
        } else {
            foreach($response['hydra:member'] as &$product) {
                $this->setPrice($product);
            }
        }
        return $response;
    }

    private function setPrice(&$product)
    {
        $product['price'] = 0;
        if (!$this->security->isGranted("ROLE_TEAM")) {
            unset($product['prices']);
        }
    }
}