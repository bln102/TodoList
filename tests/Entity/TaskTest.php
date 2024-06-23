<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())->setTitle("task 1")
                ->setContent("content 1");
    }

    public function testIsValidTask()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();

        $errors = $container->get('validator')->validate($task);

        $this->assertCount(0, $errors);
    }

    // test setters and getters
    public function testSetTitle(): void
    {
        $task = $this->getEntity();
        $task->setTitle("new_title");
        $this->assertSame("new_title", $task->getTitle());
    }

    public function testSetContent(): void
    {
        $task = $this->getEntity();
        $task->setContent("new_content");
        $this->assertSame("new_content", $task->getContent());
    }

    public function testSetDone(): void
    {
        $task = $this->getEntity();
        $task->setDone(true);
        $this->assertSame(true, $task->getIsDone());
    }

    public function testToggle(): void
    {
        $task = $this->getEntity();
        $task->toggle(!$task->isDone());
        $this->assertSame(true, $task->getIsDone());
    }

    public function testSetUser()
    {
        $task = $this->getEntity();
        $user = new User();
        $user->setUsername("test_user")
        ->setEmail("test_user@mail.com")
        ->setRoles(["ROLE_USER"]);

        $task->setUser($user);
        $this->assertSame($user, $task->getUser());
    }

}
