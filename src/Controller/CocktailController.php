<?php

namespace App\Controller;

use App\Entity\Cocktail;
use App\Form\CocktailType;
use App\Form\CommentType;
use App\Repository\CocktailRepository;
use App\Service\CocktailUploaderHelper;
use App\Service\UploaderHelper;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CocktailController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/coktail-{slug}", name="cocktail.index")
     */
    public function index(Cocktail $cocktail, Request $request): Response
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $comment = $form->getData();
            $comment->setUser($this->getUser());
            $comment->setCocktail($cocktail);

            $this->manager->persist($comment);
            $this->manager->flush();

            $this->addFlash('success', 'Votre commentaire a bien été pris en compte.');
            return $this->redirectToRoute('cocktail.index', ['slug' => $cocktail->getSlug()]);
        }

        return $this->render('cocktail/index.html.twig', [
            'cocktail' => $cocktail,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/ajax/cocktail/{slug}", name="cocktail.ajaxAddComment", methods={"POST"})
     */
    public function ajaxAddComment(Cocktail $cocktail, Request $request, ValidatorInterface $validator)
    {
        $form = $this->createForm(CommentType::class);
        $form->handleRequest($request);

        $comment = $form->getData();

        if ($form->isValid()) {

            $comment->setUser($this->getUser());
            $comment->setCocktail($cocktail);

            $this->manager->persist($comment);
            $this->manager->flush();

            // Retourner les données du commentaire ajouté en JSON
            $htmlComment = $this->renderView('partials/_comment.html.twig', ['comment' => $comment]);

            return $this->json([
                'success' => true,
                'message' => 'Votre commentaire a bien été ajouté.',
                'comment' => $htmlComment
            ]);
        }

        // En cas d'erreur, retourner les erreurs en JSON
        $violations = $validator->validate($comment);
        $errors = [];

        foreach ($violations as $violation) {

            // @TODO gérer plusieurs erreurs pour un même champ
            $errors[$violation->getPropertyPath()] = $violation->getMessage();
        }

        return $this->json([
            'success' => false,
            'errors' => $errors
        ]);
    }

    /**
     * @Route("/cocktail/new", name="cocktail.new")
     * @return Response
     */
    public function new(Request $request, SluggerInterface $slugger, CocktailUploaderHelper $uploaderHelper): Response
    {
        $form = $this->createForm(CocktailType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $cocktail = $form->getData();
            $cocktail->setUser($this->getUser());
            $cocktail->setSlug($slugger->slug($cocktail->getName()));

            // Gestion du fichier image par le service CocktailUploaderHelper
            $uploaderHelper->uploadCocktailImage($form->get('imageFile')->getData(), $cocktail);

            $this->manager->persist($cocktail);
            $this->manager->flush();

            $this->addFlash('success', 'Votre cocktail a bien été ajouté.');
            return $this->redirectToRoute('home.index');
        }

        return $this->render('cocktail/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

}
