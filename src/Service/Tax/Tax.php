<?php

namespace App\Service\Tax;

use App\Entity\Group;
use App\Entity\Catalog;
use App\Service\User\UserGroupDefiner;
use Symfony\Component\Security\Core\Security;

class Tax
{
    private $security;
    private $userGroupDefiner;

    public function __construct(Security $security, UserGroupDefiner $userGroupDefiner)
    {
        $this->security = $security;
        $this->userGroupDefiner = $userGroupDefiner;
    }

    public function getTaxRate($entity, Catalog $catalog)
    {
        $user = $this->security->getUser();
        $userGroup = $this->userGroupDefiner->getUserGroup($user);

        if (!$userGroup->getSubjectToTaxes())
            return 0;

        foreach ($entity->getTax()->getCatalogTaxes() as $catalogTax) {
            if ($catalogTax->getCatalog()->getId() === $catalog->getId())
                return $catalogTax->getPercent();
        }
    }
}