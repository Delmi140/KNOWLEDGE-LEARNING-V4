<?php

namespace App\Controller;

use App\Entity\CategoryShop;
use App\Entity\Cursus;
use App\Entity\Lessons;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Doctrine\ORM\EntityManagerInterface;

use App\Repository\LessonsRepository;



class ProductController extends AbstractController
{

    public function __construct(private readonly EntityManagerInterface $em){}

    // Route setting the URL "/product" to display the products page
    #[Route('/product', name: 'app_product')]
    public function index(): Response
    {

        
        // Retrieval of different product categories via “CategoryShop” entities
        $categorys = $this->em->getRepository(CategoryShop::class)->findByIds([1]);
        $categoryInfos = $this->em->getRepository(CategoryShop::class)->findByIds([2]);
        $categoryJards = $this->em->getRepository(CategoryShop::class)->findByIds([3]);
        $categoryCuis = $this->em->getRepository(CategoryShop::class)->findByIds([4]);

        // Retrieval of courses associated with each category
        $cursusCs = $this->em->getRepository(Cursus::class)->findByIds([1]);
        $cursusCspianos = $this->em->getRepository(Cursus::class)->findByIds([2]);
        $cursusCsInfos = $this->em->getRepository(Cursus::class)->findByIds([3]);
        $cursusJards = $this->em->getRepository(Cursus::class)->findByIds([4]);
        $cursusCuiIs = $this->em->getRepository(Cursus::class)->findByIds([5]);
        $cursusCuiIAs = $this->em->getRepository(Cursus::class)->findByIds([6]);

        // Recovery of lessons associated with each course
        $lessonsCs =  $this->em->getRepository(Lessons::class)->findByIds([1]);
        $lessonsCsguitatres =  $this->em->getRepository(Lessons::class)->findByIds([2]);
        $lessonsCspianosIs =  $this->em->getRepository(Lessons::class)->findByIds([3]);
        $lessonsCspianosGs =  $this->em->getRepository(Lessons::class)->findByIds([4]);
        $lessonsCsinfosIs =  $this->em->getRepository(Lessons::class)->findByIds([5]);
        $lessonsCsinfosDs =  $this->em->getRepository(Lessons::class)->findByIds([6]);
        $lessonsJardOs =  $this->em->getRepository(Lessons::class)->findByIds([7]);
        $lessonsJardLs =  $this->em->getRepository(Lessons::class)->findByIds([8]);
        $lessonsCuiMs =  $this->em->getRepository(Lessons::class)->findByIds([9]);
        $lessonsCuiSs =  $this->em->getRepository(Lessons::class)->findByIds([10]);
        $lessonsCuiOs =  $this->em->getRepository(Lessons::class)->findByIds([11]);
        $lessonsCuiHs =  $this->em->getRepository(Lessons::class)->findByIds([12]);

        // Rendering the view with data from categories, courses and lessons
        return $this->render('product/index.html.twig',[
            
            
            'categorys' =>  $categorys,
            'categoryInfos'=> $categoryInfos,
            'categoryJards'=> $categoryJards,
            'categoryCuis' => $categoryCuis,

            
            'cursusCs' => $cursusCs,
            'cursusCspianos' => $cursusCspianos,
            'cursusCsInfos' => $cursusCsInfos,
            'cursusJards'=> $cursusJards,
            'cursusCuiIs' => $cursusCuiIs,
            'cursusCuiIAs'=> $cursusCuiIAs,


            
            'lessonsCs' => $lessonsCs,
            'lessonsCsguitatres' => $lessonsCsguitatres,
            'lessonsCspianosIs' => $lessonsCspianosIs,
            'lessonsCspianosGs' => $lessonsCspianosGs,
            'lessonsCsinfosIs' => $lessonsCsinfosIs,
            'lessonsCsinfosDs' => $lessonsCsinfosDs,
            'lessonsJardOs'=> $lessonsJardOs,
            'lessonsJardLs' => $lessonsJardLs,
            'lessonsCuiMs' => $lessonsCuiMs,
            'lessonsCuiSs' => $lessonsCuiSs,
            'lessonsCuiOs' => $lessonsCuiOs,
            'lessonsCuiHs' => $lessonsCuiHs,


            ]);
    }

    // Route to display product details via its ID
    #[Route('/product/{id<\d+>}', name: 'details_index')]
    public function details(int $id): Response
    {



        $product = $this->em->getRepository(Lessons::class)->find($id);


        


        return $this->render('product/details.html.twig',[
            
            'product' => $product
            ]);
    }

    // Route to validate a lesson by the user
    #[Route('/lesson/validate/{id}', name: 'lesson_validate')]
    public function validateLesson(int $id, LessonsRepository $lessonsRepository, EntityManagerInterface $entityManager,): Response
    {

        // Retrieve the logged in user
        $user = $this->getUser(); 

        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('L\'utilisateur actuel n\'est pas authentifié ou n\'est pas valide.');
        }

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour valider une leçon.');
        }

        
        $lesson = $lessonsRepository->find($id);

        if (!$lesson) {
            throw $this->createNotFoundException('Leçon introuvable.');
        }

        if ($user->getPurchasedLessonsValidation()->contains($lesson)) {
            $this->addFlash('warning', 'Cette leçon est déjà validée.');
            return $this->redirectToRoute('validation_page');
        }
        
        // Adding the validated lesson to the user's account
        $user->addPurchasedProductValidation($lesson); 
        $lesson->setValidated(true);

        // Checking if the curriculum linked to the lesson is fully validated
        $cursus = $lesson->getCursus();
        if ($cursus && $cursus->isFullyValidated()) {
            $user->addPurchasedProductValidation($cursus);
        }


        // Save modifications to the database
        $entityManager->persist($lesson);
        $entityManager->persist($user);
        $entityManager->flush();

        $this->addFlash('success', 'Leçon validée avec succès.');

        return $this->redirectToRoute('validation_page');
    }

}
