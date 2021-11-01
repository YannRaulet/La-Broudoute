<?php

namespace App\Classe;

use App\Entity\Category;

class Search
{
    /**
     * @var string
     */
    public $string = '';        // No need private beacause no need getters and setters
    /**
     * @var Category[]
     */
    public $categories = [];
}
