<?php

namespace Sh1ne\MySqlBot\Core\ErrorHandler;

use Sh1ne\MySqlBot\Core\Log;

class BasicErrorHandler implements ErrorHandler
{

    public function setup(callable $shutdownCallback) : void
    {
        $this->disableErrorReporting();

        $this->registerErrorHandler();

        $this->registerShutdownCallback($shutdownCallback);
    }

    protected function disableErrorReporting() : void
    {
        ini_set('display_errors', 0);

        error_reporting(E_ERROR | E_PARSE | E_CORE_ERROR | E_COMPILE_ERROR | E_USER_ERROR);
    }

    protected function registerErrorHandler() : void
    {
        set_error_handler(function($level, $message, $file, $line) {
            if (!(error_reporting() & $level)) {
                Log::warning("[ErrorHandler] $message", [
                    'level' => $level,
                    'file' => $file,
                    'line' => $line,
                ]);

                // prevent calling shutdown callback if level is not important
                return true;
            }

            // call next error handler and eventually shutdown callback
            return false;
        });
    }

    protected function registerShutdownCallback(callable $shutdownCallback) : void
    {
        register_shutdown_function(function() use ($shutdownCallback) {
            $error = error_get_last();

            if (is_null($error)) {
                return;
            }

            $shutdownCallback();
        });
    }

}