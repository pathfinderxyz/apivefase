<?php
Header ('Access-Control-Allow-Origin *');
Header ('Access-Control-Allow-Credentials: true');
Header ('Access-Control-Allow-Methods: POST, GET, OPTIONS, DELETE, PUT');
Header ('Access-Control-Max-Age: 1000');
Header ('Access-Control-Allow-Headers:x-requested-with, Content-Type, origin, authorization, accept, client-security-token');

use Psr\Http\Message\ServerRequestInterface;
use Slim\Factory\AppFactory;
use Slim\Exception\HttpNotFoundException;
use Slim\Exception\HttpMethodNotAllowedException;
use Slim\Psr7\Response;

try {
require __DIR__. '/../vendor/autoload.php';
require __DIR__. '/../config/db.php';
$app = AppFactory::create();
$app->addRoutingMiddleware();
$app->addBodyParsingMiddleware();
$app->setBasePath("/public");
/* $app->addErrorMiddleware(true, true, true); */

require __DIR__.'/../routes/routes.php';

// Run app
$app->run();
} catch (\Throwable $th) {
   echo $th;
}





     


