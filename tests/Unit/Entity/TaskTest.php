<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use DateTimeImmutable;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class TaskTest extends KernelTestCase
{
    public function getEntity(): Task
    {
        return (new Task())->setTitle("task 1")
                ->setContent("content 1");
    }

    public function test_IsValid_Task()
    {
        self::bootKernel();
        $container = static::getContainer();

        $task = $this->getEntity();

        $errors = $container->get('validator')->validate($task);

        $this->assertCount(0, $errors);
    }

    // test setters and getters
    public function test_Title(): void
    {
        $task = $this->getEntity();
        $task->setTitle("new_title");
        $this->assertSame("new_title", $task->getTitle());
    }

    public function test_content(): void
    {
        $task = $this->getEntity();
        $task->setContent("new_content");
        $this->assertSame("new_content", $task->getContent());
    }

    public function test_isDone(): void
    {
        $task = $this->getEntity();
        $task->setDone(true);
        $this->assertSame(true, $task->getIsDone());
    }

    public function test_CreatedAt(): void
    {
        $task = $this->getEntity();
        $datetime = new DateTimeImmutable();
        $task->setCreatedAt($datetime);
        $this->assertSame($datetime, $task->getCreatedAt());
    }

    public function test_toggle(): void
    {
        $task = $this->getEntity();
        $task->toggle(!$task->isDone());
        $this->assertSame(true, $task->getIsDone());
    }

    public function test_user()
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
