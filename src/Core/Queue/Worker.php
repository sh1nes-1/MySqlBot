<?php

namespace Sh1ne\MySqlBot\Core\Queue;

use Sh1ne\MySqlBot\Core\Console\Output;
use Sh1ne\MySqlBot\Core\Log;
use Throwable;

class Worker
{

    protected Queue $queue;

    public function __construct(Queue $queue)
    {
        $this->queue = $queue;
    }

    public function work() : void
    {
        $output = app(Output::class);

        while (true) {
            if ($this->queue->size() > 0) {
                $jobDispatch = $this->queue->pop();

                try {
                    Log::info('Executing job', [
                        'job_id' => $jobDispatch->getId(),
                    ]);

                    $output->info("Executing job {$jobDispatch->getId()}");

                    $job = $jobDispatch->getJob();

                    $job->handle();

                    Log::info('Job finished', [
                        'job_id' => $jobDispatch->getId(),
                    ]);

                    $output->info("Finished executing job {$jobDispatch->getId()}");
                } catch (Throwable $exception) {
                    Log::error('Job execution failed', [
                        'job_id' => $jobDispatch->getId(),
                        'exception' => (array) $exception,
                        'dispatch' => serialize($jobDispatch),
                    ]);

                    $output->error("Job failed {$jobDispatch->getId()}");
                }
            } else {
                sleep(1);
            }
        }
    }

}