<?php

namespace App\Handlers;

class CommandGetHandler extends Handler
{
    public function handle()
    {
        $message = $this->getUpdate()->message;

        if (!preg_match('/^\/get\s?(.*$)/u', $message->text, $matches)) {
            return;
        }
        list(, $query) = $matches;

        if ($query) {
            $response = '';
            foreach (app('excuses')->search($query) as $item) {
                $response .= $item['text'] . PHP_EOL . PHP_EOL;
            }

            app('telegram')->sendMessage([
                'chat_id' => $message->chat->id,
                'text' => strlen($response) > 0 ? $response : 'Ничего не найдено'
            ]);
        } else {
            app('telegram')->sendMessage([
                'chat_id' => $message->chat->id,
                'text' => app('excuses')->getRandom()
            ]);
        }
    }
}
