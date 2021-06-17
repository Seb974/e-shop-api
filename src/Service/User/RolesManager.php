<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\GroupRepository;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;

class RolesManager
{
    private $groupRepository;
    private $accessDecisionManager;

    public function __construct(GroupRepository $groupRepository, AccessDecisionManagerInterface $accessDecisionManager)
    {
        $this->groupRepository = $groupRepository;
        $this->accessDecisionManager = $accessDecisionManager;
    }

    public function isUserGranted(User $user, $attributes, $object = null)
    {
        if (!is_array($attributes))
            $attributes = [$attributes];

        $token = new UsernamePasswordToken($user, 'none', 'none', $user->getRoles());

        return ($this->accessDecisionManager->decide($token, $attributes, $object));
    }

    public function hasAdminAccess($roles)
    {
        $adminGroups = $this->groupRepository->findBy(["hasAdminAccess" => true]);
        $adminRoles = $this->getArrayRoles($adminGroups);
        return count(array_intersect($roles, $adminRoles)) > 0;
    }

    public function hasShopAccess($roles)
    {
        $shopGroups = $this->groupRepository->findBy(["hasShopAccess" => true]);
        $shopRoles = $this->getArrayRoles($shopGroups);
        return count(array_intersect($roles, $shopRoles)) > 0;
    }

    public function getShopRoles(User $user)
    {
        return $this->filterShopRoles($user->getRoles());
    }

    public function getAdminRoles(User $user)
    {
        return $this->filterAdminRoles($user->getRoles());
    }

    public function filterShopRoles($roles)
    {
        $shopGroups = $this->groupRepository->findBy(["hasShopAccess" => true]);
        $shopRoles = $this->getArrayRoles($shopGroups);
        $filteredShopRoles = array_diff($shopRoles, ["ROLE_USER"]);
        $userShopRoles = array_intersect($roles, $filteredShopRoles);
        return count($userShopRoles) > 0 ? array_values(array_filter($userShopRoles)) : ["ROLE_USER"];
    }

    public function filterAdminRoles($roles)
    {
        $adminGroups = $this->groupRepository->findBy(["hasAdminAccess" => true]);
        $adminRoles = $this->getArrayRoles($adminGroups);
        $userAdminRoles = array_intersect($roles, $adminRoles);
        return count($userAdminRoles) > 0 ? array_values(array_filter($userAdminRoles)) : ["ROLE_USER"];
    }

    private function getArrayRoles($groups)
    {
        $arrayRoles = [];
        foreach ($groups as $group) {
            $arrayRoles[] = $group->getValue();
        }
        return $arrayRoles;
    }
}