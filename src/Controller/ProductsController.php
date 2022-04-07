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

    /**
     * @Route("/nos-produits", name="products")
     */
    public function index(
        Request $request,
        EntityManagerInterface $manager
        ): Response
    {

        $search = new Search();
        $search->page = $request->get('page', 1);
        $form= $this->createForm(SearchType::class, $search);
        $form->handleRequest($request); // request listening form
        $products = $manager->getRepository(Product::class)->findWithSearch($search); // Cf ProductRepository*/
        /*
        if ($form->isSubmitted() && $form->isValid()) {
            $products = $manager->getRepository(Product::class)->findWithSearch($search); // Cf ProductRepository
        } 
        else {
            $products = $manager->getRepository(Product::class)->findAll();
        }*/
        return $this->render('products/index.html.twig', [
            'products' => $products,
            'form'=> $form->createView()
        ]);
    }

    /**
     * @Route("/produit/{slug}", name="product")
     */
    public function show(
        string $slug,
        EntityManagerInterface $manager
        ): Response
    {
        $product = $manager->getRepository(Product::class)->findOneBySlug($slug);
        $bestProducts = $manager->getRepository(Product::class)->findByIsBest(1);

        if (!$product) {
            return $this->redirectToRoute('products');
        }

        return $this->render('products/show.html.twig', [
            'product' => $product,
            'bestProducts' =>$bestProducts,
        ]);
      
    }
}
