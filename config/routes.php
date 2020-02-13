<?php declare(strict_types=1);

use Slim\App;

return function (App $app) {

    $app->get('/', \App\Handler\HomePageHandler::class)
        ->setName('home');

    $app->get('/first-team', \App\Handler\FirstTeamPageHandler::class)
        ->setName('first-team');

    $app->get('/news', \App\Handler\NewsPageHandler::class)
        ->setName('news');

    $app->get('/contact', \App\Handler\ContactPageHandler::class)
        ->setName('contact');

};
