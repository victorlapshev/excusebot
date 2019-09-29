<?php

namespace App\Handlers;

use App\TelegramUser;
use Telegram\Bot\Objects\Update;

abstract class Handler
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

    abstract public function handle();

    /**
     * @return Update
     */
    public function getUpdate(): Update
    {
        return $this->update;
    }

    /**
     * @return TelegramUser
     */
    public function getUser(): TelegramUser
    {
        return $this->user;
    }
}
