<?php

namespace App\Listeners;

use App\Events\TelegramUpdatesEvent;
use App\Handlers;
use App\TelegramUser;

class TelegramUpdatesListener
{
    /**
     * Handle the event.
     *
     * @param TelegramUpdatesEvent $event
     * @return void
     */
    public function handle(TelegramUpdatesEvent $event)
    {
        $updates = $event->getUpdates();
        $from = $updates->inlineQuery
            ? $updates->inlineQuery->from
            : $updates->message->from;

        if ($serializedUser = app('redis')->get($from->id)) {
            $telegramUser = TelegramUser::load($serializedUser);
            $telegramUser->incrementCalls();
        } else {
            $name = sprintf('%s %s', $from->firstName, $from->lastName);
            $telegramUser = new TelegramUser($from->id, $name);
            app('prometheus')->getOrRegisterCounter('', 'uniq_users', 'Unique users count')->inc();
        }

        $responseTime = round((microtime(true) - APP_START_TIME) * 1000, 2);
        (new Handlers\Factory($updates, $telegramUser))->build()->handle();

        $histogram = app('prometheus')->getOrRegisterHistogram('', 'response_time', 'Bot response time', [], [
            0, 3, 6, 9, 12, 15, 18, 21, 24, 27, 30, 33, 36, 39, 42, 45, 50
        ]);
        $histogram->observe($responseTime);

        app('prometheus')->getOrRegisterCounter('', 'requests_served', 'Requests count')->inc();
        app('redis')->set($from->id, (string)$telegramUser);
    }
}
