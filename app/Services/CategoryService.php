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

    public function addCategories($category_name, $category_description, $category_image)
    {
        $imagePath = null;
        if ($category_image) {
            $userId = auth()->id();
            $timestamp = time();
            $extension = $category_image->getClientOriginalExtension();
            $fileName = $userId . '_' . $timestamp . '.' . $extension;
            $category_image->storeAs('categories', $fileName, 'public');
            $imagePath = 'storage/categories/' . $fileName;
        }
        return $this->categories->addCategories($category_name, $category_description, $imagePath);
    }

    public function getAllCategories()
    {
        return $this->categories->getAllCategories();
    }

    public function deleteCategory($category_id)
    {
        return $this->categories->deleteCategory($category_id);
    }

    public function getCategory($category_id)
    {
        return $this->categories->getCategory($category_id);
    }

    public function updateCategory($category_id, $category_name, $category_description, $category_image)
    {
        $imagePath = null;
        if ($category_image) {
            $category_id = auth()->id();
            $timestamp = time();
            $extension = $category_image->getClientOriginalExtension();
            $fileName = $category_id . '_' . $timestamp . '.' . $extension;
            $category_image->storeAs('categories', $fileName, 'public');
            $imagePath = 'storage/categories/' . $fileName;
        }
        return $this->categories->updateCategory($category_id, $category_name, $category_description, $imagePath);
    }
    
}