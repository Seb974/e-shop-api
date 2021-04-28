<?php

namespace App\Service\Product;

use Symfony\Component\Security\Core\Security;
use App\Repository\GroupRepository;

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
    private $groupRepository;

    public function __construct(Security $security, GroupRepository $groupRepository)
    {
        $this->security = $security;
        $this->groupRepository = $groupRepository;
    }

    public function filter($response)
    {
        $userGroup = $this->getUserGroup();
        if ( !array_key_exists('hydra:member', $response) ) {
            $this->setPrice($response, $userGroup);
            $this->setTaxes($response, $userGroup);
        } else {
            foreach($response['hydra:member'] as &$product) {
                $this->setPrice($product, $userGroup);
                $this->setTaxes($product, $userGroup);
            }
        }
        return $response;
    }

    private function getUserGroup() {
        $user = $this->security->getUser();
        $roles = $user !== null ? $user->getRoles() : ["ROLE_USER"];
        $filteredRoles = array_diff($roles, ["ROLE_USER"]);
        $role = count($filteredRoles) > 0 ? $filteredRoles[0] : "ROLE_USER";
        return $this->groupRepository->findOneBy(['value' => $role]);
    }

    private function setPrice(&$product, $userGroup)
    {
        $product['price'] = $this->getCorrespondingPrice($userGroup, $product);
        unset($product['prices']);
    }

    private function setTaxes(&$product, $userGroup) {
        $product['taxes'] = $this->getCorrespondingTaxes($product, $userGroup);
        unset($product['tax']);
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

    private function getCorrespondingTaxes($product, $userGroup) {
        $taxes = [];
        foreach ($product['tax']['rates'] as $rate) {
            $taxes[] = [
                'country' => $rate['name'], 
                'rate'    => $userGroup->getSubjectToTaxes() ? floatval($rate['value']) : 0];
        }
        return $taxes;
    }
}