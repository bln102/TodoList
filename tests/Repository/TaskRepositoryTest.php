<?php

namespace App\Tests\Repository;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class TaskRepositoryTest extends WebTestCase
{
    public function testFind(){
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $task = $taskRepository->find(1);
        $this->assertSame("task 1", $task->getTitle());
    }

    public function testFindAll(){
        $taskRepository = static::getContainer()->get(TaskRepository::class);
        $tasks = $taskRepository->findAll();
        $this->assertCount(5, $tasks);
    }
}