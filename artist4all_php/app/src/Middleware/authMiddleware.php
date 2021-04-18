<?php
namespace Artist4all\Middleware;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AuthMiddleware {
  public static function authHandler($app)
  {
    $authMiddleware = (function ($request, $handler) {
      $route = $request->getUri()->getPath();
      $publicRoutes = array(
        '/login',
        '/register',
      );
      if (in_array($route, $publicRoutes)) {
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
    }); 
    $app->add($authMiddleware);
  }

  public static function checkToken(Request $request): ?int
  {
    $token = $request->getHeader('Authorization');
    if (empty($token)) return null;
    $token = trim($token[0]);
    if (strpos($token, "Bearer ") !== 0) return null;
    $token = trim(substr($token, 6)); 
    \Artist4all\Model\Session::verifyToken($token);
    $id_user = \Artist4all\Model\User::getIdByToken($token);
    return $id_user;
  }
}