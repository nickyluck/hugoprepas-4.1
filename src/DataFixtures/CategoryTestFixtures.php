<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class CategoryTestFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $root = (new Category)->setTitle("Racine");
        $manager->persist($root);

        for ($i = 1; $i <= 3; $i++) {
            $child = (new Category)->setTitle("Enfant $i")->setParent($root);
            $manager->persist($child);

            for ($j = 1; $j <= 3; $j++) {
                $smallChild = (new Category)->setTitle("Petit enfant $i-$j")->setParent($child);
                $manager->persist($smallChild);
            }
        }

        $manager->flush();
    }
}
