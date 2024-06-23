<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    public function load(ObjectManager $manager): void
    {
        $user = new User();
        $user->setUsername("user");
        $user->setEmail("user@mail.com");
        $user->setRoles(["ROLE_USER"]);
        $user->setPassword($this->passwordHasher->hashPassword($user, 'password'));
        $manager->persist($user);

        $admin = new User();
        $admin->setUsername("admin");
        $admin->setEmail("admin@mail.com");
        $admin->setRoles(["ROLE_ADMIN"]);
        $admin->setPassword($this->passwordHasher->hashPassword($admin, 'password'));
        $manager->persist($admin);

        $task1 = new Task();
        $task1->setTitle("task 1");
        $task1->setContent("content 1");
        $task1->setUser($user);
        $manager->persist($task1);

        $task2 = new Task();
        $task2->setTitle("task 2");
        $task2->setContent("content 2");
        $task2->setUser($admin);
        $manager->persist($task2);

        $task3 = new Task();
        $task3->setTitle("task 3");
        $task3->setContent("content 3");
        $task3->setUser($admin);
        $manager->persist($task3);

        $task4 = new Task();
        $task4->setTitle("task 4");
        $task4->setContent("content 4");
        $task4->setUser($admin);
        $manager->persist($task4);

        $task5 = new Task();
        $task5->setTitle("task 5");
        $task5->setContent("content 5");
        $task5->setUser($admin);
        $manager->persist($task5);

        $manager->flush();
    }
}
