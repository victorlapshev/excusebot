<?php

namespace App\Handlers;

use App\TelegramUser;
use Illuminate\Support\Str;
use Telegram\Bot\Objects\Update;

class Factory
{
    /**
     * @var Update
     */
    private $update;
    /**
     * @var TelegramUser
     */
    private $user;

    public function __construct(Update $update, TelegramUser $user)
    {
        $this->update = $update;
        $this->user = $user;
    }

    public function build(): Handler
    {
        if ($this->update->inlineQuery) {
            return new InlineQueryHandler($this->update, $this->user);
        }

        if ($this->update->message && Str::startsWith($this->update->message->text, '/get')) {
            return new CommandGetHandler($this->update, $this->user);
        }

        return new DefaultHandler($this->update, $this->user);
    }
}
