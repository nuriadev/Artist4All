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
    $user = $this->validatePersist($request, $data, null, $response);
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
    if (!empty($_FILES) || $_FILES != null) {
      $allowed = array('image/gif', 'image/png', 'image/jpg', 'image/jpeg');
      foreach($_FILES as $file) {
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        if (!in_array(finfo_file($finfo, $file['tmp_name']), $allowed)) {
          $response = $response->withStatus(400, 'Unvalid image extension');
          return $response;
        }     
        finfo_close($finfo);
      }         
    }
    if (!isset($data['password'])) $data['password'] = $user->getPassword();
    if (!isset($data['isArtist'])) $data['isArtist'] = $user->isArtist();
    if (!isset($data['imgAvatar'])) $data['imgAvatar'] = $user->getImgAvatar();   
    if (!isset($data['token'])) $data['token'] = $user->getToken();
    if (!isset($data['isPrivate'])) $data['isPrivate'] = $user->isPrivate();
    $user = $this->validatePersist($request, $data, $id, $response);
    $session = new \Artist4all\Model\Session($user->getToken(), $user);
    $response = $response->withJson($session)->withStatus(200, 'User edited');
    return $response;
  }

  public function changePassword(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $user = static::getUserByIdSummary($id, $response);
    $password = trim($data["password"]); 
    $data["password"] = password_hash($password, PASSWORD_DEFAULT);
    if (!isset($data['name'])) $data['name'] = $user->getName();
    if (!isset($data['surname1'])) $data['surname1'] = $user->getSurname1();    
    if (!isset($data['surname2'])) $data['surname2'] = $user->getSurname2();
    if (!isset($data['email'])) $data['email'] = $user->getEmail();    
    if (!isset($data['username'])) $data['username'] = $user->getUsername();
    if (!isset($data['isArtist'])) $data['isArtist'] = $user->isArtist();
    if (!isset($data['imgAvatar'])) $data['imgAvatar'] = $user->getImgAvatar();  
    if (!isset($data['aboutMe'])) $data['aboutMe'] = $user->getAboutMe(); 
    if (!isset($data['token'])) $data['token'] = $user->getToken();   
    if (!isset($data['isPrivate'])) $data['isPrivate'] = $user->isPrivate();
    $user = $this->validatePersist($request, $data, $id, $response);
    $session = new \Artist4all\Model\Session($user->getToken(), $user);
    $response = $response->withJson($session)->withStatus(200, 'Password changed');
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
    $booleanPattern = '^[0-1]$';
    $this->validateVariable($isPrivate, $booleanPattern, 'Wrong isPrivate format', 1, $response);
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
    foreach(['email', 'password'] as $key) {
      if (!isset($data[$key])) {
        $response = $response->withStatus(400, 'Unvalid user');
        return $response;
      }
      if (empty($data[$key])) {
        $response = $response->withStatus(400, 'Unvalid user');
        return $response;
      }
    }
    
    $email = trim($data["email"]);
    $this->validateVariable($email, null, 'Wrong email format', 0, $response);

    $password = trim($data['password']);
    // TODO: CAMBIAR AL FINAL DEL PROYECTO
    $passwordPattern = '^[A-Za-z0-9 ]+^';
    $this->validateVariable($password, $passwordPattern, 'Unvalid user', 1, $response);

    $user = \Artist4all\Model\User::getUserByEmail($email, 0);
    if (is_null($user)) {
      $response = $response->withStatus(400, 'Unvalid user');
      return $response;
    }
    if (password_verify($password, $user->getPassword())) {
      $arrayAux = array($user->getEmail(), $user->getPassword(), \Artist4all\Model\Session::randomTokenPart());
      $content = implode(".", $arrayAux);
      $token = \Artist4all\Model\Session::tokenGenerator($content);
      $userWithToken = \Artist4all\Model\User::insertOrUpdateToken($token, $user);
      $session = new \Artist4all\Model\Session($token, $user);
      $response = $response->withJson($session)->withStatus(200, 'Session started');
    } else {
      $response = $response->withStatus(400, 'Unvalid user');
    }
    return $response;
  }

  private function validatePersist($request, $data, $id, $response) { 
    $name = trim($data['name']);
    $surname1 = trim($data['surname1']);
    $surname2 = trim($data['surname2']);
    $email = trim($data['email']);
    $username = trim($data['username']);
    $password = trim($data['password']);
    $aboutMe = trim($data['aboutMe']);
    $isArtist = trim($data['isArtist']);
    $isPrivate = trim($data['isPrivate']);

    foreach(['name', 'surname1', 'surname2', 'email', 'username', 'password', 'isArtist', 'aboutMe', 'isPrivate'] as $key) {
      if (!isset($data[$key])) {
        $response = $response->withStatus(400, 'Missing requiered fields');
        return $response;
      }
      if (empty($data[$key])) {
        $response = $response->withStatus(400, 'Field ' . $key . ' is empty');
        return $response;
      }
    }

    $nameSurnamePattern = "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,50}^";
    $this->validateVariable($name, $nameSurnamePattern, 'Wrong name format', 1, $response);
    $this->validateVariable($surname1, $nameSurnamePattern, 'Wrong surname format', 1, $response);
    $this->validateVariable($surname2, $nameSurnamePattern, 'Wrong surname format', 1, $response);

    $this->validateVariable($email, null, 'Wrong email format', 0, $response);

    $usernamePattern = '^[a-z0-9_ ]{5,20}^'; 
    $this->validateVariable($username, $usernamePattern, 'Wrong username format', 1, $response);

    // TODO: CAMBIAR AL FINAL DEL PROYECTO
    $passwordPattern = '^[A-Za-z0-9 ]+^';
    $this->validateVariable($password, $passwordPattern, 'Wrong password format', 1, $response);

    $booleanPattern = '^[0-1]$';
    $this->validateVariable($isArtist, $booleanPattern, 'Wrong isArtist format', 1, $response);
    $this->validateVariable($isPrivate, $booleanPattern, 'Wrong isPrivate format', 1, $response);
      
    // todo: si está dado de baja para volver a activar su acc según el email if / else
    // todo: crear función para reactivarCuenta 
    
    $user = \Artist4all\Model\User::getUserByUsername($username);
    if (!is_null($user) && $id != $user->getId()) {
      $response = $response->withJson('El nombre de usuario introducido ya está cogido.')->withStatus(400, 'This username is already taken');
      return $response;
    }

    $user = \Artist4all\Model\User::getUserByEmail($email, 0);
    if (!is_null($user) && $id != $user->getId()) {
      $response = $response->withJson('El correo electrónico introducido ya está cogido.')->withStatus(400, 'This email is already taken');
      return $response;
    }

    if (is_null($id)) {
      $data['token'] = '';
    } else {
      $filePath = '/var/www/html/assets/img/';
      if (!empty($_FILES) || $_FILES != null) {
        foreach($_FILES as $file) {
          $imgName = $file["tmp_name"]; 
          $pathImg = $filePath.$file["name"];
          if (!file_exists($pathImg)) move_uploaded_file($imgName, $pathImg);  
          $data['imgAvatar'] = $file["name"];
        }         
      }
    }
    $data['id'] = $id;
    $user = \Artist4all\Model\User::fromAssoc($data);
    $user = \Artist4all\Model\User::persistUser($user);
    if (is_null($user)) {
      if (is_null($id)) $response = $response->withStatus(500, 'Error on the register');
      else $response = $response->withStatus(500, 'Error at editing');
      return $response;
    }
    return $user;
  }
  
  private function validateVariable($variable, $regexp, $message, $type, $response) {
    if ($type == 1) {
      if(!filter_var($variable, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $regexp)))) {
        $response = $response->withStatus(400, $message);
        return $response;
      }
    } else if ($type == 0) {
      if (!filter_var($variable, FILTER_VALIDATE_EMAIL)) {
        $response = $response->withStatus(400, $message);
        return $response;
      } 
    } 
  }

}
