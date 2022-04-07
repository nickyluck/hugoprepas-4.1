<?php

namespace App\Service\HTML\Navbar;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Environment;

/**
 * Service générant le rendu HTML du menu des classes de la page d'accueil.
 */
class SidebarGrades
{
    const CACHE_EXPIRATION = 1;

    /**
     * @var string
     */
    public $title;
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var Environment
     */
    private $twig;
    /**
     * @var CacheInterface
     */
    private $cache;


    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $repository, Environment $twig, CacheInterface $cache)
    {
        $this->title = "Site des classes";
        $this->repository = $repository;
        $this->twig = $twig;
        $this->cache = $cache;
    }

    /**
     * Rendu HTML de la sidebar.
     *
     * @return string
     */
    public function render()
    {
        $html = $this->cache->get('sidebarHome', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            $grades = $this->repository->find(Category::GRADES_ID);
            $misc = $this->repository->find(Category::MISC_ID);
            $categories = array_merge(
                $this->repository->findBy(['parent' => $grades], ['place' => 'ASC']),
                $this->repository->findBy(['parent' => $misc], ['place' => 'ASC'])
            );
            return $this->twig->render('home/_navbar_classes.html.twig', [
                'title' => $this->title,
                'categories' => $categories,
            ]);
        });
        return $html;
    }
}
