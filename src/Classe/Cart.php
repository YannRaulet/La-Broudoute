<?php

Namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;


Class Cart
{
    private $session;
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager,SessionInterface $session)
    {
        $this->session = $session;
        $this->entityManager = $entityManager;
    }

    public function get()                           // Return the cart
    {
        return $this->session->get('cart');
    }

	public function add($id)
    {
        $cart = $this->session->get('cart', []);    // cart : name of the session

        if (!empty($cart[$id])) {
            $cart[$id]++;
        } else {
            $cart[$id] = 1;
        }

        $this->session->set('cart', $cart);
    }

    public function decrease($id)
    {
        $cart = $this->session->get('cart', []);

        if ($cart[$id] >1) {
            $cart[$id]--;
        } else {
            unset($cart[$id]);
        }

        return $this->session->set('cart', $cart);
    }  

    public function remove()
    {
        return $this->session->remove('cart');
    }

    public function delete($id)
    {
        $cart = $this->session->get('cart', []);

        unset($cart[$id]);

        return $this->session->set('cart', $cart);
    }

    public function getFull()                       // Enable get the whole cart
    {                     
        $cartComplete = [];

        if ($this->get()) {
            foreach ($this->get() as $id => $quantity) {
                $product_objet = $this->entityManager->getRepository(Product::class)->findOneById($id);
                
            if (!$product_objet) {                  // If we try cart/add/number that's not an id (ex 55555558)
                    $this->delete($id);             // delete the id, continue the code and return to mon-panier
                    continue;
                }
                
                $cartComplete[] = [
                    'product' => $product_objet,
                    'quantity' => $quantity
                ];
            }
        }
        return $cartComplete;
    }
}