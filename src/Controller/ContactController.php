<?php

namespace App\Controller;

use App\Classe\Mailjet;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ContactController extends AbstractController
{
    /**
     * @Route("/nous-contacter", name="contact")
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {
        $form = $this->createForm(ContactType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $this->addFlash('notice', 'merci de nous avoir contacté, notre équipe va vous répondre dans les meilleurs délais');
            $formData = $form->getData();

            $content = "Bonjour, <br/>Vous avez une nouvelle demande de contact de ".$formData['nom']. ' ' .$formData['prenom']."<br>"."<br>".
            "Email du client : " .$formData['email']. "<br/>"."<br/>".$formData['content'];
            $mail = new Mailjet();
            $mail->send('yann.raulet@hotmail.fr', 'La Broudoute', 'Vous avez reçu une nouvelle demande d\'information', $content);          
        }

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
