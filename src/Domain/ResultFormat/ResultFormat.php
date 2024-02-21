<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Core\Database\QueryResult;
use Sh1ne\MySqlBot\Core\Database\SqlQuery;
use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

abstract class ResultFormat
{

    protected SqlQuery $sqlQuery;

    protected QueryResult $result;

    public function __construct(SqlQuery $sqlQuery, QueryResult $result)
    {
        $this->sqlQuery = $sqlQuery;
        $this->result = $result;
    }

    abstract public function sendWithMessage(Messenger $messenger, string $message) : void;

}