<?php

namespace App\Service\HTML\Navbar;

use App\Entity\Category;

class NavbarGrades
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var Category[]
     */
    public $categories;

    /**
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
        $this->categories = [];
    }

    /**
     * @param Category $item
     * @return void
     */
    public function add($category)
    {
        $this->categories[] = $category;
    }
}
