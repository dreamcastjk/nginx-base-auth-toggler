<?php


namespace App\Traits;


trait BaseResponse
{
    /**
     * Returning response structure.
     *
     * @param string $status
     * @param string $message
     * @return string[]
     */
    private function response(string $status, string $message): array
    {
        return [
            'status' => $status,
            'message' => $message,
        ];
    }
}
