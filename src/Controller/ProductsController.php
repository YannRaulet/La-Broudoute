<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class ProductsController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        return $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(Request $request): Response
    {
        $search = new Search();
        $form= $this->createForm(SearchType::class, $search);

        $form->handleRequest($request); // request listening form

        if ($form->isSubmitted() && $form->isValid()) {
            $products = $this->entityManager->getRepository(Product::class)->findWithSearch($search); // Cf ProductRepository
        } else {
            $products = $this->entityManager->getRepository(Product::class)->findAll();
        }

        return $this->render('products/index.html.twig', [
            'products' => $products,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show(string $slug): Response
    {
        $product = $this->entityManager->getRepository(Product::class)->findOneBySlug($slug);

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('products/show.html.twig', [
            'product' => $product
        ]);
      
    }
}
