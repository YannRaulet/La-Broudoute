<?php

namespace App\Controller;

use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AccountOrderController extends AbstractController
{
    /**
     * @Route("/compte/mes-commandes", name="account_order")
     */
    public function index(
        EntityManagerInterface $manager
    ): Response
    {
        $orders = $manager->getRepository(Order::class)->findSuccessOrders($this->getUser());

        return $this->render('account/order.html.twig', [
            'orders' => $orders
        ]);
    }

    /**
     * @Route("/compte/ma-commande/{id}", name="account_order_show")
     */
    public function show(
        EntityManagerInterface $manager,
        int $id
    ): Response
    {
        $order = $manager->getRepository(Order::class)->findOneById($id);

        if(!$order || $order->getUser() != $this->getUser() ) {
            return $this->redirectToRoute('account_order');
        }

        return $this->render('account/order_show.html.twig', [
            'order' => $order
        ]);
    }
}
