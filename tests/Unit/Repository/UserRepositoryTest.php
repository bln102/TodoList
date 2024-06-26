<?php

namespace App\Tests\Repository;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserRepositoryTest extends WebTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
    }

    public function testFind(){
        $user = $this->entityManager
        ->getRepository(User::class)->findOneBy(
            ['username' => 'user']
        );
        $user = $this->entityManager
        ->getRepository(User::class)->find($user->getId());
        $this->assertSame("user", $user->getUsername());
    }

    public function testFindOneByEmail(){
        $user = $this->entityManager
        ->getRepository(User::class)->findOneByEmail("user@mail.com");
        $this->assertSame("user", $user->getUsername());
    }

    public function testFindAll(){
        $users = $this->entityManager
        ->getRepository(User::class)->findAll();
        $this->assertNotEmpty($users);
    }

    public function testUpgradePassword(){
        $passwordHasher = static::getContainer()->get(UserPasswordHasherInterface::class);
        $userRepository = static::getContainer()->get(UserRepository::class);
        $user = $userRepository->findOneByEmail("user@mail.com");
        $newHashedPassword = $passwordHasher->hashPassword($user, "changed");
        $userRepository->upgradePassword($user, $newHashedPassword);
        $user = $userRepository->findOneByEmail("user@mail.com");
        $this->assertTrue($passwordHasher->isPasswordValid($user, "changed"), $user->getPassword());
    }
}