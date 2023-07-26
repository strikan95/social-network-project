<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\File;

class ImageManagerService
{
    const PATH_TO_IMAGE_DIR = '/app/public/images/';

    public function __construct(){}

    public static function upload(File $file): string
    {
        $fileName = self::generateHashedFilename($file->getFilename()) . '-' . $file->getClientOriginalName();
        $file->move(
            self::PATH_TO_IMAGE_DIR,
            $fileName
        );

        return self::PATH_TO_IMAGE_DIR . $fileName;
    }

    private static function generateHashedFilename(string $fileName): string
    {
        return md5($fileName . time());
    }
}