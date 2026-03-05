<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface CategoryInterface
{
    public function getCategoriesForHome(): Collection;
    public function getCategoriesForAllItem(): Collection;
}