<?php

namespace App\Service\Request;

use Symfony\Component\HttpFoundation\Request;

/**
 * PostRequest
 *
 * Informations :
 * The unique public method 'getData' of this service extracts and serve json datas into array;
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class PostRequest
{
    public function getData(Request $request) {
        if (strpos($request->headers->get('Content-Type'), 'application/json') === 0) {
            $data = json_decode($request->getContent(), true);
            $request->request->replace(is_array($data) ? $data : array());
        }
        return $request->request;
    }
}