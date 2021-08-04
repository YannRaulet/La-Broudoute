<?php

namespace App\Controller;

use App\Entity\Product;
use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductsController extends AbstractController
{
    /**
     * @Route("/nos-produits", name="products")
     */
   /*  public function index(ProductRepository $productRepository): Response
    {
        return $this->render('products/index.html.twig', [
            'products' => $productRepository->findAll()
        ]);
    } */

    /**
     * @Route("/produit/{slug}", name="product")
     */
   /*  public function show(ProductRepository $productRepository, string $slug): Response
    {
        return $this->render('products/show.html.twig', [
            'products' => $productRepository->findOneBySlug($slug)
        ]);
      
    } */


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager) {
        return $this->entityManager = $entityManager;
    }

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(ProductRepository $productRepository): Response
    {
        $products = $this->entityManager->getRepository(Product::class)->findAll();

        return $this->render('products/index.html.twig', [
            'products' => $products
        ]);
    }

    /**
     * @Route("/produit/{id}", name="product")
     */
    /*     public function show(ProductRepository $productRepository, int $id): Response
    {

        return $this->render('products/show.html.twig', [
            'product' => $productRepository->findBy(
                [ 'id' => $id]
            )   
        ]);
      
    } */


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
