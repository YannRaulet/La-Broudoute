<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegisterController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/inscription", name="register")
     */
    public function index(Request $request, UserPasswordEncoderInterface $encoder): Response
    {
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request); // formulaire écoute requête

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();// Tu injectes dans l'objet user toutes les données récupérées du formulaire

            $password = $encoder->encodePassword($user, $user->getPassword()); //Mot de passe crypté dans variable
            $user->setPassword($password); //Réinjecte dans objet User

            $this->entityManager->persist($user); //figer la data
            $this->entityManager->flush(); // enregistrer en BDD
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
