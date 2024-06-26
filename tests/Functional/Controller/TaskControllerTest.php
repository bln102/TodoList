<?php

namespace App\Tests\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TaskControllerTest extends WebTestCase
{
    public function testTaskListNotLoggedIn(): void
    {
        $client = static::createClient();
        $client->request('GET', '/tasks');
        self::assertResponseRedirects('/login');
    }

    public function test_tasks_list(){
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $crawler = $client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
        $this->assertStringContainsString('Créer une tâche', $crawler->filter('.btn.btn-info.pull-right')->text());
    }

    public function test_task_create(){
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);

        // retrieve the test user
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

        // test e.g. the profile page
        $crawler = $client->request('GET', '/tasks/create');
        // select the button
        $buttonCrawlerNode = $crawler->selectButton('Ajouter');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object
        $form['task[title]'] = 'test task';
        $form['task[content]'] = 'test content';

        $client->submit($form);

        self::assertResponseRedirects('/tasks');
        $crawler = $client->followRedirect();
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );
        
        $this->assertNotEmpty($task);
    }

    public function test_task_edit()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);

    $taskRepository = static::getContainer()->get(TaskRepository::class);
    $task = $taskRepository->findOneBy(
        ['title' => 'test task']
    );

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/edit');
        $buttonCrawlerNode = $crawler->selectButton('Modifier');


        $form = $buttonCrawlerNode->form();
        $form['task[title]'] = 'test task';
        $form['task[content]'] = 'content changed';

        $client->submit($form);

        self::assertResponseRedirects('/tasks');
        // $crawler = $client->followRedirect();
        // $this->assertStringContainsString('content changed', $crawler->filter('.caption p')->text());
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );
        $this->assertSame("content changed", $task->getContent());
    }

    public function test_task_toggle()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/toggle');
        self::assertResponseRedirects('/tasks');
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );
        $this->assertTrue($task->getIsDone());
    }

    public function test_task_delete()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/delete');
        self::assertResponseRedirects('/tasks');
        $task = $taskRepository->findOneBy(
            ['title' => 'test task']
        );
        $this->assertEmpty($task);
    }

    public function test_task_delete_unauthorized()
    {
        $client = static::createClient();
        $userRepository = static::getContainer()->get(UserRepository::class);
        $testUser = $userRepository->findOneByEmail('user@mail.com');

        // simulate $testUser being logged in
        $client->loginUser($testUser);
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->findOneBy(
            ['title' => 'task 2']
        );

        $crawler = $client->request('GET', '/tasks/'.$task->getId().'/delete');
        self::assertResponseRedirects('/tasks');
        $task = $taskRepository->findOneBy(
            ['title' => 'task 2']
        );
        $this->assertNotEmpty($task);
    }
}
