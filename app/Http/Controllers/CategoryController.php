<?php

namespace App\Http\Controllers;

use App\Services\CategoryService;
use App\Http\Requests\CategoryRequest;

use Illuminate\Http\JsonResponse;

use Exception;

/**
 * Controller for categories.
 */
final class CategoryController extends Controller
{
    /**
     * Initializes properties.
     * 
     * @param CategoryService $service  The category service.
     */
    public function __construct(CategoryService $service)
    {
        parent::__construct($service);
    }

    /**
     * Registers a new category.
     * 
     * @param CategoryRequest $request  The category validation rules.
     * 
     * @return JsonResponse The request response.
     */
    public function register(CategoryRequest $request): JsonResponse
    {
        try {
            $this->data['message'] = 'Category created successfully!';

            $this->data['id'] = $this->service->insert($request->validated());
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Updates a category.
     * 
     * @param CategoryRequest $request  The category validation rules.
     * 
     * @return JsonResponse The request response.
     */
    public function update(CategoryRequest $request): JsonResponse
    {
        try {
            $this->service->updateByID($request->id, $request->validated());

            $this->data['message'] = 'Category updated successfully!';
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }
}
