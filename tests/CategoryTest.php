<?php

namespace App\Tests;

use App\DataFixtures\CategoryTestFixtures;
use App\Entity\Category;
use Doctrine\ORM\EntityManager;
use Liip\TestFixturesBundle\Services\DatabaseToolCollection;
use Liip\TestFixturesBundle\Services\DatabaseTools\AbstractDatabaseTool;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class CategoryTest extends WebTestCase
{
    /** @var AbstractDatabaseTool */
    protected $databaseTool;
    /** @var EntityManager */
    protected $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        self::bootKernel();

        $this->databaseTool = self::$container->get(DatabaseToolCollection::class)->get();
        $this->databaseTool->loadFixtures([
            CategoryTestFixtures::class,
        ]);
        $this->entityManager = self::$container->get('doctrine.orm.entity_manager');
    }

    protected function tearDown(): void
    {
        parent::tearDown();
        $this->entityManager->close();
        $this->entityManager = null;
    }

    public function testAncestors(): void
    {
        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");

        $this->assertEmpty($root->getAncestors());
        $this->assertEquals(1, sizeof($child->getAncestors()));
        $this->assertEquals(2, sizeof($smallChild->getAncestors()));
        $this->assertTrue($child->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($child));
        $this->assertFalse($smallChild->getAncestors()->contains($otherChild));
    }

    public function testAncestorsAfterRemove(): void
    {
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $this->entityManager->remove($otherChild);
        $this->entityManager->flush();

        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");

        $this->assertEmpty($root->getAncestors());
        $this->assertEquals(1, sizeof($child->getAncestors()));
        $this->assertEquals(2, sizeof($smallChild->getAncestors()));
        $this->assertTrue($child->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($child));
        $this->assertFalse($smallChild->getAncestors()->contains($otherChild));
    }

    public function testAncestorsAfterMovingCategory(): void
    {
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $otherChild->setParent($child);
        $this->entityManager->flush();

        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");
        $smallOtherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 3-3");

        $this->assertEmpty($root->getAncestors());
        $this->assertEquals(1, sizeof($child->getAncestors()));
        $this->assertEquals(2, sizeof($smallChild->getAncestors()));
        $this->assertTrue($child->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($root));
        $this->assertTrue($smallChild->getAncestors()->contains($child));
        $this->assertFalse($smallChild->getAncestors()->contains($otherChild));
        $this->assertTrue($otherChild->getAncestors()->contains($root));
        $this->assertTrue($otherChild->getAncestors()->contains($child));
        $this->assertTrue($smallOtherChild->getAncestors()->contains($child));
    }

    public function testOffsprings(): void
    {
        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");

        $this->assertEmpty($smallChild->getOffsprings());
        $this->assertEquals(3, sizeof($child->getOffsprings()));
        $this->assertEquals(12, sizeof($root->getOffsprings()));
        $this->assertTrue($child->getOffsprings()->contains($smallChild));
        $this->assertTrue($root->getOffsprings()->contains($smallChild));
        $this->assertTrue($root->getOffsprings()->contains($child));
        $this->assertFalse($otherChild->getOffsprings()->contains($smallChild));
    }

    public function testOffspringsAfterRemove(): void
    {
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $this->entityManager->remove($otherChild);
        $this->entityManager->flush();

        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");

        $this->assertEmpty($smallChild->getOffsprings());
        $this->assertEquals(3, sizeof($child->getOffsprings()));
        $this->assertEquals(8, sizeof($root->getOffsprings()));
        $this->assertTrue($child->getOffsprings()->contains($smallChild));
        $this->assertTrue($root->getOffsprings()->contains($smallChild));
        $this->assertTrue($root->getOffsprings()->contains($child));
        $this->assertFalse($otherChild->getOffsprings()->contains($smallChild));
        $this->assertFalse($root->getOffsprings()->contains($otherChild));
    }

    public function testOffspringsAfterMovingCategory(): void
    {
        $child = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 2");
        $otherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Enfant 3");
        $otherChild->setParent($child);
        $this->entityManager->flush();

        $root = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Racine");
        $smallChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 2-3");
        $smallOtherChild = $this->entityManager
            ->getRepository(Category::class)
            ->findOneByTitle("Petit enfant 3-3");

        $this->assertEmpty($smallChild->getOffsprings());
        $this->assertEquals(7, sizeof($child->getOffsprings()));
        $this->assertEquals(12, sizeof($root->getOffsprings()));
        $this->assertTrue($child->getOffsprings()->contains($otherChild));
        $this->assertTrue($child->getOffsprings()->contains($smallChild));
        $this->assertTrue($child->getOffsprings()->contains($smallOtherChild));
        $this->assertTrue($otherChild->getOffsprings()->contains($smallOtherChild));
        $this->assertTrue($root->getOffsprings()->contains($child));
        $this->assertTrue($root->getOffsprings()->contains($otherChild));
        $this->assertTrue($root->getOffsprings()->contains($smallChild));
        $this->assertTrue($root->getOffsprings()->contains($smallOtherChild));
        $this->assertFalse($otherChild->getOffsprings()->contains($smallChild));
        $this->assertFalse($root->getChildren()->contains($otherChild));
    }
}
