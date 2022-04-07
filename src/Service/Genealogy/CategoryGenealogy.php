<?php

namespace App\Service\Genealogy;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;

class CategoryGenealogy
{
    const CACHE_EXPIRATION = 30;

    /**
     * @var CategoryRepository
     */
    private $repository;
    /**
     * @var CacheInterface
     */
    private $cache;


    /**
     * @param CategoryRepository $categoryRepository
     */
    public function __construct(CategoryRepository $repository, CacheInterface $cache)
    {
        $this->repository = $repository;
        $this->cache = $cache;
    }

    /**
     * @param Category $category
     * @return array
     */
    public function ancestors($category)
    {
        return $this->cache->get("ancestorsIds-{$category->getId()}", function (ItemInterface $item) use ($category) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            $ancestors = [];
            while ($category !== null) {
                $ancestors[] = $category->getId();
                $category = $category->getParent();
            }
            return $ancestors;
        });
    }

    /**
     * @param Category $category
     * @return array
     */
    public function children($category)
    {
        return $this->cache->get("children-{$category->getId()}", function (ItemInterface $item) use ($category) {
            $item->expiresAfter(self::CACHE_EXPIRATION);
            $children = $category->getChildren();
            $ids = [];
            foreach ($children as $child) {
                $ids[] = $child->getId();
            }
            return $ids;
        });
    }
}
