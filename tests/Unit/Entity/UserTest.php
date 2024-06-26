<?php

namespace App\Tests\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class UserTest extends KernelTestCase
{
    public function getEntity() : User
    {
        return (new User())->setUsername("test_user")
        ->setEmail("test_user@mail.com")
        ->setRoles(["ROLE_USER"]);
    }

    public function testUserIsValid(): void
    {
        self::bootKernel();
        $container = static::getContainer();

        $user = $this->getEntity();

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(0, $errors);
    }

    public function testInvalidEmail(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        $user->setEmail("test_userMail");

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(1, $errors);
    }

    public function testInvalidUsername(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        $user = $this->getEntity();
        $user->setUsername("");

        $errors = $container->get('validator')->validate($user);

        $this->assertCount(1, $errors);
    }

    // test setters and getters
    public function testSetUsername(): void
    {
        $user = $this->getEntity();
        $user->setUsername("user_changed");
        $this->assertSame("user_changed", $user->getUsername());
    }

    public function testSetEmail(): void
    {
        $user = $this->getEntity();
        $user->setEmail("changed@mail.com");
        $this->assertSame("changed@mail.com", $user->getEmail());
    }

    public function testSetRoles(): void
    {
        $user = $this->getEntity();
        $user->setRoles(["ROLE_TEST"]);
        $this->assertSame(["ROLE_TEST", "ROLE_USER"], $user->getRoles());
    }

    public function testSetPassword(): void
    {
        $user = $this->getEntity();
        $user->setPassword("newPassword");
        $this->assertSame("newPassword", $user->getPassword());
    }

    // test adding and removing a task
    public function testAddTask(): void
    {
        $task = new Task();
        $task->setTitle("a title")
        ->setContent("a content");

        $user = $this->getEntity();
        $user->addTask($task);

        $this->assertContains($task, $user->getTasks());
    }

    public function testRemoveTask(): void
    {
        $task = new Task();
        $task->setTitle("a title")
        ->setContent("a content");

        $user = $this->getEntity();
        $user->addTask($task);
        $user->removeTask($task);
        $this->assertNotContains($task, $user->getTasks());
    }
}