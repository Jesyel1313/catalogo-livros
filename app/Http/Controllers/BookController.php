<?php

namespace App\Http\Controllers;

use App\Http\Requests\BookRequest;
use App\Services\BookService;

use Illuminate\Http\JsonResponse;

use Exception;

/**
 * Controller for book.
 */
final class BookController extends Controller
{
    /**
     * Initializes properties.
     * 
     * @param BookService $service  Service for book.
     */
    public function __construct(BookService $service)
    {
        parent::__construct($service);
    }

    /**
     * Registers a new book.
     * 
     * @param BookRequest $request  The request object.
     * 
     * @return JsonResponse The response object.
     */
    public function register(BookRequest $request): JsonResponse
    {
        try {
            $this->data['message'] = 'Book created successfully!';

            $this->data['id'] = $this->service->insert($request->validated());
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Updates a specific book.
     * 
     * @param BookRequest $request  The request object.
     * 
     * @return JsonResponse The response object.
     */
    public function update(BookRequest $request): JsonResponse
    {
        try {
            $this->service->updateByID($request->id, $request->validated());

            $this->data['message'] = 'Book updated successfully!';
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }
}
