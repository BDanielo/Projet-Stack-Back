<?php
// src/Service/FileUpload.php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{
    private $uploadsDirectory;

    public function __construct(string $uploadsDirectory)
    {
        $this->uploadsDirectory = $uploadsDirectory;
    }

    public function uploadImage(UploadedFile $file): string
    {
        // Check if the uploaded file is an image
        if (strpos($file->getMimeType(), 'image/') !== 0) {
            throw new FileException('The uploaded file is not an image.');
            // or handle the error as required
        }

        $fileName = md5(uniqid()) . '.' . $file->guessExtension();

        try {
            $file->move($this->uploadsDirectory, $fileName);
        } catch (FileException $e) {
            // Handle file upload error
            throw new FileException('Failed to upload the file');
        }

        return $fileName;
    }
}
