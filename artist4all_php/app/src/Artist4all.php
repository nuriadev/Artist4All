<?php

namespace Artist4all;

use Slim\Factory\AppFactory as AppFactory;
use Slim\Psr7\Response as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Server\RequestHandlerInterface as RequestHandler;

class Artist4all
{
  public static function processRequest()
  {
    // CORS
    header('Access-Control-Allow-Origin: *');
    header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Authorization, Access-Control-Request-Method");
    header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, PATCH, DELETE");
    header("Allow: GET, POST, OPTIONS, PUT, PATCH, DELETE");

    $app = AppFactory::create();
    \Artist4all\Controller\UserController::initRoutes($app);
    \Artist4all\Controller\PublicationController::initRoutes($app);
    \Artist4all\Controller\NotificationController::initRoutes($app);


    $authMiddleware = function ($request, $handler) {
      // TODO: ARREGLAR MIDDLEWARE
      $id_user = 1;
      if (is_null($id_user)) {
        $response = new Response();
        return $response->withStatus(401);
      } 
      $response = $handler->handle($request);
      return $response;
    };

    $app->add($authMiddleware);
    $app->run();
  }

  private static function checkToken(Request $request): ?int
  {
    $token = $request->getHeader('Authorization');
    if (empty($token)) return null;
    $token = trim($token[0]);
    \Artist4all\Model\Session::verifyToken($token);
    $id_user = \Artist4all\Model\User::getIdByToken($token);
    return $id_user;
  }
}
