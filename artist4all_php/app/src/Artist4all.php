<?php

namespace Artist4all;

use Slim\Factory\AppFactory as AppFactory;
use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;
use Slim\Exception\NotFoundException;


class Artist4all
{
  public static function processRequest()
  {
  
    $app = AppFactory::create();
    \Artist4all\Middleware\corsMiddleware::corsHandler($app);
    \Artist4all\Controller\UserController::initRoutes($app);
    \Artist4all\Controller\PublicationController::initRoutes($app);
    \Artist4all\Controller\NotificationController::initRoutes($app);

   /*  $app->add(function ($request, $handler) {
      $route = $request->getAttribute('route');
      $publicRoutes = array(
        '/login',
        '/register',
      );
      $name = $route->getName();
      if (!in_array($name, $publicRoutes)) {
        $response = $handler->handle($request);
        return $response;
      } else {
        $user_id = static::checkToken($request);
        if (is_null($user_id)) {
          $response = new Response();
          return $response->withStatus(401);
        } else {
          $response = $handler->handle($request);
          return $response;
        }
      }
    }); */
    $app->run();
  }

  public static function checkToken(Request $request): ?int
  {
    $token = $request->getHeader('Authorization');
    if (empty($token)) return null;
    $token = trim($token[0]);
    \Artist4all\Model\Session::verifyToken($token);
    $id_user = \Artist4all\Model\User::getIdByToken($token);
    return $id_user;
  }
}
