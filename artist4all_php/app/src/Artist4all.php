<?php

namespace Artist4all;
use Slim\Factory\AppFactory as AppFactory;

class Artist4all
{
  public static function processRequest()
  {
    $app = AppFactory::create();
    \Artist4all\Middleware\CorsMiddleware::corsHandler($app);
    \Artist4all\Middleware\AuthMiddleware::authHandler($app);
    \Artist4all\Controller\UserController::initRoutes($app);
    \Artist4all\Controller\PublicationController::initRoutes($app);
    \Artist4all\Controller\NotificationController::initRoutes($app);
    $app->run();
  }

  
}
