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
    $app->patch('/user/{id_user:[0-9 ]+}/notification/{id_notification:[0-9 ]+}', '\Artist4all\Controller\NotificationController:notificationRead');
    $app->delete('/user/{id_user:[0-9 ]+}/notification/{id_notification:[0-9 ]+}', '\Artist4all\Controller\NotificationController:removeNotification');
  }

  public function getNotifications(Request $request, Response $response, array $args)
  {
    $id = $args['id'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id, $response);
    $notifications = \Artist4all\Model\Notification::getNotifications($user->getId());
    if (is_null($notifications)) $response = $response->withStatus(204, 'No notifications collected');
    else $response = $response->withJson($notifications);
    return $response;
  }


  public function notificationRead(Request $request, Response $response, array $args)
  {
    $id_user = $args['id_user'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $id_notification = $args['id_notification'];
    $notification = \Artist4all\Model\Notification::getNotificationById($id_notification);
    if (is_null($notification)) {
      $response = $response->withStatus(404, 'Notification not found');
      return $response;
    }
    $result = \Artist4all\Model\Notification::notificationRead($notification->getId());
    if (!$result) {
      $response = $response->withStatus(500, 'Error at updating notification status');
    } else {
      $response = $response->withStatus(204, 'Notification status updated');
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
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $id_notification = $args['id_notification'];
    $notification = \Artist4all\Model\Notification::getNotificationById($id_notification);
    if (is_null($notification)) {
      $response = $response->withStatus(404, 'Notification not found');
      return $response;
    }
    $result = \Artist4all\Model\Notification::removeNotification($notification->getId());
    if (!$result) $response = $response->withStatus(500, 'Error at removing notification');
    else $response = $response->withStatus(200, 'Notification removed');
    return $response;
  }
}
