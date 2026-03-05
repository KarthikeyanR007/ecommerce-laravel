<?php

namespace App\Services;

use App\Interfaces\CategoryInterface;
use Illuminate\Support\Collection;

class CategoryService
{
    public function __construct(private CategoryInterface $categories)
    {
    }

    public function getCategoriesForHome(): Collection
    {
        return $this->categories->getCategoriesForHome();
    }

    public function getCategoriesForAllItem(): Collection
    {
        return $this->categories->getCategoriesForAllItem();
    }
}