<?php declare(strict_types=1);

use Slim\App;

return function (App $app) {

    $app->get('/', \App\Handler\HomePageHandler::class)
        ->setName('home');

    $app->get('/fixtures', \App\Handler\FixturesPageHandler::class)
        ->setName('fixtures');

};
