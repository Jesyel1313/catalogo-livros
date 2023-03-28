<?php

namespace App\Services;

use App\Exceptions\ClientException;

use Illuminate\Database\Eloquent\Model;

/**
 * Base for all services.
 */
abstract class Service
{
    /**
     * @var Model $model Entity representing a database table.
     */
    protected Model $model;

    /**
     * Creates a new record.
     * 
     * @param string[] $data    Record data.
     * 
     * @return int The new record ID.
     */
    public function insert(array $data): int
    {
        $record = $this->model::create($data);

        return $record->id;
    }

    /**
     * Retrieves a certain number of records.
     * 
     * @param int $start        The starting position of the record.
     * @param int $limit        The amount of records.
     * @param string $order     The ordering of records.
     * @param string $orderBy   The sort field.
     * @param string[] $fields  The fields of the select.
     * 
     * @return mixed Collection of table records.
     */
    public function fetch(
        int $start = 0,
        int $limit = 20,
        string $order = 'ASC',
        string $orderBy = 'id',
        array $fields = ['id', 'name']
    ) {
        $model = $this->model::select($fields);
        $model->skip($start);
        $model->take($limit);
        $model->orderBy($orderBy, $order);

        return $model->get();
    }

    /**
     * Retrieves a record by ID.
     * 
     * @param int $id   The record ID.
     * 
     * @throws ClientException  If the record is not found.
     * 
     * @return Model The requested record.
     */
    public function fetchByID(int $id): Model
    {
        $record = $this->model::find($id);
        if (!$record) {
            throw new ClientException('Record not found.', 404);
        }

        return $record;
    }

    /**
     * Deletes record by ID.
     * 
     * @param int $id   The record ID.
     * 
     * @throws ClientException  If the record is not found.
     * 
     * @return void
     */
    public function deleteByID(int $id): void
    {
        $record = $this->model::find($id);
        if (!$record) {
            throw new ClientException('Record not found.', 404);
        }

        $record->delete();
    }

    /**
     * Updates a record by ID.
     * 
     * @param int $id   The record ID.
     * @param string[]  The new record data.
     * 
     * @throws ClientException  If the record is not found.
     * 
     * @return void
     */
    public function updateByID(int $id, array $data): void
    {
        $record = $this->model::find($id);
        if (!$record) {
            throw new ClientException('Record not found.', 404);
        }

        $record->update($data);
    }
}