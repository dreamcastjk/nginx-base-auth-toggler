<?php


namespace App\Helpers;


use App\Interfaces\ISender;
use Http;

/**
 * Class MattermostHelper
 * @package App\Helpers
 */
class MattermostHelper implements ISender
{
    const NOTIFY_TYPE_CHANNEL = '@channel';

    const COLOR_PURPLE = '#c830ff';

    /**
     * @param string $notifyType
     * @param string $messageText
     * @param string $attachmentText
     */
    public static function sendMessage(
        string $notifyType,
        string $messageText,
        string $attachmentText = ''
    ): void
    {
        if (!config('mattermost.hook_url')
            || !config('mattermost.channel')
            || !config('mattermost.username')
        ) {
            return;
        }

        Http::post(config('mattermost.hook_url'), array_merge([
            'channel' => config('mattermost.channel'),
            'username' => config('mattermost.username'),
            'icon_url' => config('mattermost.icon_url'),
        ], static::setMessageData($messageText, $attachmentText, $notifyType)));
    }

    /**
     * @param string $messageText
     * @param string $attachmentText
     * @param string $notifyType
     * @param string $attachmentColor
     * @return array
     */
    private static function setMessageData(
        string $messageText,
        string $attachmentText = '',
        string $notifyType = '',
        string $attachmentColor = self::COLOR_PURPLE
    ): array
    {
        $attachments = [
            'attachments' => [
                [
                    'text' => $notifyType . PHP_EOL . $attachmentText,
                    'color' => $attachmentColor,
                ],
            ]
        ];

        $message = [
            'text' => $messageText,
        ];

        return $attachmentText ? array_merge($message, $attachments) : $message;
    }
}
