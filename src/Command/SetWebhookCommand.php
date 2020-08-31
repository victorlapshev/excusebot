<?php

namespace App\Command;

use App\TelegramBot\Telegram;
use Longman\TelegramBot\Exception\TelegramException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SetWebhookCommand extends Command
{
    protected static $defaultName = 'telegram:set-webhook';

    /**
     * @var Telegram
     */
    private $telegram;

    public function __construct(Telegram $telegram)
    {
        parent::__construct();

        $this->telegram = $telegram;
    }

    protected function configure()
    {
        $this
            ->setDescription('Setup webhook for telegram bot')
            ->addArgument('path', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        try {
            // Set webhook
            $result = $this->telegram->setWebhook($input->getArgument('path'));
            if ($result->isOk()) {
                $output->writeln($result->getDescription());
            }
        } catch (TelegramException $e) {
            $output->writeln($e->getMessage());

            return Command::FAILURE;
        }

        return Command::SUCCESS;
    }
}
