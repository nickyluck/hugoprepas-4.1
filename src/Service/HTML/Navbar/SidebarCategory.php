<?php

namespace App\Service\HTML\Navbar;

class SidebarCategory
{
    /**
     * @var string
     */
    public $title;
    /**
     * @var array
     */
    public $items;

    /**
     * @param string $title
     */
    public function __construct($title)
    {
        $this->title = $title;
        $this->items = [];
    }

    /**
     * @param mixed $item
     * @return void
     */
    public function add($item)
    {
        $this->items[] = $item;
    }
}
