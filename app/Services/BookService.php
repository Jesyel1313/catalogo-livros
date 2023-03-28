<?php

namespace App\Services;

use App\Models\Book;

use Illuminate\Database\Eloquent\Model;

/**
 * Service for books.
 */
final class BookService extends Service
{
    /**
     * Initializes properties.
     * 
     * @param Book $model   The book entity.
     */
    public function __construct(Book $model)
    {
        $this->model = $model;
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
        array $fields = ['id', 'name', 'isbn', 'author_id']
    ) {
        $books = parent::fetch($start, $limit, $order, $orderBy, $fields);
        foreach ($books as $book) {
            $book->author;
        }

        return $books;
    }

    /**
     * Retrieves a record by ID.
     * 
     * @param int $id   The record ID.
     * 
     * @return Model The requested record.
     */
    public function fetchByID(int $id): Model
    {
        $record = parent::fetchByID($id);
        $record->author;
        $record->category;

        return $record;
    }
}