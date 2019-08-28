<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

/**
 * @var $router Router
 */

use App\Events\TelegramUpdatesEvent;
use Laravel\Lumen\Routing\Router;

$router->get('/', function () use ($router) {
    return app('excuses')->getRandom();
});

$router->post('/webhook', function () use ($router) {
    $updates = app('telegram')->getWebhookUpdates();

    event(new TelegramUpdatesEvent($updates));
});
