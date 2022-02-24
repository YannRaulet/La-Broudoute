<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegisterController extends AbstractController
{

    /**
     * @Route("/inscription", name="register")
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $userPasswordHasherInterface
        ): Response
    {
        $notification = null;
        $user = new User();
        $form = $this->createForm(RegisterType::class, $user);

        $form->handleRequest($request); // request listening form

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $search_email = $manager->getRepository(User::class)->findOneByEmail($user->getEmail());
           
            // If email is not in the DB
           if (!$search_email) {
                // encode the plain password
                $user->setPassword(
                    $userPasswordHasherInterface->hashPassword(
                        $user,
                        //$form->get('plainPassword')->getData() ANCIEN
                        $form->get('password')->getData()
                    )
                );

                $manager->persist($user);
                $manager->flush();

                $mail = new Mailjet();
                $content = "Bonjour ".$user->getFirstname()."<br>"."Bienvenue sur la boutique de la Broudoute - BROU BROU.";
                $mail->send($user->getEmail(), $user->getFirstname(), 'Bienvenue sur la Broudoute', $content); 

                $notification = "Votre inscription s'est correctement déroulée, vous pouvez dès à présent vous connecter à votre compte";
           } else {
                $notification = "L'email que vous avez renseigné existe déjà.";
           }
           

            // do anything else you need here, like send an email
            /* return $this->redirectToRoute('app_login'); */
        }

        return $this->render('security/register.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification
        ]);
    }
}
