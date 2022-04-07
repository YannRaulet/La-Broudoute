<?php

namespace App\Classe;

use App\Entity\Category;

class Search
{
    /**
     * @var integer
     */
    public $page = 1;

    /**
     * @var string
     */
    public $string = '';        // No need private because no need getters and setters
    /**
     * @var Category[]
     */
    public $categories = [];

    /* ---------------- A ÉTÉ AJOUTÉ -------------------------*/
    /**
     * @var null|integer
     */
    public $max;

    /**
     * @var null|integer
     */
    public $min;

    /**
     * @var boolean
     */
    public $promo = false;

}
