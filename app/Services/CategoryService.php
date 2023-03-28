<?php

namespace App\Services;

use App\Models\Category;

/**
 * Service for category.
 */
final class CategoryService extends Service
{
    /**
     * Initializes properties.
     * 
     * @param Category $model   The category entity.
     */
    public function __construct(Category $model)
    {
        $this->model = $model;
    }
}