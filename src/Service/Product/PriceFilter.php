<?php

namespace App\Service\Product;

use Symfony\Component\Security\Core\Security;
use App\Service\User\UserGroupDefiner;

/**
 * PriceFilter
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
    private $userGroupDefiner;

    public function __construct(Security $security, UserGroupDefiner $userGroupDefiner)
    {
        $this->security = $security;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public function filter($response)
    {
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);
        if ( !array_key_exists('hydra:member', $response) ) {
            $this->setPrice($response, $userGroup);
            unset($response['userGroups']);
        } else {
            foreach($response['hydra:member'] as &$product) {
                $this->setPrice($product, $userGroup);
                unset($product['userGroups']);
            }
        }
        return $response;
    }

    private function setPrice(&$product, $userGroup)
    {
        $product['price'] = $this->getCorrespondingPrice($userGroup, $product);
        unset($product['prices']);
    }

    private function getCorrespondingPrice($userGroup, $product)
    {
        $priceGroup = $userGroup->getPriceGroup();
        foreach ($product['prices'] as $price) {
            if ($price['priceGroup']['id'] === $priceGroup->getId()) {
                return array_key_exists('amount', $price) ? $price['amount'] : 0;
            }
        }
        return 0;
    }
}