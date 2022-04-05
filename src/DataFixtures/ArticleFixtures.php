<?php

namespace App\DataFixtures;

use App\Entity\Article;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class ArticleFixtures extends Fixture implements DependentFixtureInterface
{
    private $faker;
    private $nb_articles;

    public function __construct()
    {
        $this->faker = Factory::create('fr_FR');
        $this->nb_articles = 0;
    }

    public function load(ObjectManager $manager): void
    {
        while ($this->hasReference("Category-" . $this->nb_articles)) {
            $category = $this->getReference("Category-" . $this->nb_articles);
            $article = new Article;
            $article->setCategory($category)
                ->setTitle($this->faker->sentence(4))
                ->setContent($this->faker->text())
                ->setIsVisible($this->faker->numberBetween(0, 1));
            $manager->persist($article);
            $this->nb_articles++;
        }

        $manager->flush();
    }

    public function getDependencies()
    {
        return [
            CategoryFixtures::class
        ];
    }
}
