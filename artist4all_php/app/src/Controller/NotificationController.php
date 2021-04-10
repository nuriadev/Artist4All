<?php
namespace Artist4all\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class NotificationController {
  public static function initRoutes($app) {
    // TODO: GET all my notifications, delete notifications
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/notification', '\Artist4all\Controller\NotificationController:getMyNotifications');
  }

  public function getMyNotifications(Request $request, Response $response, array $args) {
    $authorized = $this->isAuthorizated($request, $response);
    $username = $args['username'];
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) {
        $response = $response->withStatus(404, 'User not found');
        return $response;
    }
    $notifications = \Artist4all\Model\Notification\NotificationDB::getInstance()->getMyNotifications($user->getId());
    if (is_null($notifications)) $response = $response->withStatus(200, 'No notifications collected');
    else $response = $response->withJson($notifications);
    return $response;
  }

  private function isAuthorizated(Request $request, Response $response) {  
    $token = trim($request->getHeader('Authorization')[0]);
    $result = \Artist4all\Model\User\UserDB::getInstance()->isValidToken($token);
    if (!$result) {
      $response = $response->withStatus(401, 'Unauthorized user');
      return $response;
    } else {
      return $result;
    }
  }
}
