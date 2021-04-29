<?php

namespace App\Service\User;

use App\Repository\GroupRepository;

class UserGroupDefiner
{
    private $groupRepository;

    public function __construct(GroupRepository $groupRepository)
    {
        $this->groupRepository = $groupRepository;
    }

    public function getUserGroup($user) {
        $roles = $user !== null ? $user->getRoles() : ["ROLE_USER"];
        $filteredRoles = array_diff($roles, ["ROLE_USER"]);
        $role = count($filteredRoles) > 0 ? $filteredRoles[0] : "ROLE_USER";
        return $this->groupRepository->findOneBy(['value' => $role]);
    }

}