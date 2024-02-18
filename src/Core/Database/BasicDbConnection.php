<?php

namespace Sh1ne\MySqlBot\Core\Database;

use Exception;
use PDO;

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
    public function query(string $sql, array $params = []) : array
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

    /**
     * @param string $sql
     * @param array $params
     *
     * @return array<array<string, mixed>>
     */
    public function executeQuery(string $sql, array $params) : array
    {
        $statement = $this->pdo->prepare($sql);

        $statement->execute($params);

        $data = $statement->fetchAll();

        if ($data === false) {
            return [];
        }

        return $data;
    }

}