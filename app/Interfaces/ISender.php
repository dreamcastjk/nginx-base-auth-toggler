<?php


namespace App\Interfaces;

/**
 * Interface ISender
 * @package App\Interfaces
 */
interface ISender
{
    public static function sendMessage(
        string $notifyType,
        string $messageText,
        string $attachmentText = ''
    ): void;
}
