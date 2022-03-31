<?php

namespace App\Controller;

use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index(
        EntityManagerInterface $manager): Response
    {
        // 1 : $isBest is an boolean, concerning the "is best" tab of the admin (featured products)
        $bestProducts = $manager->getRepository(Product::class)->findByIsBest(1);
        $headers = $manager->getRepository(Header::class)->findAll();

        return $this->render('home/index.html.twig', [
            'bestProducts' =>$bestProducts,
            'headers' => $headers
        ]);    
    }

    /**
     * @Route("/mentions-légales", name="legal_notice")
     * This controler displays the legal notices
     */
    public function legalNotice(): Response
    {
        return $this->render('home/legal_notice.html.twig');
    }
}
