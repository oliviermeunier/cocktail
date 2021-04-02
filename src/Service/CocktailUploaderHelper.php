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

    public function uploadCocktailImage(?File $file, Cocktail $cocktail)
    {
        if ($file) {

            // Suppression de l'image actuelle le cas échéant
            $this->removeCocktailImage($cocktail);

            // Upload du fichier image dans le dossier d'uploads
            $filename = $this->uploaderHelper->upload($file);

            // Enregistrement du nom du fichier dans l'entité
            $cocktail->setImage($filename);
        }
    }

    public function removeCocktailImage(Cocktail $cocktail)
    {
        if ($image = $cocktail->getImage()) {
            $this->uploaderHelper->remove($image);
        }
    }
}