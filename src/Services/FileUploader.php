<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\File\File;

class FileUploader
{
    const FILE_UPLOAD_DIRECTORY = '/public/uploads';

    public function __construct(
        private readonly string $targetDirectory,
    ) {
    }

    public function upload(File $file): string
    {
        $fileName = self::generateHashedFilename($file->getFilename()) . '-' . $file->getClientOriginalName();
        $file->move(
            $this->targetDirectory . self::FILE_UPLOAD_DIRECTORY,
            $fileName
        );

        return $fileName;
    }

    private function generateHashedFilename(string $fileName): string
    {
        return $this->base64_encode_url(md5($fileName . time()));
    }

    function base64_encode_url($string): array|string
    {
        return str_replace(['+','/','='], ['-','_',''], base64_encode($string));
    }
}