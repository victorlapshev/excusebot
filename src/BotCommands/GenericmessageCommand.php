<?php

namespace App\BotCommands;

use App\Entity\Excuse;
use App\Repository\ExcuseRepository;
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
     * Main command execution
     *
     * @return ServerResponse
     * @throws TelegramException
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function execute(): ServerResponse
    {
        $messageText = $this->getMessage()->getText(true);

        /** @var ExcuseRepository $repo */
        $repo = $this->telegram->getContainer()->get('doctrine')->getRepository(Excuse::class);

        return $this->replyToChat($repo->findRandomOne()->getText());
    }
}