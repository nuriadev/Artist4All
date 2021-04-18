<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    $app->post('/register', '\Artist4all\Controller\UserController:register');
    $app->post('/login', '\Artist4all\Controller\UserController:login');
    $app->post('/logout', '\Artist4all\Controller\UserController:logout');
    // TODO: cambiar a patch los 2 edits
    $app->post('/user/{id:[0-9 ]+}/profile', '\Artist4all\Controller\UserController:editProfile');
    $app->post('/user/{id:[0-9 ]+}/password', '\Artist4all\Controller\UserController:changePassword');

    $app->get('/user/{id:[0-9 ]+}/list', '\Artist4all\Controller\UserController:getAllOtherUsers');
    $app->get('/user/{id:[0-9 ]+}', '\Artist4all\Controller\UserController:getUserById');

    $app->get('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}', '\Artist4all\Controller\UserController:isFollowingThatUser');
    $app->post('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}', '\Artist4all\Controller\UserController:requestOrFollowUser');
    // TODO: cambiar a patch 
    $app->post('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}/{id_follow:[0-9 ]+}', '\Artist4all\Controller\UserController:updateFollowRequest');

    $app->get('/user/{id:[0-9 ]+}/follower', '\Artist4all\Controller\UserController:getFollowers');
    $app->get('/user/{id:[0-9 ]+}/followed', '\Artist4all\Controller\UserController:getFollowed');
    // TODO: cambiar a patch 
    $app->post('/user/{id:[0-9 ]+}/settings/account/privacy', '\Artist4all\Controller\UserController:privateAccountSwitcher');
  }

  public function register(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $user = $this->validatePersist($data, null, $response);
    if (is_null($user)) {
      $response = $response->withStatus(500, 'Error on the register');
      return $response;
    }
    return $this->loginProcess($data, $response);
  }

  public function login(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    return $this->loginProcess($data, $response);
  }

  public function editProfile(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $user = static::getUserByIdSummary($id, $response);
    if (!isset($data['password'])) $data['password'] = $user->getPassword();
    if (!isset($data['isArtist'])) $data['isArtist'] = $user->isArtist();
    if (!isset($data['imgAvatar'])) $data['imgAvatar'] = $user->getImgAvatar();
    if (!isset($data['isPrivate'])) $data['isPrivate'] = $user->isPrivate();
    if (!isset($data['token'])) $data['token'] = $user->getToken();
    $user = $this->validatePersist($data, $id, $response);
    if (is_null($user)) {
      $response = $response->withStatus(500, 'Error at editing');
      return $response;
    }
    $session = new \Artist4all\Model\Session($user->getToken(), $user);
    $response = $response->withJson($session)->withStatus(200, 'User edited');
    return $response;
  }

  public function changePassword(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $user = static::getUserByIdSummary($id, $response);
    //TODO: si se puede adaptarlo a validatePersist
    $password = trim($data["password"]); // todo validar campos
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $result = \Artist4all\Model\User::changePassword($password_hashed, $id);
    if (!$result) {
      $response = $response->withStatus(500, 'Error on the password modification');
    } else {
      $session = new \Artist4all\Model\Session($user->getToken(), $user);
      $response = $response->withJson($session)->withStatus(200, 'Password changed');
    }
    return $response;
  }

  public function logout(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = $data['id'];
    $result = \Artist4all\Model\User::logout($id);
    if (!$result) $response = $response->withStatus(500, 'Error at closing session');
    else $response = $response->withStatus(204, 'Session closed');
    return $response;
  }

  public function getAllOtherUsers(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $users = \Artist4all\Model\User::getAllOtherUsers($id);
    if (is_null($users)) $response = $response->withStatus(204, 'Users not found');
    else $response = $response->withJson($users);
    return $response;
  }

  public function getUserById(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $user = static::getUserByIdSummary($id, $response);
    $response = $response->withJson($user);
    return $response;
  }

  public static function getUserByIdSummary(int $id, Response $response) {
    $user = \Artist4all\Model\User::getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      return $user;
    }
  }

  public static function getUserByTokenSummary(Request $request, Response $response) {
    $token = $request->getHeader('Authorization');
    $token = trim($token[0]);
    $token = trim(substr($token, 6));
    $user = \Artist4all\Model\User::getUserByToken($token);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      return $user;
    }
  }

  public function isFollowingThatUser(Request $request, Response $response, array $args) {
    $id_follower = $args['id_follower'];
    $id_followed = $args['id_followed'];
    $follower = static::getUserByIdSummary($id_follower, $response);
    $followed = static::getUserByIdSummary($id_followed, $response);
    $result = \Artist4all\Model\User::isFollowingThatUser($follower, $followed);
    if (is_null($result)) $response = $response->withStatus(204, 'User not followed');
    else $response = $response->withJson($result)->withStatus(200, 'User followed');
    return $response;
  }

  public function requestOrFollowUser(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    if (!isset($data['id_follow'])) $id = null;
    else $id = $data['id_follow'];
    return $this->persistFollow($args, $data, $id, $response);
  }

  // TODO: una vez cambiado a patch, adaptar la function
  public function updateFollowRequest(Request $request, Response $response, array $args) {
    $id = $args['id_follow'];
    $data = $request->getParsedBody();
    return $this->persistFollow($args, $data, $id, $response);
  }

  private function persistFollow($args, $data, $id, $response) {
    $id_follower = $args['id_follower'];
    $id_followed = $args['id_followed'];
    $follower = static::getUserByIdSummary($id_follower, $response);
    $followed = static::getUserByIdSummary($id_followed, $response);
    $logFollow = [
      'id' => $id,
      'id_follower' => $follower->getId(),
      'id_followed' => $followed->getId(),
      'status_follow' => (int) $data['status_follow']
    ];
    $logFollow = \Artist4all\Model\User::persistFollow($logFollow);
    if (is_null($logFollow)) {
      $response = $response->withStatus(500);
    } else {
      if ($logFollow['status_follow'] == 2) {
        if (!\Artist4all\Model\Notification::existsNotification($follower->getId(), $followed->getId(), 2)) {
          \Artist4all\Controller\NotificationController::createNotification($follower, $followed, 2, $response);
        }
      } else if ($logFollow['status_follow'] == 3 && $followed->isPrivate()) {
        \Artist4all\Controller\NotificationController::createNotification($follower, $followed, 1, $response);
        \Artist4all\Controller\NotificationController::createNotification($followed, $follower, 3, $response);
      } else if ($logFollow['status_follow'] == 3 && !$followed->isPrivate()) {
        if (!\Artist4all\Model\Notification::existsNotification($follower->getId(), $followed->getId(), 1)) {
          \Artist4all\Controller\NotificationController::createNotification($follower, $followed, 1, $response);
        }
      }
      $response = $response->withJson($logFollow);
    }
    return $response;
  }

  public function getFollowers(Request $request, Response $response, array $args) {
    return $this->getFollowersOrFollowed($args, 'followers', $response);
  }

  public function getFollowed(Request $request, Response $response, array $args) {
    return $this->getFollowersOrFollowed($args, 'followed', $response);
  }

  public function privateAccountSwitcher(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $user = static::getUserByIdSummary($id, $response);
    $isPrivate = $data['isPrivate'];
    $result = \Artist4all\Model\User::privateAccountSwitcher($isPrivate, $user->getId());
    if (!$result) {
      $response = $response->withStatus(400, 'Error at switching');
    } else {
      $user->setIsPrivate($isPrivate);
      $session = new \Artist4all\Model\Session($user->getToken(), $user);
      $response = $response->withJson($session)->withStatus(200, 'Switched');
    }
    return $response;
  }

  private function getFollowersOrFollowed(array $args, string $followedOrFollowing, Response $response) {
    $id = $args['id'];
    $user = static::getUserByIdSummary($id, $response);
    if ($followedOrFollowing == 'followers') $users = \Artist4all\Model\User::getFollowers($user->getId());
    else $users = \Artist4all\Model\User::getFollowed($user->getId());
    if (empty($users)) $response = $response->withStatus(204, 'No users collected');
    else $response = $response->withJson($users);
    return $response;
  }

  private function loginProcess(array $data, Response $response) {
    // todo validacion email y password
    $email = trim($data["email"]);
    $password = trim($data["password"]);
    $user = \Artist4all\Model\User::getUserByEmail($email);
    if (is_null($user)) {
      $response = $response->withStatus(400, 'Unvalid user');
      return $response;
    }
    if (password_verify($password, $user->getPassword())) {
      $arrayAux = array($user->getEmail(), $user->getPassword(), \Artist4all\Model\Session::randomTokenPart());
      $content = implode(".", $arrayAux);
      // creamos el token a partir de la variable $content
      $token = \Artist4all\Model\Session::tokenGenerator($content);
      $userWithToken = \Artist4all\Model\User::insertOrUpdateToken($token, $user);
      $session = new \Artist4all\Model\Session($token, $user);
      $response = $response->withJson($session)->withStatus(200, 'Session started');
    } else {
      $response = $response->withStatus(400, 'Unvalid user');
    }
    return $response;
  }

  private function validatePersist($data, $id, $response) {

    // todo: si está dado de baja para volver a activar su acc según el email if / else
    // todo: crear función para reactivarCuenta 

    // todo validación completa de un usuario menos del img avatar 
    $name = $data['name'];
    $surname1 = $data['surname1'];
    $surname2 = $data['surname2'];
    $email = $data['email'];
    $username = $data['username'];
    $password = $data['password'];
    $isArtist = $data['isArtist'];
    $aboutMe = $data['aboutMe'];
    $isPrivate = $data['isPrivate'];
    // todo comprueba si existe un usuario según el email y el username 

    if (is_null($id)) {
      $data['token'] = '';
    } else {
      $token = trim($data['token']);
      $folderUrl = "assets/img" . DIRECTORY_SEPARATOR;
      //TODO:Mover la img pasada a una carpeta interna 'assets', limitar el tamaño y el formato de la img
      foreach ($_FILES as $file) {
        $nombreImg = $file["tmp_name"];
        $urlImg = $folderUrl . $file["name"];
        move_uploaded_file($nombreImg, $urlImg);
      }
      $currentImgAvatar = $data['imgAvatar'];
      if (isset($urlImg)) $data['imgAvatar'] = 'http://localhost:81/' . $urlImg;
      else $data['imgAvatar'] = $currentImgAvatar;
    }
    $data['id'] = $id;
    $user = \Artist4all\Model\User::fromAssoc($data);
    $user = \Artist4all\Model\User::persistUser($user);
    return $user;
  }
}
