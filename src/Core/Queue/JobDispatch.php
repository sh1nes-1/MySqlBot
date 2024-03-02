<?php

namespace Sh1ne\MySqlBot\Core\Queue;

class JobDispatch
{

    protected bool $shouldDispatch;

    protected Job $job;

    protected string $id;

    protected string $queue;

    public function __construct(Job $job)
    {
        $this->shouldDispatch = true;
        $this->job = $job;
        $this->id = uniqid();
        $this->queue = 'default';
    }

    public function getJob() : Job
    {
        return $this->job;
    }

    public function getId() : string
    {
        return $this->id;
    }

    public function getQueue() : string
    {
        return $this->queue;
    }

    public function onQueue(string $queue) : static
    {
        $this->queue = $queue;

        return $this;
    }

    public function __destruct()
    {
        if (!$this->shouldDispatch) {
            return;
        }

        $this->shouldDispatch = false;

        app(Dispatcher::class)->dispatch($this);
    }

}