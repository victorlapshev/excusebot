<?php

namespace App\BotCommands;

use App\Excuse\Random;
use App\TelegramBot\Telegram;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Exception\TelegramException;

/**
 * @property Telegram $telegram
 */
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
     * Main command execution.
     *
     * @throws TelegramException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function execute(): ServerResponse
    {
        $messageText = $this->getMessage()->getText(true);

        /** @var Random $random */
        $random = $this->telegram->getContainer()->get(Random::class);

        return $this->replyToChat($random->get());
    }
}
