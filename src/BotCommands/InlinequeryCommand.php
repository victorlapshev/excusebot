<?php

namespace App\BotCommands;

use App\Entity\Excuse;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Entities\ServerResponse;

class InlinequeryCommand extends SystemCommand
{
    /**
     * @var string
     */
    protected $name = 'inlinequery';

    /**
     * @var string
     */
    protected $description = 'Handle inline query';

    /**
     * Main command execution
     *
     * @return ServerResponse
     */
    public function execute(): ServerResponse
    {
        $inlineQuery = $this->getInlineQuery();
        $query = $inlineQuery->getQuery();
        $repo = $this->telegram->getContainer()->get('doctrine')->getRepository(Excuse::class);

        /** @var Excuse[] $excuses */
        $excuses = empty($query) ? $repo->findRandom() : $repo->findByText($query);

        $results = [];

        foreach ($excuses as $excuse) {
            $results[] = new InlineQueryResultArticle([
                'id' => $excuse->getId(),
                'title' => 'Отмазка ' . $excuse->getId(),
                'description' => $excuse->getText(),

                'input_message_content' => new InputTextMessageContent([
                    'message_text' => $excuse->getText(),
                ]),
            ]);
        }

        return $inlineQuery->answer($results, ['cache_time' => 0,]);
    }
}