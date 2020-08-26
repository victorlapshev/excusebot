<?php

namespace App\Controller;

use Longman\TelegramBot\Telegram;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Response;

class WebhookController
{
    public function handle(Telegram $telegram, LoggerInterface $logger): Response
    {
        try {
            $telegram->addCommandsPath(__DIR__ . '/../BotCommands');
            $telegram->handle();

            $logger->debug('Webhook request processed:');
        } catch (\Longman\TelegramBot\Exception\TelegramException $e) {
            $logger->error($e->getMessage());
        }

        return new Response('ok');
    }
}