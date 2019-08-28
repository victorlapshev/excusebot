<?php

namespace App\Events;

use Telegram\Bot\Objects\Update;

class TelegramUpdatesEvent extends Event
{
    /**
     * @var Update
     */
    private $updates;

    /**
     * Create a new event instance.
     *
     * @param Update $updates
     */
    public function __construct(Update $updates)
    {
        $this->updates = $updates;
    }

    /**
     * @return Update
     */
    public function getUpdates(): Update
    {
        return $this->updates;
    }
}
