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

        $form->handleRequest($request); // request listening form

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();  // You inject into the user object all the data retrieved from the form

            $password = $encoder->encodePassword($user, $user->getPassword()); // Password encrypted in variable
            $user->setPassword($password); // Feed back into User object

            $this->entityManager->persist($user); // freeze the data
            $this->entityManager->flush(); // Save it in BDD
        }

        return $this->render('register/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
