<?php

namespace App\Http\Controllers;

use App\Exceptions\ClientException;
use App\Services\Service;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;

use Exception;

/**
 * Base class for controllers.
 */
abstract class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    /**
     * @var int $code Response status code.
     */
    protected int $code = 200;

    /**
     * @var string[]|object $data The response data.
     */
    protected array|object $data = [];

    /**
     * @var Service The service for the current controller.
     */
    protected Service $service;

    /**
     * Initializes properties and settings.
     * 
     * @param Service $service  The service for the current resource.
     * @param array $options    Options for middleware.
     */
    public function __construct(Service $service = null, array $options = [])
    {
        $this->service = $service;

        // Protect all routes. Exceptions can be passed by parameter in each
        // controller.
        $this->middleware('auth:api', $options);
    }

    /**
     * Lists a group of records according to the specified parameters.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function list(Request $request): JsonResponse
    {
        $params = $request->validate([
            'start' => 'integer|nullable',
            'limit' => 'integer|nullable',
            'order' => 'string|nullable',
            'order_by' => 'string|nullable'
        ]);
        // Sets the default value for the starting record position in
        // pagination.
        $params['start'] = $params['start'] ?? 0;
        // Sets the default value for total records, in pagination.
        $params['limit'] = $params['limit'] ?? 20;
        // Defines the ordering of records.
        $params['order'] = (
            array_key_exists('order', $params) &&
            strtoupper($params['order']) == 'DESC'
        ) ? 'DESC' : 'ASC';
        // Defines the sort field.
        $fields = ['id', 'name', 'created_at'];
        $params['order_by'] = (
            array_key_exists('order_by', $params) &&
            in_array(strtolower($params['order_by']), $fields)
        ) ? $params['order_by'] : 'id';

        $this->data = $this->service->fetch(
            $params['start'],
            $params['limit'],
            $params['order'],
            $params['order_by']
        );

        return $this->send();
    }

    /**
     * Retrieves a specific record.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function retrieve(Request $request): JsonResponse
    {
        try {
            $this->data = $this->service->fetchByID($request->id);
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Removes the specified record.
     * 
     * @param Request $request  The request object.
     * 
     * @return JsonResponse The request response.
     */
    public function remove(Request $request): JsonResponse
    {
        try {
            $this->service->deleteByID($request->id);

            $this->data['message'] = 'Record removed successfully!';
        } catch (Exception $exception) {
            $this->handleException($exception);
        }

        return $this->send();
    }

    /**
     * Dispatches the request response.
     * 
     * @return JsonResponse The request response.
     */
    protected function send(): JsonResponse
    {
        return response()->json($this->data, $this->code);
    }

    /**
     * Handles different types of exceptions.
     * 
     * @param Exception $exception The exception thrown.
     * 
     * @return void
     */
    protected function handleException(Exception $exception): void
    {
        if ($exception instanceof ClientException) {
            $this->data['message'] = $exception->getMessage();
            $this->code = $exception->getCode();
        } elseif ($exception instanceof ValidationException) {
            $this->data['message'] = $exception->getMessage();
            $this->code = $exception->status;
        } else {
            $message = 'Internal error when performing the operation.';
            $this->data['message'] = $message;
            $this->code = 500;
        }
    }
}
