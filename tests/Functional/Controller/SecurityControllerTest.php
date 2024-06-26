<?php

namespace App\Tests\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class SecurityControllerTest extends WebTestCase
{
    private $client;
    protected function setUp(): void
    {
        
    }

    protected function tearDown(): void
    {
        parent::tearDown();

    }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(0, $crawler->filter('input[name="_username"]')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="_password"]')->count());

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['_username'] = 'user';
        $form['_password'] = 'password';

        // submit the Form object
        $client->submit($form);

        self::assertResponseRedirects('/');
        $client->followRedirect();

        // $this->assertTrue($client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_USER'));
    }

    public function testInvalidLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        $this->assertGreaterThan(0, $crawler->filter('input[name="_username"]')->count());
        $this->assertGreaterThan(0, $crawler->filter('input[name="_password"]')->count());

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Se connecter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['_username'] = 'invalid';
        $form['_password'] = 'password';

        // submit the Form object
        $client->submit($form);

        self::assertResponseRedirects('/login');
        $this->assertFalse($client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_USER'));
        
    }

    public function testLogout()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $user = $userRepository->findOneByEmail('user@mail.com');
        $client->loginUser($user);
        $crawler = $client->request('GET', '/logout');
        $this->assertFalse($client->getContainer()->get('security.authorization_checker')->isGranted('ROLE_USER'));
        }

        public function test_login_check()
        {
            $client = static::createClient();
            $client->request('GET', '/login_check');
            self::assertResponseRedirects('/page404');
        }
}
