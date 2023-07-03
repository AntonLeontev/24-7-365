<?php

namespace App\Support\Services\Telegram;

use App\Models\Article;
use App\Models\Chat;
use Illuminate\Support\Facades\DB;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Types\Message\Message;

class TelegramService
{
    public function __construct(public Nutgram $bot)
    {
    }

    public function storeMessage(Message $message): Article
    {
        return DB::transaction(function () use ($message) {
            $dbMessage = Article::query()->create([
                'message_id' => $message->message_id,
                'text' => $message->text ?? $message->caption,
                'from' => $message->from->id,
                'photo' => collect($message->photo)?->last()?->file_id,
                'photo_height' => collect($message->photo)?->last()?->height,
                'photo_width' => collect($message->photo)?->last()?->width,
            ]);

            return $dbMessage;
        }, 3);
    }

    public function downloadFile(string $fileId): string
    {
        $link = $this->getDownloadLink($fileId);

        return file_get_contents($link);
    }

    public function getDownloadLink(string $fileId): string
    {
        $file = $this->bot->getFile($fileId);

        return sprintf(
            'https://api.telegram.org/file/bot%s/%s',
            config('nutgram.token'),
            $file->file_path
        );
    }
}
