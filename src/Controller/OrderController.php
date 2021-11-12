<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Form\OrderType;
use App\Entity\OrderDetails;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    /**
     * @Route("/commande", name="order")
     */
    public function index(Request $request, Cart $cart): Response
    {
        if (!$this->getUser()->getAddresses()->getValues()) {    // Get the Addresses() data of the User() entity
            return $this->redirectToRoute('account_address_add');
        }     

        $form = $this->createForm(OrderType::class, null, [     // Null because the form is not linked to a class
            'user' => $this->getUser()                          // Retrieve the logged in user
        ]);

        return $this->render('order/index.html.twig', [
            'form' => $form->createView(),
            'cart' => $cart->getFull()                          // Retrieves all the data of the Cart entity with the full function
        ]);
    }

    /**
     * @Route("/commande/recapitulatif", name="order_recap", methods={"POST"})
     */
    public function add(
        Request $request,
        EntityManagerInterface $manager,
        Cart $cart): Response
    {  
        $form = $this->createForm(OrderType::class, null, [     // Null because the form is not linked to a class
            'user' => $this->getUser()                          // Retrieve the logged in user
        ]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $date = new \DateTimeImmutable();
            $carrier = $form->get('carriers')->getData();       // 'carriers' from OrderType
            $delivery = $form->get('addresses')->getData();     // 'addresses' from OrderType
            $delivery_content = $delivery->getFirstname().' '.$delivery->getLastName();
            $delivery_content .= '<br>'.$delivery->getPhone();
            if($delivery->getCompany()) {
                $delivery_content .= '<br>'.$delivery->getCompany();
            }
            $delivery_content .= '<br>'.$delivery->getAddress();
            $delivery_content .= '<br>'.$delivery->getPostal().' '.$delivery->getCity();
            $delivery_content .= '<br>'.$delivery->getCountry();

            // Register my order : Order()
            $order = new Order();
            $reference = $date->format('dmY').'-'.uniqid();
            $order->setReference($reference);
            $order->setUser($this->getUser());
            $order->setCreatedAt($date);
            $order->setCarrierName($carrier->getName());
            $order->setCarrierPrice($carrier->getPrice());
            $order->setDelivery($delivery_content);
            $order->setIsPaid(0);

            $manager->persist($order);

            // Register my products : OrderDetails()
            // For each product in my cart
            foreach ($cart->getFull() as $product) {
                $orderDetails = new OrderDetails();
                $orderDetails->setMyOrder($order);
                $orderDetails->setProduct($product['product']->getName());
                $orderDetails->setQuantity($product['quantity']);
                $orderDetails->setPrice($product['product']->getPrice());
                $orderDetails->setTotal($product['product']->getPrice() * $product['quantity']);

                $manager->persist($orderDetails);
            }

            $manager->flush();

            return $this->render('order/add.html.twig', [
                'cart' => $cart->getFull(),                          // Retrieves all the data of the Cart entity with the full function
                'carrier' => $carrier,
                'delivery' => $delivery_content,
                'reference'=>$order->getReference()

            ]);
        }

        return $this->redirectToRoute('cart');
    }
}
