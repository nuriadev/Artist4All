<?php
namespace Artist4all;
use Slim\Factory\AppFactory as AppFactory;

class Artist4all {
  public static function processRequest() {
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, PATCH, DELETE");

    $app = AppFactory::create();
    /* \Artist4all\Middleware\CorsMiddleware::corsHandler($app); 
    \Artist4all\Middleware\AuthMiddleware::authHandler($app); */
    \Artist4all\Controller\UserController::initRoutes($app);
    \Artist4all\Controller\PublicationController::initRoutes($app);
    \Artist4all\Controller\NotificationController::initRoutes($app);
    \Artist4all\Controller\CommentController::initRoutes($app);
    $app->run();
  }
}
