<?php

namespace App\Controller;

use App\Entity\Picture;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * CreatePictureAction
 *
 * Informations :
 * This invokable class handles the file upload
 *
 * @author Sébastien : sebastien.maillot@coding-academy.fr
 */
final class CreatePictureAction
{
    public function __invoke(Request $request): Picture
    {
        $uploadedFile = $request->files->get('file');
        if (!$uploadedFile) {
            throw new BadRequestHttpException('"file" is required');
        }

        $picture = new Picture();
        $picture->file = $uploadedFile;

        return $picture;
    }
}