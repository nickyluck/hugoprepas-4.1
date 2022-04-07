<?php

namespace App\Service\HTML\Navbar;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use App\Service\Genealogy\CategoryGenealogy;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

/**
 * Service générant le rendu HTML du menu visiteurs de la page d'accueil.
 */
class SidebarHome
{
    const CACHE_EXPIRATION = 30;

    /**
     * @var string
     */
    public $title;
    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var CacheInterface
     */
    private $cache;
    private $genealogy;


    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $repository, CacheInterface $cache, CategoryGenealogy $genealogy)
    {
        $this->title = "Les classes prépas";
        $this->repository = $repository;
        $this->cache = $cache;
        $this->genealogy = $genealogy;
    }

    /**
     * Rendu HTML de la sidebar.
     *
     * @param integer $category_id
     * @return void
     */
    public function render($category_id = null)
    {
        $html = $this->cache->get('sidebarVisits', function (ItemInterface $item) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            $home = $this->repository->find(Category::HOME_ID);
            $html = "<nav class='navbar-left'>" . PHP_EOL . $this->tree($home) . PHP_EOL . "</nav>";
            return $html;
        });
        if ($category_id !== null) {
            $html = $this->setClassOn($html, $this->repository->find($category_id));
        }
        return $html;
    }

    /**
     * Construction récursive de l'arbre des sous-rubriques.
     *
     * @param Category $category
     * @return void
     */
    private function tree(Category $category)
    {
        if ($category->getId() == Category::HOME_ID) {
            $html = "<h3>{$this->title}</h3>" . PHP_EOL;
        } else {
            $html = "<a href='#'>{$category->getTitle()}</a>" . PHP_EOL;
        }

        $html .= "<ul rubrique='{$category->getId()}'>" . PHP_EOL;

        $children = $category->getChildren();
        if (!empty($children)) {
            foreach ($children as $child) {
                $html .= "<li>" . PHP_EOL;
                $html .= $this->tree($child) . PHP_EOL;
                $html .= "</li>" . PHP_EOL;
            }
            $html .= "</ul>" . PHP_EOL;
        } else {
            $articles = $category->getArticles();
            foreach ($articles as $article) {
                $html .= "<li>" . PHP_EOL;
                $html .= "<a href='#'>{$article->getTitle()}</a>" . PHP_EOL;
                $html .= "</li>" . PHP_EOL;
            }
            $html .= "</ul>" . PHP_EOL;
        }

        return $html;
    }

    /**
     * Active une rubrique dans le menu.
     *
     * @param string $html
     * @param Category $category
     * @return void
     */
    private function setClassOn($html, $category)
    {
        $html = str_replace(" class='on'", "", $html);
        $ancestors = $this->genealogy->ancestors($category);
        foreach ($ancestors as $ancestor) {
            $html = str_replace("rubrique='$ancestor'", "rubrique='$ancestor' class='on'", $html);
        }

        return $html;
    }
}
