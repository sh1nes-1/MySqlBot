<?php

namespace Sh1ne\MySqlBot\Core\Database;

use Sh1ne\MySqlBot\Core\Log;

class DbConnectionWithLog implements DbConnection
{

    private DbConnection $dbConnection;

    public function __construct(DbConnection $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }

    public function query(SqlQuery $sqlQuery, array $params = []) : QueryResult
    {
        $timeBefore = microtime(true);

        $result = $this->dbConnection->query($sqlQuery, $params);

        $queryTime = microtime(true) - $timeBefore;

        Log::debug('SQL query finished', [
            'time' => $queryTime,
            'rows_count' => count($result->getRows()),
        ]);

        return $result;
    }

}