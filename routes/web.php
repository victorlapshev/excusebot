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
use Prometheus\RenderTextFormat;

$router->get('/', function () use ($router) {
    return app('excuses')->getRandom();
});

$router->post('/webhook', function () use ($router) {
    $updates = app('telegram')->getWebhookUpdates();

    event(new TelegramUpdatesEvent($updates));
});

$router->get('/metrics', function () use ($router) {
    $result = (new RenderTextFormat)->render(app('prometheus')->getMetricFamilySamples());

    return response($result, 200)->header('Content-Type', RenderTextFormat::MIME_TYPE);
});
