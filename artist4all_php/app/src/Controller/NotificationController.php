<?php

namespace Artist4all\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotificationController
{
  public static function initRoutes($app)
  {
    // TODO: GET all my notifications, delete notifications
    $app->get('/user/{id:[0-9 ]+}/notification', '\Artist4all\Controller\NotificationController:getNotifications');
  }

  public function getNotifications(Request $request, Response $response, array $args)
  {
    $id = $args['id'];
    $authorized = $this->isAuthorizated($request, $response);
    $user = \Artist4all\Model\User::getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $notifications = \Artist4all\Model\Notification::getNotifications($user->getId());
    if (is_null($notifications)) $response = $response->withStatus(204, 'No notifications collected');
    else $response = $response->withJson($notifications);
    return $response;
  }

  private function isAuthorizated(Request $request, Response $response)
  {
    $token = trim($request->getHeader('Authorization')[0]);
    $result = \Artist4all\Model\User::isValidToken($token);
    if (!$result) {
      $response = $response->withStatus(401, 'Unauthorized user');
      return $response;
    } else {
      return $result;
    }
  }
}
