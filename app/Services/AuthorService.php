<?php

namespace App\Services;

use App\Models\Author;

/**
 * Service book author.
 */
final class AuthorService extends Service
{
    /**
     * Initializes properties.
     * 
     * @param Author $model The author entity.
     */
    public function __construct(Author $model)
    {
        $this->model = $model;
    }
}