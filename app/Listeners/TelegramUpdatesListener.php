<?php

namespace App\Listeners;

use App\Events\TelegramUpdatesEvent;
use Telegram\Bot\Api;

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
     * @throws \Telegram\Bot\Exceptions\TelegramSDKException
     */
    public function handle(TelegramUpdatesEvent $event)
    {
        $updates = $event->getUpdates();

        $results = [];

        for ($i = 1; $i < 4; $i++) {
            $excuse = app('excuses')->getRandom();

            $results[] = [
                'type' => 'article',
                'id' => $i,
                'title' => 'Excuse ' . $i,
                'message_text' => $excuse,
                'description'    => $excuse,
            ];
        }

        $this->telegram->answerInlineQuery([
            'inline_query_id' => $updates->inlineQuery['id'],
            'results' => json_encode($results),
            'cache_time' => 0,
        ]);
    }
}
