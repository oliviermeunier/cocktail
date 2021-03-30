<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Repository\CocktailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CocktailController extends AbstractController
{
    /**
     * @Route("/cocktail/{slug}", name="cocktail.index")
     */
    public function index(Cocktail $cocktail): Response
    {
        return $this->render('cocktail/index.html.twig', [
            'cocktail' => $cocktail,
        ]);
    }
}
