<?php

namespace App\Controller;



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



    #[Route('/cart/add/{type}/{id<\d+>}', name: 'cart_add')]
    public function addToRoute(CartService $cartService,string $type, int $id): Response
    {

        $cartService->addToCart($type,$id);
        
        return $this->redirectToRoute('cart_index');
    }


    #[Route('/product/remove/{type}/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService,string $type, int $id ): Response
    {
        $cartService->removeToCart($type,$id);
      

        return $this->redirectToRoute('cart_index');
    }



    #[Route('/cart/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService,): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('app_product');
    }
    

}