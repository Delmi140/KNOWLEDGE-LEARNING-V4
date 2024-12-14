<?php

namespace App\Controller;

use App\Entity\Cursus;
use App\Entity\SweatShirts;
use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{

    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {

    
        
        return $this->render('cart/index.html.twig',[
            'cart' => $cartService->getTotal()
            ]);
    }



    #[Route('/cart/add/{id<\d+>}', name: 'cart_add')]
    public function addToRoute(CartService $cartService, int $id): Response
    {

        $cartService->addToCart($id);
        
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/cart/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService,): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('app_product');
    }
    

}