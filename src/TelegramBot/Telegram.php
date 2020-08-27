<?php

namespace App\TelegramBot;

use Symfony\Component\DependencyInjection\ContainerInterface;

class Telegram extends \Longman\TelegramBot\Telegram
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct($api_key, $bot_username = '', ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct($api_key, $bot_username);
    }

    /**
     * @return ContainerInterface
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}