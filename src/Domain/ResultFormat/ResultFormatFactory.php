<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Core\Database\QueryResult;
use Sh1ne\MySqlBot\Core\Database\SqlQuery;

class ResultFormatFactory
{

    public function make(SqlQuery $sqlQuery, QueryResult $result) : ResultFormat
    {
        // return new CsvMessage($sqlQuery, $result);
        //return new CsvFile($sqlQuery, $result);
        return new SqlFile($sqlQuery, $result);
    }

}