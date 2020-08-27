<?php

namespace App\BotCommands;

use App\Entity\Excuse;
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
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $doctrine = $this->telegram->getContainer()->get('doctrine');

        /** @var Excuse $excuse */
        $excuse = $doctrine->getRepository(Excuse::class)->findRandom();

        return $this->replyToChat($excuse->getText());
    }
}