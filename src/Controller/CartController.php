<?php

namespace App\Controller;



use App\Service\CartService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CartController extends AbstractController
{
    // Route to display the contents of the cart.
    #[Route('/cart', name: 'cart_index')]
    public function index(CartService $cartService): Response
    {

    
        // Retrieves the cart total via the CartService service and displays the associated view.
        return $this->render('cart/index.html.twig',[
            'cart' => $cartService->getTotal()
            ]);
    }


    // Route to add a product to the cart.
    #[Route('/cart/add/{type}/{id<\d+>}', name: 'cart_add')]
    public function addToRoute(CartService $cartService,string $type, int $id): Response
    {
        // Add an item to the cart using the CartService service.
        $cartService->addToCart($type,$id);
        
        return $this->redirectToRoute('cart_index');
    }

    // Route to remove a specific product from the cart.
    #[Route('/product/remove/{type}/{id<\d+>}', name: 'cart_remove')]
    public function removeToCart(CartService $cartService,string $type, int $id ): Response
    {
        
        $cartService->removeToCart($type,$id);
      

        return $this->redirectToRoute('cart_index');
    }


    // Route to completely empty the basket.
    #[Route('/cart/removeAll', name: 'cart_removeAll')]
    public function removeAll(CartService $cartService,): Response
    {
        $cartService->removeCartAll();

        return $this->redirectToRoute('app_product');
    }
    

}