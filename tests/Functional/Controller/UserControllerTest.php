<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UserControllerTest extends WebTestCase
{
    public function test_users_list()
    {
        $client = static::createClient();
        $client->request('GET', '/users');
        self::assertResponseRedirects('/error_role');
    }

    public function test_users_list_user()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $user = $userRepository->findOneByEmail('user@mail.com');
        $client->loginUser($user);
        $client->request('GET', '/users');
        self::assertResponseRedirects('/error_role');
    }

    public function test_users_list_admin()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $admin = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($admin);
        $client->request('GET', '/users');
        $this->assertResponseIsSuccessful();
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function test_create_user()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $admin = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($admin);
        $crawler = $client->request('GET', '/users/create');
        $buttonCrawlerNode = $crawler->selectButton('Soumettre');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['user[username]'] = 'testUser';
        $form['user[password]'] = 'password';
        $form['user[passwordConfirm]'] = 'password';
        $form['user[email]'] = 'testUser@mail.com';
        $form['user[roles]'] = "ROLE_USER";

        $client->submit($form);

        self::assertResponseRedirects('/users');
        $crawler = $client->followRedirect();
        $testUser = $userRepository->findOneByEmail('testUser@mail.com');
        
        $this->assertNotEmpty($testUser);
    }

    public function test_edit_user()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $admin = $userRepository->findOneByEmail('admin@mail.com');
        $client->loginUser($admin);
        $testUser = $userRepository->findOneByEmail('testUser@mail.com');
    
        $crawler = $client->request('GET', '/users/'.$testUser->getId().'/edit');
        $buttonCrawlerNode = $crawler->selectButton('Soumettre');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['user[username]'] = 'anotherName';
        $form['user[password]'] = 'password';
        $form['user[passwordConfirm]'] = 'password';
        $form['user[email]'] = 'testUser@mail.com';
        $form['user[roles]'] = "ROLE_USER";

        $client->submit($form);

        self::assertResponseRedirects('/users');
        $crawler = $client->followRedirect();
        $testUser = $userRepository->findOneByEmail('testUser@mail.com');
        
        $this->assertSame("anotherName", $testUser->getUsername());

    }

}