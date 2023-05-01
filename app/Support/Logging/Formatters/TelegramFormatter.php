<?php

namespace App\Support\Logging\Formatters;

use Monolog\Formatter\NormalizerFormatter;
use Monolog\LogRecord;

class TelegramFormatter extends NormalizerFormatter
{
    public function format(LogRecord $record): string
    {
        if (isset($record->context['exception'])) {
            $message = $this->formatException($record);
        } else {
			$message = $this->formatMessage($record);
		}

        return $this->postFormat($message);
    }

    public function formatBatch(array $records): string
    {
        $message = '';
        foreach ($records as $record) {
            $message .= $this->format($record);
        }

        return $message;
    }

    private function formatException(LogRecord $record): string
    {
        $exception = $record->context['exception'];

        return "{$record->level->name}: {$record->message}\n<b>file:</b> {$exception->getFile()}:{$exception->getLine()}";
    }

    private function formatMessage(LogRecord $record): string
    {
        $message = str($record->message);

        foreach ($record->context as $key => $value) {
            $message = $message->replace("{{$key}}", $value);
        }

        $context = htmlspecialchars(print_r($record->context, true));

        if (strlen($context) > 4000) {
            return $message->append("\n", $context);
        }

        return $message->append("\n", '<pre>', $context, '</pre>');
    }

    private function postFormat(string $message): string
    {
        $project = env('APP_NAME');
        $url = request()->fullUrl();

        return str($message)->prepend("<b><u>$project</u></b>\n$url\n")->value();
    }
}
