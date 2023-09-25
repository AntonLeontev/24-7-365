<?php

namespace App\Support\Services\Telegram;

use Illuminate\Support\Facades\Storage;

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

    public function sendDocument($chatId, $documentPath, $documentName, $silent = false): void
    {
        $document = Storage::get($documentPath);

        $this->bot->sendDocument($chatId, $document, $documentName, disableNotification: $silent);
    }
}
