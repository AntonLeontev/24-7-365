<?php

namespace App\Telegram\Handlers;

use App\Support\Services\Telegram\TelegramService;
use SergiX44\Nutgram\Nutgram;

class PhotoMessageHandler
{
    public function __invoke(Nutgram $bot): void
    {
        $service = app(TelegramService::class);

        $service->storeMessage($bot->message());
    }
}
