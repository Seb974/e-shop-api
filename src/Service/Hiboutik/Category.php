<?php

namespace App\Service\Hiboutik;

use App\Entity\Store;
use App\Entity\Department;
use App\Service\Hiboutik\Request;

class Category
{
    private $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function sendCategories(Store $store, $categories)
    {
        if (!is_null($store->getUrl()) && !is_null($store->getUser()) && !is_null($store->getApiKey())) {
            $hiboutikCategories = $this->getHiboutikCategories($store);
            foreach ($categories as $category) {
                $existingCategory = $this->getHiboutikCategory($category, $hiboutikCategories);
                if (is_null($existingCategory) || count($existingCategory) == 0) {
                    $body = $this->getFormattedCategory($category);
                    $this->request->send($store, 'POST', $store->getUrl() . '/api/categories', $body);
                } else {
                    $body = $this->getFormattedExistingCategory($category, $existingCategory);
                    $this->request->send($store, 'PUT', $store->getUrl() . '/api/categories', $body);
                }
            }
        }
    }

    public function getHiboutikCategories(Store $store)
    {
        return $this->request->send($store, 'GET', $store->getUrl() . '/api/categories');
    }

    private function getFormattedCategory(Department $category)
    {
        return [
            "category_name" => $category->getName(),
            "category_bck_color" => '#' . substr(str_shuffle('ABCDEF0123456789'), 0, 6),
            "category_ref_ext" => $category->getId()
        ];
    }

    private function getFormattedExistingCategory(Department $category, array $existingCategory) {
        return [
            "category_id" => $existingCategory["category_id"],
            "category_name" => $category->getName(),
            "category_ref_ext" => $category->getId()
        ];
    }

    private function getHiboutikCategory(Department $category, array $hiboutikCategories)
    {
        $existingCategory = null;
        foreach ($hiboutikCategories as $hiboutikCategory) {
            if (intval($hiboutikCategory["category_ref_ext"]) == $category->getId()) {
                $existingCategory = $hiboutikCategory;
                break;
            }
        }
        return $existingCategory;
    }
}