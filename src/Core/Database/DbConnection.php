<?php

namespace Sh1ne\MySqlBot\Core\Database;

interface DbConnection
{

    /**
     * @throws DbException
     * @throws ReadOnlyException
     */
    public function query(SqlQuery $sqlQuery, array $params = []) : QueryResult;

}