<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Core\Database\QueryResult;

class ResultFormatFactory
{

    public function make(QueryResult $result) : ResultFormat
    {
        return new CsvMessage($result);
    }

}