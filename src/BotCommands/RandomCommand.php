<?php

namespace App\BotCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

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
        return $this->replyToChat('Пока не очень случайная отмазка');
    }
}