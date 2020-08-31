<?php

namespace App\BotCommands;

use App\Excuse\Random;
use App\TelegramBot\Telegram;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @property Telegram $telegram
 */
class RandomCommand extends SystemCommand
{
    protected $name = 'random';

    protected $usage = '/random';

    /**
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        /** @var Random $random */
        $random = $this->telegram->getContainer()->get(Random::class);

        return $this->replyToChat($random->get());
    }
}
