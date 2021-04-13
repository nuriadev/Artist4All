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
    $app->patch('/user/{id_user:[0-9 ]+}/notification/{id:_notification:[0-9 ]+}', '\Artist4all\Controller\NotificationController:notificationRead');
    $app->delete('/user/{id_user:[0-9 ]+}/notification/{id:_notification:[0-9 ]+}', '\Artist4all\Controller\NotificationController:removeNotification');
  }

  public function getNotifications(Request $request, Response $response, array $args)
  {
    $id = $args['id'];
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

  public function notificationRead(Request $request, Response $response, array $args)
  {
    $id_user = $args['id_user'];
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $id_publication = $args['id_publication'];
    $publication = \Artist4all\Model\Notification::getNotificationById($id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    }
    $result = \Artist4all\Model\Notification::notificationRead($publication->getId());
    if(!$result) {
      $response = $response->withStatus(500, 'Error at updating publication status');
    } else {
      $response = $response->withStatus(204, 'Publication status updated');
    }
    return $response;
  }

  public static function createNotification($user_responsible, $user_receiver, $typeNotification, $response)
  {
    $notification = new \Artist4all\Model\Notification(null, $user_responsible, $user_receiver, null, 0, $typeNotification, '');
    $newNotification = \Artist4all\Model\Notification::insertNotification($notification);
    if (is_null($newNotification)) $response = $response->withStatus(500, 'Error at creating the notification');
    return $newNotification;
  }

  public function removeNotification(Request $request, Response $response, array $args) 
  {
    $id_user = $args['id_user'];
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $id_publication = $args['id_publication'];
    $publication = \Artist4all\Model\Notification::getNotificationById($id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    }
    $result = \Artist4all\Model\Notification::removeNotification($publication->getId());
    if(!$result) $response = $response->withStatus(500, 'Error at removing publication');
    else $response = $response->withStatus(200, 'Publication removed');
    return $response;
  }
}
