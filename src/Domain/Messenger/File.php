<?php

namespace Sh1ne\MySqlBot\Domain\Messenger;

class File
{

    private string $name;

    private string $type;

    private string $content;

    public function __construct(string $name, string $type, string $content)
    {
        $this->name = $name;
        $this->type = $type;
        $this->content = $content;
    }

    public function getName() : string
    {
        return $this->name;
    }

    public function getType() : string
    {
        return $this->type;
    }

    public function getContent() : string
    {
        return $this->content;
    }

}