<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Form\CommentType;
use App\Repository\CocktailRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CocktailController extends AbstractController
{
    /**
     * @Route("/cocktail/{slug}", name="cocktail.index")
     */
    public function index(Cocktail $cocktail, Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $comment->setUser($this->getUser());
            $comment->setCocktail($cocktail);

            $manager->persist($comment);
            $manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été pris en compte.');
            return $this->redirectToRoute('cocktail.index', ['slug' => $cocktail->getSlug()]);
        }

        return $this->render('cocktail/index.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form->createView()
        ]);
    }
}
