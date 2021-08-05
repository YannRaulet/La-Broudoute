<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CartController extends AbstractController
{

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
        $this->entityManager = $entityManager;
    }

    /**
     * @Route("/mon-panier", name="cart")
     */
    public function index(Cart $cart): Response
    {
        $CartComplete = [];

        foreach ($cart->get() as $id =>$quantity) {
            $CartComplete[] = [
                'product' => $this->entityManager->getRepository(Product::class)->findOneById($id),
                'quantity' => $quantity
            ];
        }
        
        return $this->render('cart/index.html.twig', [
            'cart' => $CartComplete
        ]);
    }

    /**
     * @Route("/cart/add/{id}", name="add_to_cart")
     */
    public function add(int $id, Cart $cart): Response
    {
        $cart->add($id);

        return $this->redirectToRoute('cart');
    }

    /**
     * @Route("/cart/remove", name="remove_my_cart")
     */
    public function remove( Cart $cart): Response
    {
        $cart->remove();

        return $this->redirectToRoute('products');
    }

}
