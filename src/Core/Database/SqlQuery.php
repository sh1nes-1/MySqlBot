<?php

namespace Sh1ne\MySqlBot\Core\Database;

class SqlQuery
{

    private string $sql;

    public function __construct(string $sql)
    {
        $this->sql = $sql;
    }

    public function getSql() : string
    {
        return $this->sql;
    }

    public function isReadOnly() : bool
    {
        $sql = strtoupper(trim($this->sql));

        $forbiddenKeywords = ['INSERT', 'UPDATE', 'DELETE', 'CREATE', 'ALTER', 'DROP', 'TRUNCATE', 'RENAME'];

        foreach ($forbiddenKeywords as $query) {
            if (str_starts_with($sql, $query)) {
                return false;
            }
        }

        return true;
    }

    public function getTable() : string
    {
        $pattern = '/\bFROM\s+(\S+)\b/i';

        preg_match($pattern, $this->sql, $matches);

        return $matches[1];
    }

}