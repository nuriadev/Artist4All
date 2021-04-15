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
<<<<<<< HEAD

    $authMiddleware = function ($request, $handler) {
      $id_user = 1;
      if (is_null($id_user)) {
        $response = new Response();
        return $response->withStatus(401);
      } 
      $response = $handler->handle($request);
      return $response;
    };
    $app->add($authMiddleware);
=======
>>>>>>> 9e0671851d3ee89d656d132951adfd8c752762c5
    $app->run();
  }

  
}
