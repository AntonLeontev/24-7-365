<?php

namespace App\Telegram\Handlers;

use App\Models\Article;
use SergiX44\Nutgram\Nutgram;

class EditedMessageHandler
{
    public function __invoke(Nutgram $bot): void
    {
        Article::query()
            ->where([
                'chat_id' => $bot->message()->chat->id,
                'message_id' => $bot->message()->message_id,
            ])
            ->firstOrCreate()
            ->update(['text' => $bot->message()->text]);
    }
}
