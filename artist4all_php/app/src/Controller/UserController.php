<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    $app->post('/register', '\Artist4all\Controller\UserController:register');
    $app->post('/login', '\Artist4all\Controller\UserController:login');
    $app->post('/logout', '\Artist4all\Controller\UserController:logout');
    $app->post('/contact', '\Artist4all\Controller\UserController:sendContactForm');
    $app->post('/user/search', '\Artist4all\Controller\UserController:searchUsers');

    // TODO: cambiar a patch los 2 edits
    $app->post('/user/{id:[0-9 ]+}/profile', '\Artist4all\Controller\UserController:editProfile');
    $app->post('/user/{id:[0-9 ]+}/existByEmail', '\Artist4all\Controller\UserController:existUserByEmail');
    $app->post('/user/{id:[0-9 ]+}/existByUsername', '\Artist4all\Controller\UserController:existUserByUsername');
    $app->post('/user/{id:[0-9 ]+}/password', '\Artist4all\Controller\UserController:changePassword');

    $app->get('/user/{id:[0-9 ]+}', '\Artist4all\Controller\UserController:getUserById');
    $app->get('/user/{id:[0-9 ]+}/followSuggestions', '\Artist4all\Controller\UserController:getFollowSuggestions');

    $app->get('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}', '\Artist4all\Controller\UserController:isFollowingThatUser');
    $app->post('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}', '\Artist4all\Controller\UserController:requestOrFollowUser');
    // TODO: cambiar a patch 
    $app->post('/user/{id_follower:[0-9 ]+}/follow/{id_followed:[0-9 ]+}/{id_follow:[0-9 ]+}', '\Artist4all\Controller\UserController:updateFollowRequest');

    $app->get('/user/{id:[0-9 ]+}/follower', '\Artist4all\Controller\UserController:getFollowers');
    $app->get('/user/{id:[0-9 ]+}/followed', '\Artist4all\Controller\UserController:getFollowed');
    // TODO: cambiar a patch 
    $app->post('/user/{id:[0-9 ]+}/settings/account/privacy', '\Artist4all\Controller\UserController:privateAccountSwitcher');
    $app->post('/user/{id:[0-9 ]+}/settings/account', '\Artist4all\Controller\UserController:deactivateAccount');
  }

  public function register(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    return $this->validatePersist($data, null, $response, 'register');
  }


  public function login(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    return $this->loginProcess($data, $response);
  }

  public function searchUsers(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();  
    $searchedPattern = $data['searchedPattern'];
    $users = \Artist4all\Model\User::getUsersByPattern($searchedPattern);
    if (is_null($users)) $response = $response->withStatus(204, 'No users found');
    else $response = $response->withJson($users);
    return $response;
  }

  public function getFollowSuggestions(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $me = \Artist4all\Controller\UserController::getUserByIdSummary($id, $response);
    $users = \Artist4all\Model\User::getFollowed($me->getId());
    if (empty($users)) {
      $response = $response->withStatus(204, 'No users followed');
      return $response;
    }
    $usersAux = [];    
    $followSuggestions = [];
    foreach ($users as $user) {
      $usersAux = \Artist4all\Model\User::getFollowed($user->getId());
      if (!is_null($usersAux)) {
        foreach ($usersAux as $userAux) {
          if (!in_array($userAux, $followSuggestions)) {
            if ($userAux != $me) {
              if (is_null(\Artist4all\Model\User::isFollowingThatUser($me, $userAux))) {
                $followSuggestions[] = $userAux;    
              }
            }         
          } 
        }
      }
    }
    array_values($followSuggestions);
    shuffle($followSuggestions);
    if (count($followSuggestions) > 3) array_splice($followSuggestions, 3);
    if (empty($followSuggestions)) $response = $response->withStatus(204, 'No follow suggestions');
    else $response = $response->withJson($followSuggestions);
    return $response;
  }

  public function editProfile(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();    
    $user = static::getUserByIdSummary($id, $response);
    if (!isset($data['password'])) $data['password'] = $user->getPassword();
    if (!isset($data['isArtist'])) $data['isArtist'] = $user->isArtist();
    if (!isset($data['imgAvatar'])) $data['imgAvatar'] = $user->getImgAvatar();   
    if (!isset($data['token'])) $data['token'] = $user->getToken();
    if (!isset($data['isPrivate'])) $data['isPrivate'] = $user->isPrivate();
    return $this->validatePersist($data, $id, $response, 'edit');
  }

  public function existUserByEmail(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();    
    $email = trim($data['email']);
    $user = \Artist4all\Model\User::getUserByEmail($email, 0);
    if (!is_null($user) && $id != $user->getId()) $response = $response->withJson('El correo electrónico introducido ya está cogido.')->withStatus(400, 'This email is already taken');
    else $response = $response->withStatus(200);
    return $response;
  }

  public function existUserByUsername(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();    
    $username = trim($data['username']);
    $user = \Artist4all\Model\User::getUserByUsername($username);
    if (!is_null($user) && $id != $user->getId()) $response = $response->withJson('El nombre de usuario introducido ya está cogido.')->withStatus(400, 'This username is already taken');
    else $response = $response->withStatus(200);
    return $response;
  }

  public function changePassword(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $user = static::getUserByIdSummary($id, $response);
    $password = trim($data['password']);
    $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$#!%*?&])([A-Za-z\d$@#$!%*?&]|[^ ]){8,20}$/';
    if(!filter_var($password, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $passwordPattern)))) {
      $response = $response->withStatus(400, 'Wrong password format');
      return $response;
    }
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
    $status_follow = trim(intval($data['status_follow']));
    if($status_follow != 1 && $status_follow != 2 && $status_follow !=3) {
      $response = $response->withStatus(400, 'Wrong status_follow');
      return $response;
    }
    $logFollow = [
      'id' => $id,
      'id_follower' => $follower->getId(),
      'id_followed' => $followed->getId(),
      'status_follow' => $status_follow
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
    $isPrivate = trim(intval($data['isPrivate']));
    if($isPrivate != 0 && $isPrivate != 1) {
      $response = $response->withStatus(400, 'Wrong isPrivate format');
      return $response;
    }
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

  public function deactivateAccount(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $user = static::getUserByIdSummary($id, $response);
    $result = \Artist4all\Model\User::deactivateAccount($id);
    if (!$result) {
      $response = $response->withStatus(404, 'Error at deactivating');
      return $response;
    } 
    return $this->logout($request, $response, $args);   
  }

  public function sendContactForm(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody(); 
    foreach(['name', 'surname1', 'email', 'bodyMessage'] as $key) {
      if (!isset($data[$key])) {
        $response = $response->withStatus(400, 'Missing requiered fields');
        return $response;
      }
      if (empty($data[$key])) {
        $response = $response->withStatus(400, 'Field ' . $key . ' is empty');
        return $response;
      }
    }

    $name = trim($data['name']);
    $nameSurnamePattern = "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,50}^";
    if(!filter_var($name, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $nameSurnamePattern)))) {
      $response = $response->withStatus(400, 'Wrong name format');
      return $response;
    }

    $surname1 = trim($data['surname1']);
    if(!filter_var($surname1, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $nameSurnamePattern)))) {
      $response = $response->withStatus(400, 'Wrong surname format');
      return $response;
    }

    $email = trim($data['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response = $response->withStatus(400, 'Wrong email format');
      return $response;
    } 

    $phone = trim($data['phone']);
    if (!empty($phone)) {
      $phonePattern = '^(6|7|9)[ -]*([0-9][ -]*){8}$^';
      if(!filter_var($phone, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $phonePattern)))) {
        $response = $response->withStatus(400, 'Wrong phone format');
        return $response;
      }
    }

    $bodyMessage = trim($data['bodyMessage']);
    if (strlen($bodyMessage) > 510) {
      $response = $response->withStatus(400, 'Maximum character length surpassed');
      return $response;
    }

    $message = \Artist4all\Model\User::sendContactForm(null, $name, $surname1, $email, $phone, $bodyMessage);
    if (is_null($message)) $response = $response->withStatus(500, 'Error at storing the message');
    else $response = $response->withJson($message);
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
    
    $email = trim($data['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response = $response->withStatus(400, 'Wrong email format');
      return $response;
    } 

    $user = \Artist4all\Model\User::getUserByEmail($email, 1);
    if (!is_null($user)) {
      \Artist4all\Model\User::reactivateAccount($email);
    } 

    $password = trim($data['password']);
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

  private function validatePersist($data, $id, $response, $type) { 
    foreach(['name', 'surname1', 'surname2', 'email', 'username', 'password', 'aboutMe'] as $key) {
      if (!isset($data[$key])) {
        $response = $response->withStatus(400, 'Missing requiered fields');
        return $response;
      }
      if (empty($data[$key])) {
        $response = $response->withStatus(400, 'Field ' . $key . ' is empty');
        return $response;
      }
    }

    $name = trim($data['name']);
    $nameSurnamePattern = "^[a-zA-ZàáâäãåąčćęèéêëėįìíîïłńòóôöõøùúûüųūÿýżźñçčšžÀÁÂÄÃÅĄĆČĖĘÈÉÊËÌÍÎÏĮŁŃÒÓÔÖÕØÙÚÛÜŲŪŸÝŻŹÑßÇŒÆČŠŽ∂ð ,.'-]{2,50}^";
    if(!filter_var($name, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $nameSurnamePattern)))) {
      $response = $response->withStatus(400, 'Wrong name format');
      return $response;
    }

    $surname1 = trim($data['surname1']);
    $surname2 = trim($data['surname2']);
    if(!filter_var($surname1, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $nameSurnamePattern))) || 
       !filter_var($surname2, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $nameSurnamePattern))) ) {
      $response = $response->withStatus(400, 'Wrong surname format');
      return $response;
    }

    $email = trim($data['email']);
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $response = $response->withStatus(400, 'Wrong email format');
      return $response;
    } 

    $username = trim($data['username']);
    $usernamePattern = '^[a-z0-9_ ]{5,20}^'; 
    if(!filter_var($username, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $usernamePattern)))) {
      $response = $response->withStatus(400, 'Wrong username format');
      return $response;
    }

    if (is_null($id)) {
      $password = trim($data['password']);
      $passwordPattern = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$#!%*?&])([A-Za-z\d$@#$!%*?&]|[^ ]){8,20}$/';
      if(!filter_var($password, FILTER_VALIDATE_REGEXP,  array("options" => array("regexp" => $passwordPattern)))) {
        $response = $response->withStatus(400, 'Wrong password format');
        return $response;
      }
    }

    $aboutMe = trim($data['aboutMe']);

    $isArtist = trim(intval($data['isArtist']));
    if($isArtist != 0 && $isArtist != 1) {
      $response = $response->withStatus(400, 'Wrong isArtist format');
      return $response;
    }

    $isPrivate = trim(intval($data['isPrivate']));
    if($isPrivate != 0 && $isPrivate != 1) {
      $response = $response->withStatus(400, 'Wrong isPrivate format');
      return $response;
    }
      
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
        $allowed = array('image/gif', 'image/png', 'image/jpg', 'image/jpeg');
        foreach($_FILES as $file) {
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          if (!in_array(finfo_file($finfo, $file['tmp_name']), $allowed)) {
            $response = $response->withStatus(400, 'File is not an image');
            return $response;
          }     
          finfo_close($finfo);
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
    if ($type == 'register') {
      return $this->loginProcess($data, $response);
    } else if ($type == 'edit') {
      $session = new \Artist4all\Model\Session($user->getToken(), $user);
      $response = $response->withJson($session)->withStatus(200, 'User edited');
      return $response;
    }
  }

}
 