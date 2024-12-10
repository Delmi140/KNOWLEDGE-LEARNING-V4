<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Service\MailerService;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use DateTime;
use Symfony\Component\Finder\Exception\AccessDeniedException;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher,
     EntityManagerInterface $entityManager,
     MailerService $mailerService, TokenGeneratorInterface $tokenGeneratorInterface): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {

            // TOKEN

            $tokenRegistration = $tokenGeneratorInterface->generateToken();

            // USER
            /** @var string $plainPassword */
            $plainPassword = $form->get('plainPassword')->getData();

            // encode the plain password
            $user->setPassword($userPasswordHasher->hashPassword($user, $plainPassword));

            // USER TOKEN
            $user->setTokenRegistration($tokenRegistration);

            $entityManager->persist($user);
            $entityManager->flush();

            // MAILER SEND

            $mailerService->send(
                $user->getEmail(),
                'Confirmation du compte utilisateur',
                'registration_confirmation.html.twig',
                [
                    'user' => $user,
                    'token' => $tokenRegistration,
                    'lifeTimeToken' => $user->getTokenRegistrationLifeTime()->format('d/m/y à H\hi')

                ]
            );

            
            $this->addFlash('success', 'Votre compte à bien été créé, veuillez vérifier vos e-mail pour l\'activer');

            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form,
        ]);
    }

    #[Route('/verify/{token}/{id<\d+>}', name: 'account_verify', methods:['GET'])]
    public function verify (string $token, User $user, EntityManagerInterface $em): Response{

        if($user->getTokenRegistration() !== $token) {
            throw new AccessDeniedException();
        }

        if($user->getTokenRegistration() === null){
            throw new AccessDeniedException();
        }

        if(new DateTime('now') > $user->getTokenRegistrationLifeTime()){
            throw new AccessDeniedException();
        }

        $user->setVerified(true);
        $user->setTokenRegistration(null);
        $em->flush();

        $this->addFlash('success', 'Votre compte a bien été activé, vous pouvez maintenant vous connecter');

        return $this->redirectToRoute('app_login');


    }

}
