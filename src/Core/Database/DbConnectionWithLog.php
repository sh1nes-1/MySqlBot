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

    public function query(string $sql, array $params = []) : array
    {
        $timeBefore = microtime(true);

        $result = $this->dbConnection->query($sql, $params);

        $queryTime = microtime(true) - $timeBefore;

        Log::debug('SQL query finished', [
            'time' => $queryTime,
            'rows_count' => count($result),
        ]);

        return $result;
    }

}