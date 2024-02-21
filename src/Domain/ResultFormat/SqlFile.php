<?php

namespace Sh1ne\MySqlBot\Domain\ResultFormat;

use Sh1ne\MySqlBot\Domain\Messenger\File;
use Sh1ne\MySqlBot\Domain\Messenger\Messenger;

class SqlFile extends ResultFormat
{

    public function sendWithMessage(Messenger $messenger, string $message) : void
    {
        $sql = $this->generateSql();

        $file = new File('result.sql', 'sql', $sql);

        $messenger->uploadFile($file, $message);
    }

    private function generateSql() : string
    {
        $columnsJoined = implode(',', $this->result->getColumns());
        $insertStatement = "INSERT INTO {$this->sqlQuery->getTable()} ($columnsJoined) VALUES \n";

        $sql = $insertStatement;
        $rowsCount = count($this->result->getRows());

        foreach ($this->result->getRows() as $i => $row) {
            $sql .= $this->valuesToString($row);

            $isLastElement = $i === $rowsCount - 1;
            $isLastElementOfChunk = ($i + 1) % 1000 === 0;

            $sql .= $isLastElement || $isLastElementOfChunk ? ";\n" : ",\n";

            if ($isLastElementOfChunk && !$isLastElement) {
                $sql .= $insertStatement;
            }
        }

        return $sql;
    }

    private function formatValue(mixed $value) : string
    {
        if (is_null($value)) {
            return 'NULL';
        }

        $valueEscaped = addslashes($value);

        return "'$valueEscaped'";
    }

    private function valuesToString(mixed $row) : string
    {
        $values = [];

        foreach ($row as $value) {
            $values[] = $this->formatValue($value);
        }

        return '(' . implode(', ', $values) . ')';
    }

}