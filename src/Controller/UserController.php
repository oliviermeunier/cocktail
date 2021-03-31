<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/signup", name="user.signup")
     */
    public function signup(Request $request, EntityManagerInterface $manager): Response
    {
        $form = $this->createForm(UserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {

            // Récupération des données du formulaire
            $user = $form->getData();

            // Persistance en base de données
            $manager->persist($user);
            $manager->flush();

            // Message flash
            $this->addFlash('success', 'Votre compte a bien été créé, vous pouvez vous connecter.');

            // Redirection vers la page de login
            return $this->redirectToRoute('security.login');
        }

        return $this->render('user/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
