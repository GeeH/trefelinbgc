<?php declare(strict_types=1);

namespace App\Handler;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Psr\Log\LoggerInterface;
use Slim\Psr7\Response;
use Twig\Environment;

require_once(__DIR__ . '/../functions.php');

class HomePageHandler implements RequestHandlerInterface
{
    private $logger;

    private $twig;

    public function __construct(LoggerInterface $logger, Environment $twig)
    {
        $this->logger = $logger;
        $this->twig = $twig;
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {


        $this->logger->info('Home page handler dispatched');
        $response = new Response();
        $response->getBody()->write(
            $this->twig->render('home.twig', getFixtures())
        );

        return $response;
    }
}
