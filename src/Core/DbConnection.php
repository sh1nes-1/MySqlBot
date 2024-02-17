<?php

namespace Sh1ne\MySqlBot\Core;

interface DbConnection
{

    public function query(string $sql, array $params = []) : array;

}