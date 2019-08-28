<?php


namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Telegram\Bot\Api;
use Telegram\Bot\Exceptions\TelegramSDKException;

class ManageWebhookCommand extends Command
{
    protected $signature = 'telegram:webhook {action} {argument?}';

    /** @var Api */
    protected $telegram;

    /**
     * @throws TelegramSDKException
     */
    public function handle()
    {
        $action = $this->input->getArgument('action');

        $this->telegram = app('telegram');

        switch ($action) {
            case 'setup':
                $res = $this->telegram->setWebhook(['url' => $this->input->getArgument('argument')]);
                break;
            case 'delete':
                $res = $this->telegram->deleteWebhook();
                break;
            default:
                throw new InvalidArgumentException("Wrong action: {$action}");
        }

        if ($res) {
            $this->output->success('Done');
        }
    }
}
