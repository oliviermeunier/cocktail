<?php


namespace App\Service;


use App\Entity\Cocktail;
use Symfony\Component\HttpFoundation\File\File;

class CocktailUploaderHelper
{
    /**
     * @var UploaderHelper
     */
    private $uploaderHelper;

    public function __construct(UploaderHelper $uploaderHelper)
    {
        $this->uploaderHelper = $uploaderHelper;
    }

    public function uploadCocktailImage(File $file, Cocktail $cocktail)
    {
        if ($file) {

            $filename = $this->uploaderHelper->upload($file);

            // Enregistrement du nom du fichier dans l'entité
            $cocktail->setImage($filename);
        }
    }
}