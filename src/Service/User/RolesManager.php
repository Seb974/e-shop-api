<?php

namespace App\Service\User;

use App\Entity\User;
use App\Repository\GroupRepository;

class RolesManager
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
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
        $shopGroups = $this->groupRepository->findBy(["hasShopAccess" => true]);
        $shopRoles = $this->getArrayRoles($shopGroups);
        $filteredShopRoles = array_diff($shopRoles, ["ROLE_USER"]);
        $userShopRoles = array_intersect($user->getRoles(), $filteredShopRoles);
        return count($userShopRoles) > 0 ? array_values(array_filter($userShopRoles)) : ["ROLE_USER"];
    }

    public function getAdminRoles(User $user)
    {
        $adminGroups = $this->groupRepository->findBy(["hasAdminAccess" => true]);
        $adminRoles = $this->getArrayRoles($adminGroups);
        $userAdminRoles = array_intersect($user->getRoles(), $adminRoles);
        return count($userAdminRoles) > 0 ? array_values(array_filter($userAdminRoles)) : ["ROLE_USER"];
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