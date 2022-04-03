<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $category = new Category;
        $category->setTitle("Catégorie de test");
        $subCategory = new Category;
        $subCategory->setTitle("Sous catégorie de test");
        $subCategory->setParent($category);
        $manager->persist($category);
        $manager->persist($subCategory);
        $manager->flush();
    }
}
