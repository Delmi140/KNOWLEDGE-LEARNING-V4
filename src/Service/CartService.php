<?php
namespace App\Service;

use App\Entity\Cursus;
use App\Entity\Lessons;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class CartService {

    private RequestStack $requestStack;

    private EntityManagerInterface $em;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $em)
    {
        $this->requestStack = $requestStack;
        $this->em = $em;
    }


    public function addToCart(string $type,int $id) : void {

        $card = $this->requestStack->getSession()->get('cart', []);

        $key = "{$type}_{$id}";

        if(!empty($card[$key])) {
            $card[$key]++;
        }else{
        $card[$key] = 1;
        }

        $this->getSession()->set('cart' ,$card );

    }


    public function removeCartAll()
    {
        return $this->getSession()->remove('cart');

    }

    public function getTotal() : array
    {
        $cart = $this->getSession()->get('cart');
        $cartData = [];
        if($cart){
            foreach ( $cart as $key => $quantity){
                [$type, $id] = explode('_', $key);

                if ($type === 'cursus') {
                    $entity = $this->em->getRepository(Cursus::class)->find($id);
                } elseif ($type === 'lessons') {
                    $entity = $this->em->getRepository(Lessons::class)->find($id);
                } else {
                    continue; // Ignore les entrées inconnues
                }

                if ($entity) {
                    $cartData[] = [
                        'type' => $type,
                        'entity' => $entity,
                        'quantity' => $quantity,
                    ];
                }


            }
        }
        return $cartData;

    }


    public function removeToCart(string $type,int $id) 
    {
        $card = $this->requestStack->getSession()->get('cart', []);

        $key = "{$type}_{$id}";

        unset($card[$key]);
        return $this->getSession()->set('cart',$card );
    }

    
    private function getSession() : SessionInterface 
    {
        return $this->requestStack->getSession();
    }


    

    


   


}