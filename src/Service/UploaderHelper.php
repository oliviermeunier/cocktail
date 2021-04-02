<?php


namespace App\Service;


use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\File;

class UploaderHelper
{
    private $uploadsBaseDir;
    private $uploadsBaseUrl;

    public function __construct(string $uploadsBaseDir, string $uploadsBaseUrl)
    {
        $this->uploadsBaseDir = $uploadsBaseDir;
        $this->uploadsBaseUrl = $uploadsBaseUrl;
    }

    public function upload(File $file)
    {
        try {
            $filename = sha1(uniqid()) . '.' . $file->guessExtension();
            $file->move($this->uploadsBaseDir, $filename);

            return $filename;
        }
        catch(FileException $exception) {
            return false;
        }
    }

    public function assetUploadUrl(string $path): string
    {
        // Si le path est une URL, on la retourne telle quelle
        if (filter_var($path, FILTER_VALIDATE_URL)) {
            return $path;
        }

        // Sinon on construit le chemin vers le fichier dans le dossier d'uploads
        return $this->uploadsBaseUrl . '/' . $path;
    }
}