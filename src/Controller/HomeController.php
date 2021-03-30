<?php

namespace App\Controller;

use App\Repository\CategoryRepository;
use App\Repository\CocktailRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home.index")
     */
    public function index(Request $request, CocktailRepository $cocktailRepository, CategoryRepository $categoryRepository): Response
    {
        $categoryId = null;
        $createdAtMin = null;

        if ($request->query->count()) {
            if ($request->query->has('category')) {
                $categoryId = $request->query->get('category');
            }
            if ($request->query->has('created-at-min')) {
                $createdAtMin = $request->query->get('created-at-min');
            }
        }

        $cocktails = $cocktailRepository->search($categoryId, $createdAtMin);

        dump($cocktails);

        $categories = $categoryRepository->findBy([], ['label' => 'ASC']);

        return $this->render('home/index.html.twig', [
            'cocktails' => $cocktails,
            'categories' => $categories
        ]);
    }
}
