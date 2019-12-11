<?php declare(strict_types=1);

use Slim\App;

return function (App $app) {

    $app->get('/', \App\Handler\HomePageHandler::class)
        ->setName('home');

    $app->get('/fixtures', \App\Handler\FixturesPageHandler::class)
        ->setName('fixtures');

    $app->get('/about', \App\Handler\AboutPageHandler::class)
        ->setName('about');

    $app->get('/contact', \App\Handler\ContactPageHandler::class)
        ->setName('contact');

};
