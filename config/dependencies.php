<?php declare(strict_types=1);

use App\ResultParser;
use DI\ContainerBuilder;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Monolog\Processor\UidProcessor;
use Psr\Container\ContainerInterface;
use Psr\Log\LoggerInterface;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

return static function (ContainerBuilder $containerBuilder, array $settings) {
    $containerBuilder->addDefinitions([
        'settings' => $settings,

        LoggerInterface::class => function (ContainerInterface $c): Logger {
            $settings = $c->get('settings');

            $loggerSettings = $settings['logger'];
            $logger = new Logger($loggerSettings['name']);

            $processor = new UidProcessor();
            $logger->pushProcessor($processor);

            $handler = new StreamHandler($loggerSettings['path'], $loggerSettings['level']);
            $logger->pushHandler($handler);

            return $logger;
        },

        Environment::class => function (ContainerInterface $c) use ($settings): Environment {
            $loader = new FilesystemLoader(__DIR__ . '/../view');
            $twig = new Environment($loader, [
                __DIR__ . '/../var/cache'

            ]);
            if ($settings['app_env'] === 'DEVELOPMENT') {
                $twig->enableDebug();
                $twig->addExtension(new \Twig\Extension\DebugExtension());
            }

            $twig->addGlobal('resultParser', new ResultParser());

//            $filter = new \Twig\TwigFilter('urlFor', function(string $routeName) use ($c): string {
//                return $c->get(\Slim\App::class)->urlFor($routeName);
//            });
//            $twig->addFilter($filter);

            return $twig;
        }
    ]);
};
