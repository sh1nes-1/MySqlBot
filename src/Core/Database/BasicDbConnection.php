<?php

namespace Sh1ne\MySqlBot\Core\Database;

use Exception;
use PDO;
use PDOStatement;

class BasicDbConnection implements DbConnection
{

    private PDO $pdo;

    public function __construct(string $host, int $port, string $username, string $password, string $database)
    {
        $this->pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $username, $password);
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    }

    /**
     * @throws ReadOnlyException
     * @throws DbException
     */
    public function query(string $sql, array $params = []) : QueryResult
    {
        if (!$this->isReadOnlySql($sql)) {
            throw new ReadOnlyException('Query is modifying data');
        }

        try {
            return $this->executeQuery($sql, $params);
        } catch (Exception $e) {
            throw new DbException($e->getMessage(), $e->getCode(), $e);
        }
    }

    private function isReadOnlySql(string $sql) : bool
    {
        $sql = strtoupper(trim($sql));

        $forbiddenKeywords = ['INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP', 'TRUNCATE', 'RENAME'];

        foreach ($forbiddenKeywords as $query) {
            if (str_starts_with($sql, $query)) {
                return false;
            }
        }

        return true;
    }

    public function executeQuery(string $sql, array $params) : QueryResult
    {
        $statement = $this->pdo->prepare($sql);

        $statement->execute($params);

        $data = $statement->fetchAll();

        if ($data === false) {
            $data = [];
        }

        return new QueryResult($this->getColumns($statement), $data);
    }

    private function getColumns(PDOStatement $statement) : array
    {
        $columns = [];

        for ($i = 0; $i < $statement->columnCount(); $i++) {
            $column = $statement->getColumnMeta($i);

            $columns[] = $column['name'];
        }

        return $columns;
    }

}