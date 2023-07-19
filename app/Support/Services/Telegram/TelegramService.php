<?php

namespace App\Support\Services\Telegram;

class TelegramService
{
    public function __construct(public TelegramBotApi $bot)
    {
    }

    public function sendText(string $text, int|string $chat): void
    {
        $this->bot->sendMessage($chat, $text);
    }

    public function sendSilentText(string $text, int|string $chat): void
    {
        $this->bot->sendMessage($chat, $text, disableNotification: true);
    }
}
