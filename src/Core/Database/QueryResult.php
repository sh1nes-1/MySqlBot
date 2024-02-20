<?php

namespace Sh1ne\MySqlBot\Core\Database;

class QueryResult
{

    private array $columns;

    /**
     * @var array<array> $rows
     */
    private array $rows;

    public function __construct(array $columns, array $rows)
    {
        $this->columns = $columns;
        $this->rows = $rows;
    }

    public function getColumns() : array
    {
        return $this->columns;
    }

    public function getRows() : array
    {
        return $this->rows;
    }

}