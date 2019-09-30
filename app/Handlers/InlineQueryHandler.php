<?php

namespace App\Handlers;

class InlineQueryHandler extends Handler
{
    public function handle()
    {
        $inlineQuery = $this->getUpdate()->inlineQuery;
        $query = trim($inlineQuery->query);

        $response = $query
            ? $this->responseSearch($query)
            : $this->responseRandom();

        if (!$response) {
            return;
        }

        app('telegram')->answerInlineQuery([
            'inline_query_id' => $inlineQuery['id'],
            'results' => json_encode($response),
            'cache_time' => 0,
        ]);
    }

    protected function responseRandom(): array
    {
        $results = [];

        for ($i = 1; $i < 4; $i++) {
            $excuse = app('excuses')->getRandom();

            $results[] = [
                'type' => 'article',
                'id' => $i,
                'title' => 'Excuse ' . $i,
                'message_text' => $excuse,
                'description' => $excuse,
            ];
        }

        return $results;
    }

    protected function responseSearch(string $query): array
    {
        $results = [];

        foreach (app('excuses')->search($query) as $index => $item) {
            $results[] = [
                'type' => 'article',
                'id' => $index,
                'title' => 'Excuse ' . ($index + 1),
                'message_text' => $item['text'],
                'description' => $item['text'],
            ];
        }

        return $results;
    }
}
