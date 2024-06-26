<?php
// tests/Service/UserFixturesTest.php
namespace App\Tests\DataFixtures;

use App\DataFixtures\AppFixtures;
use App\Entity\User;
use Doctrine\Common\DataFixtures\Purger\ORMPurger;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class AppFixturesTest extends KernelTestCase
{
    private EntityManagerInterface $entityManager;

    protected function setUp(): void
    {
        $kernel = self::bootKernel();

        $this->entityManager = $kernel->getContainer()
            ->get('doctrine')
            ->getManager();

        // Purge the database before loading fixtures
        $purger = new ORMPurger($this->entityManager);
        $purger->purge();

        // Load the fixtures
        $fixture = static::getContainer()->get(AppFixtures::class);
        $fixture->load($this->entityManager);
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        $this->entityManager->close();
        // $this->entityManager = null; // Avoid memory leaks
    }

    public function testUserFixture(): void
    {
        $users = $this->entityManager
            ->getRepository(User::class)
            ->findAll();

        $this->assertNotEmpty($users);
    }
}
