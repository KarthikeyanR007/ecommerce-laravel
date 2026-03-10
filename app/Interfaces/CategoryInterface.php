<?php

namespace App\Interfaces;

use Illuminate\Support\Collection;

interface CategoryInterface
{
    public function getCategoriesForHome(): Collection;
    public function getCategoriesForAllItem(): Collection;
    public function addCategories($category_name, $category_description, $category_image);
    public function getAllCategories();
    public function deleteCategory($category_id);
    public function getCategory($category_id);
    public function updateCategory($category_id, $category_name, $category_description, $category_image);
}