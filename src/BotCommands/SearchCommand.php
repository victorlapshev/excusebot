<?php

namespace App\BotCommands;

use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class SearchCommand extends SystemCommand
{
    protected $name = 'search';

    protected $usage = '/search <text>';

    /**
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $text = $this->getMessage()->getText(true);

        return $this->replyToChat('Ты искал: ' . $text);
    }
}