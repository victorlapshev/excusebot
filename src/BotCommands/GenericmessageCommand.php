<?php

namespace App\BotCommands;

use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

class GenericmessageCommand extends \Longman\TelegramBot\Commands\SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'genericmessage';

    /**
     * @var string
     */
    protected $description = 'Handle generic message';

    /**
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     */
    public function execute(): ServerResponse
    {
        $messageText = $this->getMessage()->getText(true);

        return $this->replyToChat('Я не могу с тобой поговорить, я же бот!');
    }
}