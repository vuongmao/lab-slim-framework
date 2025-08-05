<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\RequestInterface as Request;
use Slim\Factory\AppFactory;
use DI\Container;

require __DIR__ . '/../vendor/autoload.php';
define('DATA_DIR', __DIR__ . '/../data');

$container = new Container();

$container->set('templating', function(){
  return new Mustache\Engine([
    'loader' => new Mustache\Loader\FilesystemLoader(
      __DIR__ . '/../templates', 
      ['extentions' => '']),
  ]);
});
// Register globally to app
$container->set('session', function () {
  return new \SlimSession\Helper();
});

AppFactory::setContainer($container);

$app = AppFactory::create();
$app->add(new \Slim\Middleware\Session);

//Album project
$app->get("/album", "\App\Controller\Album\SearchController:default");
$app->get("/album/search", "\App\Controller\Album\SearchController:search");
$app->any("/album/form", "\App\Controller\Album\SearchController:form");
$app->get("/album/api", "\App\Controller\Album\ApiController:api");

//Bike project
$app->get("/bike", "\App\Controller\Bike\ShopController:default");
$app->get("/bike/detail/{id:[0-9]+}", "\App\Controller\Bike\ShopController:detail");
$app->any("/bike/login", "\App\Controller\Bike\AuthController:login");
$app->get("/bike/blog", "\App\Controller\Bike\AuthController:blog")->add(new \App\Middleware\Authenticate($app->getContainer()->get('session')));
$app->get("/bike/logout", "\App\Controller\Bike\AuthController:logout");

// Error handling
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setErrorHandler(
  Slim\Exception\HttpNotFoundException::class, 
  function(Psr\Http\Message\ServerRequestInterface $request) use ($container) {
    $controller = new App\Controller\ExceptionController($container);
    return $controller->notFound($request);
  }
);

$app->run();