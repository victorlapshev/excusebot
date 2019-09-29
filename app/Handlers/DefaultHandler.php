<?php

namespace App\Handlers;

use Illuminate\Support\Facades\Log;

class DefaultHandler extends Handler
{
    public function handle()
    {
        Log::info('Unrecognized command or message received', [
            'update' => $this->getUpdate(),
            'user' => $this->getUser(),
        ]);
    }
}
