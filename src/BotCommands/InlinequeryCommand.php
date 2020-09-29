<?php

namespace App\BotCommands;

use App\Entity\Excuse;
use Elastica\Result;
use Longman\TelegramBot\Commands\SystemCommand;
use Longman\TelegramBot\Entities\InlineQuery\InlineQueryResultArticle;
use Longman\TelegramBot\Entities\InputMessageContent\InputTextMessageContent;
use Longman\TelegramBot\Entities\ServerResponse;
use Longman\TelegramBot\Entities\Update;
use Longman\TelegramBot\Telegram;

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

    private $repo;

    public function __construct(Telegram $telegram, Update $update = null)
    {
        parent::__construct($telegram, $update);

        $this->repo = $this->telegram->getContainer()->get('doctrine')->getRepository(Excuse::class);
    }

    /**
     * Main command execution.
     */
    public function execute(): ServerResponse
    {
        $inlineQuery = $this->getInlineQuery();
        $query = $inlineQuery->getQuery();

        $excuses = empty($query) ? $this->findRandom() : $this->findByText($query);

        $results = [];
        $i = 0;

        foreach ($excuses as $excuse) {
            $i++;
            $results[] = new InlineQueryResultArticle([
                'id' => $i,
                'title' => 'Отмазка ' . $i,
                'description' => $excuse['title'],
                'parse_mode' => 'html',

                'input_message_content' => new InputTextMessageContent([
                    'message_text' => $excuse['text'],
                    'parse_mode' => 'html',
                ]),
            ]);
        }

        return $inlineQuery->answer($results, ['cache_time' => 0, 'parse_mode' => 'html']);
    }

    protected function findRandom(): array
    {
        /** @var Excuse[] $excuses */
        $excuses = $this->repo->findRandom();

        $results = [];

        foreach ($excuses as $excuse) {
            $results[] = [
                'title' => $excuse->getText(),
                'text' => $excuse->getText()
            ];
        }

        return $results;
    }

    protected function findByText(string $text): array
    {
        /** @var Result[] $excuses */
        $excuses = $this->repo->searchIndex($text);

        $results = [];

        foreach ($excuses as $excuse) {
            $results[] = [
                'title' => $excuse->getHighlights()['text'][0],
                'text'  => $excuse->getSource()['text'],
            ];
        }

        return $results;
    }
}
