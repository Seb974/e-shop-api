<?php

namespace App\Service\Security;

/**
 * TokenGenerator
 *
 * Informations :
 * Create a random string with the desired length;
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
class TokenGenerator
{
    public function generate(int $length) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}