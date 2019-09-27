<?php

namespace App\Listeners;

use App\Events\TelegramUpdatesEvent;
use App\TelegramUser;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;
use Telegram\Bot\Objects\InlineQuery;
use Telegram\Bot\Objects\Message;

class TelegramUpdatesListener
{
    protected $telegram;

    /**
     * Create the event listener.
     *
     * @param Api $telegram
     */
    public function __construct(Api $telegram)
    {
        $this->telegram = $telegram;
    }

    /**
     * Handle the event.
     *
     * @param TelegramUpdatesEvent $event
     * @return void
     * @throws TelegramSDKException
     */
    public function handle(TelegramUpdatesEvent $event)
    {
        $updates = $event->getUpdates();

        if ($updates->inlineQuery) {
            $this->processInlineQuery($updates->inlineQuery);
            $from = $updates->inlineQuery->from;
        } elseif ($updates->message) {
            $this->processMessageRequest($updates->message);
            $from = $updates->message->from;
        } else {
            return;
        }

        $responseTime = round((microtime(true) - APP_START_TIME) * 1000, 2);

        $histogram = app('prometheus')->getOrRegisterHistogram('', 'response_time', 'Bot response time', [], [
            0, 3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 45, 50
        ]);

        $histogram->observe($responseTime);

        if ($serializedUser = app('redis')->get($from->id)) {
            $telegramUser = TelegramUser::load($serializedUser);
            $telegramUser->incrementCalls();
        } else {
            $name = sprintf('%s %s', $from->firstName, $from->lastName);
            $telegramUser = new TelegramUser($from->id, $name);
            app('prometheus')->getOrRegisterCounter('', 'uniq_users', 'Unique users count')->inc();
        }
        app('prometheus')->getOrRegisterCounter('', 'requests_served', 'Requests count')->inc();

        app('redis')->set($from->id, (string)$telegramUser);
    }

    /**
     * @param InlineQuery $inlineQuery
     * @throws TelegramSDKException
     */
    protected function processInlineQuery(InlineQuery $inlineQuery)
    {
        $results = [];

        for ($i = 1; $i < 4; $i++) {
            $excuse = app('excuses')->getRandom();

            $results[] = [
                'type' => 'article',
                'id' => $i,
                'title' => 'Excuse ' . $i,
                'message_text' => $excuse,
                'description' => $excuse,
            ];
        }

        $this->telegram->answerInlineQuery([
            'inline_query_id' => $inlineQuery['id'],
            'results' => json_encode($results),
            'cache_time' => 0,
        ]);
    }

    /**
     * @param Message $message
     * @throws TelegramSDKException
     */
    protected function processMessageRequest(Message $message)
    {
        if ($message->text === '/get') {
            $this->telegram->sendMessage([
                'chat_id' => $message->chat->id,
                'text' => app('excuses')->getRandom()
            ]);
        }
    }
}
