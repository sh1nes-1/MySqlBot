<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use InvalidArgumentException;
use Sh1ne\MySqlBot\Core\Config\AppConfig;
use Sh1ne\MySqlBot\Core\Database\QueryResult;
use Sh1ne\MySqlBot\Core\Database\SqlQuery;

class ResultFormatFactory
{

    public function make(SqlQuery $sqlQuery, QueryResult $result) : ResultFormat
    {
        return match (AppConfig::getResultMessageFormat()) {
            'csv_message' => new CsvMessage($sqlQuery, $result),
            'csv_file' => new CsvFile($sqlQuery, $result),
            'sql_file' => new SqlFile($sqlQuery, $result),
            default => throw new InvalidArgumentException('Invalid result message format (Try csv_message, csv_file or sql_file)')
        };
    }

}