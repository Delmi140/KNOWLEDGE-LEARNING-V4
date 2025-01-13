<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;

class RegistrationControllerTest extends WebTestCase
{
    public function testRegister(): void
{
    $client = static::createClient();
    $crawler = $client->request('GET', '/register');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', "S'inscrire");

    // Vérifiez que le formulaire existe
    $this->assertGreaterThan(0, $crawler->filter('form')->count(), 'Le formulaire n’a pas été trouvé sur la page.');

    // Soumission du formulaire d'inscription
    $form = $crawler->selectButton('Register')->form([
        'registration_form[email]' => 'testuser@example.com',
        'registration_form[plainPassword]' => 'password123',
        'registration_form[name]' => 'Test User',
        'registration_form[delivery_address]' => '123 Test Street',
    ]);
    $client->submit($form);

    // Vérification de la redirection
    $this->assertResponseRedirects('/login');

    // Vérification du flash message
    $client->followRedirect();
    $this->assertSelectorTextContains('.flash-success', 'Votre compte à bien été créé');
}

public function testVerify(): void
{
    $user = new User();
    $user->setEmail('testuser@example.com');
    $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
    $user->setTokenRegistration('test_token');
    $user->setVerified(false);
    $user->setName('Test User'); // Ajout de la valeur pour le champ "name"
    $user->setDeliveryAddress('123 Test Street');

    $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    $entityManager->persist($user);
    $entityManager->flush();

    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);

    $user = $userRepository->findOneBy(['email' => 'testuser@example.com']);
    $this->assertNotNull($user, 'L’utilisateur n’a pas été trouvé en base.');

    $client->request('GET', '/verify/' . $user->getTokenRegistration() . '/' . $user->getId());

    // Recharger l'utilisateur depuis la base pour vérifier les changements
    $entityManager->refresh($user);
    $this->assertTrue($user->isVerified());

    // Vérifiez la redirection et le flash message
    $this->assertResponseRedirects('/login');
    $client->followRedirect();
    $this->assertSelectorTextContains('.flash-success', 'Votre compte a bien été activé');
}

public function testLogin(): void
{

    $client = static::createClient();
    // Créer l'utilisateur si nécessaire (assurez-vous que l'utilisateur existe dans la base)
    $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    $user = $entityManager->getRepository(User::class)->findOneByEmail('testuser@example.com');
    if (!$user) {
        $user = new User();
        $user->setEmail('testuser@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setName('Test User');
        $user->setDeliveryAddress('123 Test Street');
        $entityManager->persist($user);
        $entityManager->flush();
    }

    // Effectuer la requête de connexion
    
    $crawler = $client->request('GET', '/login');
    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', 'Connexion');

    // Soumettre le formulaire de connexion
    $form = $crawler->selectButton('Se Connecter')->form([
        'email' => 'testuser@example.com',
        'password' => 'password123',
    ]);
    $client->submit($form);

    // Vérifier la redirection après connexion réussie
    $this->assertResponseRedirects('/login');
    
}

}
