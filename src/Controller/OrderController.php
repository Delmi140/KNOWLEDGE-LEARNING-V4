<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\CartService;

class OrderController extends AbstractController
{
    #[Route('/order/create', name: 'order_index')]
    public function index(CartService $cartService): Response
    {

        if(!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }


        return $this->render('order/index.html.twig', [
            'recapCart' => $cartService->getTotal(),
        ]);
    }
}
