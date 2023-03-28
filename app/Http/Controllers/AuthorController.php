<?php

namespace App\Http\Controllers;

use App\Http\Requests\AuthorRequest;
use App\Services\AuthorService;

use Illuminate\Http\JsonResponse;

use Exception;

/**
 * Controller for author.
 */
final class AuthorController extends Controller
{
    /**
     * Initializes properties.
     * 
     * @param AuthorService $service    Service for author.
     */
    public function __construct(AuthorService $service)
    {
        parent::__construct($service);
    }

    /**
     * Registers a new author.
     *
     * @param AuthorRequest $request    Validation rules for author.
     *
     * @return JsonResponse The response object.
     */
    public function register(AuthorRequest $request): JsonResponse
    {
        try {
            $this->data['message'] = 'Author successfully created!';

            $this->data['id'] = $this->service->insert($request->validated());
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Updates an author.
     * 
     * @param AuthorRequest $request  The author validation rules.
     * 
     * @return JsonResponse The request response.
     */
    public function update(AuthorRequest $request): JsonResponse
    {
        try {
            $this->service->updateByID($request->id, $request->validated());

            $this->data['message'] = 'Author updated successfully!';
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }
}
