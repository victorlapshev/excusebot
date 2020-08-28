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
        $doctrine = $this->telegram->getContainer()->get('doctrine');

        /** @var Excuse[] $excuses */
        $excuses = $doctrine->getRepository(Excuse::class)->findByText($text);

        $reply = '';
        foreach ($excuses as $excuse) {
            $reply .= $excuse->getText() . PHP_EOL;
        }

        return $this->replyToChat($reply);
    }
}