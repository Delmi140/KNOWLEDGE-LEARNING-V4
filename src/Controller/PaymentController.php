<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\CartService;
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Doctrine\ORM\EntityManagerInterface;



class PaymentController extends AbstractController{


    private UrlGeneratorInterface $generator;

    public function __construct(UrlGeneratorInterface $generator)
    {
        
        $this->generator = $generator;
    }

    #[Route('/order/create-session-stripe', name:'payment_stripe') ]
    public function stripeChectout(CartService $cartService): RedirectResponse 
    {
        
        $productStripe = [];

        $recapCart = $cartService->getTotal() ;

        foreach($recapCart as $item){
            $productStripe[] =[
                'price_data' => [
                    'currency' => 'eur',
                    'unit_amount' => $item['entity']->getPrice(),
                    'product_data' => [
                        'name' => $item['entity']->getName(),
                        ]
                    ],
                    'quantity' => $item['quantity'],
                ];
        }

        Stripe::setApiKey('sk_test_51QWiCIFLpGQLQVezpTGLMvBpPCtU8pYAHAIT7HA6MPhpi0wmircH0YhhTcyCQ1Nae7OoavmpmlR2IDRJG01DiMvl00gr5RvI94');
        $checkout_session = Session::create([
            
            'payment_method_types' => ['card'],
            'line_items' => [[
                $productStripe
        ]],
        'mode' => 'payment',
        'success_url' => $this->generator->generate('payment_success', [],UrlGeneratorInterface::ABSOLUTE_URL),
        'cancel_url' => $this->generator->generate('payment_error',[],UrlGeneratorInterface::ABSOLUTE_URL),
        ]);

        return new RedirectResponse($checkout_session->url);
        
   
    
    }

    #[Route('/order/success/', name:'payment_success')]
    public function stripeSuccess(CartService $cartService): Response {
        return $this->render('order/success.html.twig');

    }

    #[Route('/order/error/', name:'payment_error')]
    public function stripeError(CartService $cartService): Response {
        return $this->render('order/error.html.twig');

    }


}