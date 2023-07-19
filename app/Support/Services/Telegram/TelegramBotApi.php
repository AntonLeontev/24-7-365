<?php

namespace App\Support\Services\Telegram;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class TelegramBotApi
{
    public static function sendMessage(
        int|string $chat,
        string $text,
        string $parseMode = 'HTML',
        bool $disableNotification = false,
        bool $disableWebPagePreview = false,
    ): Response {
        return Http::telegram()
            ->post('/sendMessage', [
                'chat_id' => $chat,
                'text' => $text,
                'parse_mode' => $parseMode,
                'disable_notification' => $disableNotification,
                'disable_web_page_preview' => $disableWebPagePreview,
            ]);
    }
}
