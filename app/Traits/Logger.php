<?php


namespace App\Traits;

use Illuminate\Support\Facades\Log;

trait Logger
{
    /**
     * Loging exception into log file.
     *
     * @param \Exception $exception
     */
    private function logException(\Exception $exception): void
    {
        Log::error($exception->getMessage() . PHP_EOL . $exception->getTraceAsString());
    }
}
