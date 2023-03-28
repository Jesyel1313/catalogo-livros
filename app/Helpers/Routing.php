<?php

namespace App\Helpers;

use Illuminate\Support\Facades\Route;

/**
 * Creation of common routes.
 */
final class Routing
{
    /**
     * Create default persistence routes for common resources.
     * 
     * Default routes are for resource collection retrieving, resource redemption by
     * ID, update and delete.
     * 
     * @param string $resource  The resource name.
     * @param string $class     Controller class name.
     * 
     * @return void
     */
    public static function createPersistenceRoutes(
        string $resource,
        string $class
    ): void
    {
        // Lists the records.
        Route::get($resource, [$class, 'list']);
        // Retrieves a specific record.
        Route::get("{$resource}/{id}", [$class, 'retrieve'])->whereNumber('id');
        // Creates a new record.
        Route::post($resource, [$class, 'register']);
        // Updates a specific record.
        Route::put("{$resource}/{id}", [$class, 'update'])->whereNumber('id');
        // Removes a specific record.
        Route::delete(
            "{$resource}/{id}",
            [$class, 'remove']
        )->whereNumber('id');
    }
}
