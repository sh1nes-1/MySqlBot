<?php

namespace Sh1ne\MySqlBot\Core;

interface DbConnection
{

    public function execute(string $sql) : array;

}