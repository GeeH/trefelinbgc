<?php declare(strict_types=1);

use Slim\App;

return function (App $app) {

    $app->get('/', \App\Handler\HomePageHandler::class)
        ->setName('home');

    $app->get('/first-team', \App\Handler\FirstTeamPageHandler::class)
        ->setName('first-team');

    $app->get('/reserve-team', \App\Handler\ReserveTeamHandler::class)
        ->setName('reserve-team');

    $app->get('/youth-team', \App\Handler\YouthTeamHandler::class)
        ->setName('youth-team');

    $app->get('/junior-team', \App\Handler\JuniorTeamHandler::class)
        ->setName('junior-team');

    $app->get('/news', \App\Handler\NewsPageHandler::class)
        ->setName('news');

    $app->get('/contact', \App\Handler\ContactPageHandler::class)
        ->setName('contact');

};
