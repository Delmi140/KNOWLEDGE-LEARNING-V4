<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;
use App\Repository\UserRepository;
use App\Entity\User;
use Symfony\Component\Routing\RouterInterface;
use App\Service\CartService;
use Stripe\Checkout\Session;
use Doctrine\ORM\EntityManagerInterface;
use App\Controller\PaymentController;

use PHPUnit\Framework\TestCase;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use App\Entity\Cursus;
use App\Entity\Lessons;
use DateTime;

class RegistrationControllerTest extends WebTestCase

{
    


    public function testRegister(): void
    {
    $client = static::createClient();
    $crawler = $client->request('GET', '/register');

    $this->assertResponseIsSuccessful();
    $this->assertSelectorTextContains('h1', "S'inscrire");

    $this->assertGreaterThan(0, $crawler->filter('form')->count(), 'Le formulaire n’a pas été trouvé sur la page.');

    $form = $crawler->selectButton("S'inscrire")->form([
        'registration_form[email]' => 'testuser' . rand(1000, 9999) . '@example.com',  // Utilisation d'un email unique
        'registration_form[plainPassword]' => 'password123',
        'registration_form[name]' => 'Test User',
        'registration_form[delivery_address]' => '123 Test Street',
    ]);
    $client->submit($form);

    $response = $client->getResponse();
    echo $response->getContent(); // Pour debug

    $this->assertResponseRedirects('app_login', 'La redirection après inscription a échoué.');

    $client->followRedirect();
    $this->assertSelectorTextContains('.flash-success', 'Votre compte à bien été créé', 'Le message flash est absent ou incorrect.');
    }


    public function testVerify(): void
    {
    $client = static::createClient();
    $userRepository = static::getContainer()->get(UserRepository::class);

    // Créez un utilisateur et insérez-le en base de données
    $user = new User();
    $user->setEmail( 'testuser' . rand(1000, 9999) . '@example.com',);
    $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
    $user->setTokenRegistration('test_token');
    $user->setVerified(false);
    $user->setName('Test User Verify');
    $user->setDeliveryAddress('123 Test Street');

    $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    $entityManager->persist($user);
    $entityManager->flush();

    // Effectuer la vérification
    $client->request('GET', '/verify/' . $user->getTokenRegistration() . '/' . $user->getId());

    // Recharger l'utilisateur et vérifier qu'il a bien été validé
    $entityManager->refresh($user);
    $this->assertTrue($user->isVerified());

    

    // Vérifiez la redirection et le message flash
    $this->assertResponseRedirects('/login');
    $client->followRedirect();
    
    }


    public function testLogin(): void
    {

    $client = static::createClient();
    // Créer l'utilisateur si nécessaire (assurez-vous que l'utilisateur existe dans la base)
    $entityManager = static::getContainer()->get('doctrine.orm.entity_manager');
    $user = $entityManager->getRepository(User::class)->findOneByEmail('testuser' . rand(1000, 9999) . '@example.com');
    if (!$user) {
        $user = new User();
        $user->setEmail('testuser' . rand(1000, 9999) . '@example.com');
        $user->setPassword(password_hash('password123', PASSWORD_BCRYPT));
        $user->setName('Test User Login');
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
        'email' => 'testuser' . rand(1000, 9999) . '@example.com',
        'password' => 'password123',
    ]);
    $client->submit($form);

    // Vérifier la redirection après connexion réussie
    $this->assertResponseRedirects('/login');
    
    }


    public function testCreateUser(): void
    {
        $user = new User();
        $user->setEmail('test@example.com');
        $user->setName('John Doe');
        $user->setDeliveryAddress('123 Main St');
        $user->setPassword('password');
        $user->setRoles(['ROLE_USER']);
        $user->setVerified(true);

        // Vérifier que les valeurs sont correctement définies
        $this->assertEquals('test@example.com', $user->getEmail());
        $this->assertEquals('John Doe', $user->getName());
        $this->assertEquals('123 Main St', $user->getDeliveryAddress());
        $this->assertEquals('password', $user->getPassword());
        $this->assertTrue(in_array('ROLE_USER', $user->getRoles()));
        $this->assertTrue($user->isVerified());
    }

    public function testRolesAssignment(): void
    {
    $user = new User();
    $user->setRoles(['ROLE_ADMIN', 'ROLE_USER']);
    
    $roles = $user->getRoles();
    
    // Vérifier que l'utilisateur a les rôles attendus
    $this->assertTrue(in_array('ROLE_ADMIN', $roles));
    $this->assertTrue(in_array('ROLE_USER', $roles));
    }

    public function testAddPurchasedProduct(): void
    {
    $user = new User();
    
    // Supposons que Cursus et Lessons sont des objets valides.
    $cursus = $this->createMock(Cursus::class);
    $lesson = $this->createMock(Lessons::class);
    
    $user->addPurchasedProduct($cursus);
    $user->addPurchasedProduct($lesson);

    // Vérifiez que l'utilisateur a bien ajouté les produits dans les collections
    $this->assertCount(1, $user->getPurchasedCursuses());
    $this->assertCount(1, $user->getPurchasedLessons());
    }

    public function testTokenRegistrationLifeTime(): void
    {
    $user = new User();
    
    $tokenLifeTime = $user->getTokenRegistrationLifeTime();
    
    $now = new DateTime();
    $this->assertGreaterThan($now, $tokenLifeTime);
    }

    public function testGetUserIdentifier(): void
    {
    $user = new User();
    $user->setEmail('test@example.com');

    $this->assertEquals('test@example.com', $user->getUserIdentifier());
    }







    

}
