<?php

namespace App\Service\User;

use App\Service\User\RolesManager;
use App\Repository\GroupRepository;

class UserGroupDefiner
{
    private $adminDomain;
    private $requestStack;
    private $rolesManager;
    private $groupRepository;

    public function __construct($requestStack, $admin, RolesManager $rolesManager, GroupRepository $groupRepository)
    {
        $this->adminDomain = $admin;
        $this->rolesManager = $rolesManager;
        $this->requestStack = $requestStack;
        $this->groupRepository = $groupRepository;
    }

    public function getUserGroup($user)
    {
        $request = $this->requestStack->getCurrentRequest();
        $origin = $request->headers->get('origin');

        $roles = $user == null ? ["ROLE_USER"] : ($origin == $this->adminDomain ?
            $this->rolesManager->getAdminRoles($user) :
            $this->rolesManager->getShopRoles($user));

        $filteredRoles = array_diff($roles, ["ROLE_USER"]);
        $role = count($filteredRoles) > 0 ? array_values(array_filter($filteredRoles))[0] : "ROLE_USER";
        return $this->groupRepository->findOneBy(['value' => $role]);
    }

    public function getShopGroup($user)
    {
        $roles = $user == null ? ["ROLE_USER"] : $this->rolesManager->getShopRoles($user);
        
        $filteredRoles = array_diff($roles, ["ROLE_USER"]);
        $role = count($filteredRoles) > 0 ? array_values(array_filter($filteredRoles))[0] : "ROLE_USER";
        return $this->groupRepository->findOneBy(['value' => $role]);
    }

}