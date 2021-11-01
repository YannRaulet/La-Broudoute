<?php

namespace App\Controller;

use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;


class AccountPasswordController extends AbstractController
{
    /**
     * @Route("/compte/modifier-mon-mot-de-passe", name="account_password")
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager,
        UserPasswordHasherInterface $userPasswordHasherInterface
        ): Response
    {
        $notification = null;
        $notificationRed = null;

        $user = $this->getUser();                                           // Calls the user object                      
        $form = $this->createForm(ChangePasswordType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();               // retrieves the password typed in the form
            if($userPasswordHasherInterface->isPasswordValid($user, $old_pwd)) {         // Compare the database password to the one you typed
                $new_pwd  = $form->get('new_password')->getData();
                $password = $userPasswordHasherInterface->hashPassword($user, $new_pwd); // Password hashed in variable

                $user->setPassword($password);                              //  
                $manager->flush();                                          // no need to persist in a data modification.
                $notification = "Votre mot de passe à bien été mis à jour.";
            } else {
                $notificationRed = "Votre mot de passe actuel n'est pas le bon.";

            }
        }

        return $this->render('account/password.html.twig', [
            'form' => $form->createView(),
            'notification' => $notification,
            'notificationRed' => $notificationRed
        ]);
    }
}
