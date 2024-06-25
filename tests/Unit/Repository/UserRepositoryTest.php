<?php

namespace App\Tests\Repository;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends WebTestCase
{
    public function testFind(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->find(1);
        $this->assertSame("user", $user->getUsername());
    }

    public function testFindOneByEmail(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail("user@mail.com");
        $this->assertSame("user", $user->getUsername());
    }

    public function testFindAll(){
        $userRepository = static::getContainer()->get(UserRepository::class);
        $users = $userRepository->findAll();
        $this->assertNotEmpty($users);
    }

    public function testUpgradePassword(){
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail("user@mail.com");
        $user->setPassword($passwordHasher->hashPassword($user, "changed"));
        $this->assertTrue($passwordHasher->isPasswordValid($user, "changed"), $user->getPassword());
    }
}