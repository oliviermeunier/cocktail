<?php

namespace App\Controller;

use App\Repository\CocktailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(CocktailRepository $cocktailRepository): Response
    {
        $cocktails = $cocktailRepository->findAll();

        return $this->render('home/index.html.twig', [
            'cocktails' => $cocktails
        ]);
    }
}
