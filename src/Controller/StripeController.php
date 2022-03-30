<?php

namespace App\Controller;

use Stripe\Stripe;
use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class StripeController extends AbstractController
{
    /**
     * @Route("/commande/create-session/{reference}", name="stripe_create_session")
     */
    public function index(
        Cart $cart,
        EntityManagerInterface $manager,
        string $reference
        ): Response
    {
        $products_for_stripe = [];
        // Change the domain name in production
        //$YOUR_DOMAIN = 'http://127.0.0.1:8000';
        $YOUR_DOMAIN = 'https://oh9hlaqvaf.preview.infomaniak.website';


        $order = $manager->getRepository(Order::class)->findOneByReference($reference);

        if(!$order){
            return $this->redirectToRoute('order');
        } 

        foreach ($order->getOrderDetails()->getValues() as $product) {     
            // For each product in my cart
            $product_object = $manager->getRepository(Product::class)->findOneByName($product->getProduct());
            $products_for_stripe[] = [
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $product->getPrice(),
                    'product_data' => [
                        'name' => $product->getProduct(),
                        'images' => [$YOUR_DOMAIN."/uploads/".$product_object->getIllustration()],
                    ],
                ],
                'quantity' => $product->getQuantity(),
            ];
        }
        
        $products_for_stripe[] = [
            'price_data' => [
                'currency' => 'eur',
                'unit_amount' => $order->getCarrierPrice(),
                'product_data' => [
                    'name' => $order->getCarrierName(),
                    'images' => [$YOUR_DOMAIN],
                ],
            ],
            'quantity' => 1,
        ];

        Stripe::setApiKey('sk_test_51JNC9SEmVE2CPXTTG5n1Rh4kM63iRgDFbdeRAOmSHJlTQm3tMUvydc25AfQAbFlj1uhXslayNwk5U7XAUBfVfQRk00XorKRlOp');

        $checkout_session = Session::create([
            'customer_email' =>$this->getUser()->getEmail(),
            'payment_method_types' => ['card'],
            'line_items' => [
                # TODO: replace this with the `price` of the product you want to sell
                $products_for_stripe
            ],
            'mode' => 'payment',
             'success_url' => $YOUR_DOMAIN .'/commande/success',
            'cancel_url' => $YOUR_DOMAIN .'/commande/cancel',
        ]);
 
        $order->setStripeSessionUrl($checkout_session->url);
        $manager->flush();

        return $this->redirect($checkout_session->url);
    }
}
