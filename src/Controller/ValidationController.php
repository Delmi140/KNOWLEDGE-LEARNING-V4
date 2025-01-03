<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

use App\Repository\LessonsRepository;
use App\Repository\CursusRepository;


class ValidationController extends AbstractController
{
    #[Route('/validation', name: 'validation_page')]
    public function validationPage(LessonsRepository $lessonsRepository, CursusRepository $cursusRepository): Response
    {
        $user = $this->getUser();

        if (!$user) {
            throw $this->createAccessDeniedException('Vous devez être connecté pour accéder à cette page.');
        }

        if (!$user instanceof \App\Entity\User) {
            throw new \LogicException('L\'utilisateur actuel n\'est pas authentifié ou n\'est pas valide.');
        }


        $lessons = $user->getPurchasedLessonsValidation();
        $cursuses = $user->getPurchasedCursusesValidation()->filter(function ($cursus) {
        return $cursus->isFullyValidated();
        });



        return $this->render('validation/index.html.twig', [

            'lessons' => $lessons,
            'cursuses' => $cursuses,
            
        ]);
    }
}