<?php

namespace App\Controller;

use App\Entity\CategoryShop;
use App\Entity\Cursus;
use App\Entity\Lessons;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

class ProductController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}


    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {

        $categorys = $this->em->getRepository(CategoryShop::class)->findByIds([1]);
        $cursusCs = $this->em->getRepository(Cursus::class)->findAll();
        $lessonsCs =  $this->em->getRepository(Lessons::class)->findAll();

        return $this->render('product/index.html.twig',[
            
            'categorys' =>  $categorys,
            'cursusCs' => $cursusCs,
            'lessonsCs' => $lessonsCs

            ]);
    }
}
