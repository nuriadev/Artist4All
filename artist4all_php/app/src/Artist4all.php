<?php
namespace Artist4all;

use Slim\Factory\AppFactory;

class Artist4all {
  public static function processRequest() {
    $app = AppFactory::create();
    \Artist4all\Controller\UserController::initRoutes($app);
    $app->run();
  }
}