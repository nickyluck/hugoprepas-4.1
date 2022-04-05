<?php

namespace App\DataFixtures;

use App\Entity\Category;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class CategoryFixtures extends Fixture
{
    private $faker;
    private $nb_categories;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
        $this->nb_categories = 0;
    }

    public function load(ObjectManager $manager): void
    {
        $home = new Category;
        $home->setTitle("Accueil")
            ->setDescription("Page d'accueil du site Hugoprépas 4.");
        $manager->persist($home);
        $this->addReference("home", $home);

        $classes = new Category;
        $classes->setTitle("Sites des classes")
            ->setDescription("Rubrique contenant tous les sites des classes préparatoires du lycée Victor Hugo.");
        $manager->persist($classes);
        $this->addReference("classes", $classes);

        $divers = new Category;
        $divers->setTitle("Divers")
            ->setDescription("Rubrique contenant toutes les rubriques et articles qui ne sont ni dans la page d'accueil ni dans un site d'une classe.\nCette rubrique contient notamment la rubrique \"Documents Profs\" permettant aux professeurs d'échanger des documents.");
        $manager->persist($divers);
        $this->addReference("divers", $divers);

        $this->buildTree($manager, $home, 5, 3);

        $liste_des_classes = ["MPSI", "MP2I", "PCSI 1", "PCSI 2", "BCPST 1", "MP", "MP*", "PC", "PC*", "PSI", "BCPST 2"];
        $liste_des_matieres = ["Infos Générales", "Mathématiques", "Physique - Chimie", "S.I.I.", "Français - Philosophie", "Langues Vivantes", "Informatique", "TIPE", "S.V.T."];
        foreach ($liste_des_classes as $class_name) {
            $class = new Category;
            $class->setTitle($class_name)
                ->setDescription("Site de la classe de {$class_name}.")
                ->setParent($classes);
            $manager->persist($class);
            $this->addReference($class_name, $class);

            foreach ($liste_des_matieres as $matiere_name) {
                $matiere = new Category;
                $matiere->setTitle($matiere_name)
                    ->setParent($class);
                $manager->persist($matiere);

                $this->buildTree($manager, $matiere, 2, 5);
            }
        }

        $this->buildTree($manager, $divers, 3, 2);

        $manager->flush();
    }

    private function buildTree(ObjectManager $manager, Category $category, int $deep, int $max_children)
    {
        if ($deep > 0) {
            for ($i = 0; $i < $max_children; $i++) {
                $child = new Category;
                $child->setTitle($this->faker->sentence(4))
                    ->setDescription($this->faker->text(50))
                    ->setParent($category)
                    ->setIsVisible($this->faker->numberBetween(0, 1));
                $manager->persist($child);
                $this->addReference("Category-" . $this->nb_categories, $child);
                $this->nb_categories++;
                $this->buildTree($manager, $child, $deep-1, $max_children);
            }
        }
    }
}
