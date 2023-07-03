<?php

/** @var SergiX44\Nutgram\Nutgram $bot */

use App\Telegram\Commands\Start;
use App\Telegram\Handlers\DocumentMessageHandler;
use App\Telegram\Handlers\EditedMessageHandler;
use App\Telegram\Handlers\PhotoMessageHandler;
use App\Telegram\Handlers\TextMessageHandler;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\Telegram\Properties\MessageType;

/*
|--------------------------------------------------------------------------
| Nutgram Handlers
|--------------------------------------------------------------------------
|
| Here is where you can register telegram handlers for Nutgram. These
| handlers are loaded by the NutgramServiceProvider. Enjoy!
|
*/

// $bot->onCommand('start', [Start::class, 'handle']);

$bot->onMessageType(MessageType::TEXT, TextMessageHandler::class);

$bot->onEditedMessage(EditedMessageHandler::class);
$bot->onPhoto(PhotoMessageHandler::class);
// $bot->onDocument(DocumentMessageHandler::class);

// $bot->fallback(function (Nutgram $bot) {
//     $bot->sendMessage('Такие сообщения не поддерживаются');
// });
