<?php

namespace Sh1ne\MySqlBot\Core\Database;

class DB
{

    /**
     * @throws DbException
     * @throws ReadOnlyException
     */
    public static function query(SqlQuery $sqlQuery, array $params = []) : QueryResult
    {
        return app(DbConnection::class)->query($sqlQuery, $params);
    }

}