<?php

namespace Sh1ne\MySqlBot\Core;

class BasicDbConnection implements DbConnection
{

    public function query(string $sql, array $params = []) : array
    {
        // TODO: Implement execute() method.
        return [
            [1, 'hello'],
            [2, 'world'],
        ];
    }

}