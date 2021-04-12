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
    $app->post('/user/my/settings/profile', '\Artist4all\Controller\UserController:edit');
    $app->post('/user/my/settings/password', '\Artist4all\Controller\UserController:changePassword');

    $app->get('/user/{username:[a-zA-Z0-9 ]+}/list', '\Artist4all\Controller\UserController:getOtherUsers');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:getUserByUsername');

    $app->get('/user/{username_follower:[a-zA-Z0-9 ]+}/follow/{username_followed:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:isFollowingThatUser');
    $app->post('/user/{username_follower:[a-zA-Z0-9 ]+}/follow/{username_followed:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:requestOrFollowUser');
    // TODO: cambiar a patch 
    $app->post('/user/{username_follower:[a-zA-Z0-9 ]+}/follow/{username_followed:[a-zA-Z0-9 ]+}/{id_follow:[0-9 ]+}', '\Artist4all\Controller\UserController:updateFollowRequest'); 
    
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/follower', '\Artist4all\Controller\UserController:countFollowers');  
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/followed', '\Artist4all\Controller\UserController:countFollowed'); 

    $app->get('/user/{username:[a-zA-Z0-9 ]+}/list/follower', '\Artist4all\Controller\UserController:getFollowers');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/list/followed', '\Artist4all\Controller\UserController:getUsersFollowed');
    // TODO: cambiar a patch 
    $app->post('/user/my/settings/account/privacy', '\Artist4all\Controller\UserController:switchPrivateAccount');
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

  public function edit(Request $request, Response $response, array $args) {  
    $data = $request->getParsedBody();
    $id = trim($data['id']);
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    if (!isset($data['password'])) $data['password'] = $user->getPassword();
    if (!isset($data['isArtist'])) $data['isArtist'] = $user->isArtist();
    if (!isset($data['imgAvatar'])) $data['imgAvatar'] = $user->getImgAvatar();
    $user = $this->validatePersist($data, $id, $response);
    if (is_null($user)) {
      $response = $response->withStatus(500, 'Error on the edit');
      return $response;
    }
    $session = new \Artist4all\Model\User\Session($user->getToken(), $user);
    $response = $response->withJson($session)->withStatus(200, 'User edited');
    return $response;
  }

  public function changePassword(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data["id"]);
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $password = trim($data["password"]); // todo validar campos
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = trim($data["token"]);
    $result = \Artist4all\Model\User\UserDB::getInstance()->changePassword($password_hashed, $token);
    if (!$result) {
      $response = $response->withStatus(500, 'Error on the password modification'); 
    } else {
      $session = new \Artist4all\Model\User\Session($user->getToken(), $user);
      $response = $response->withJson($session)->withStatus(200, 'Password changed');
    }
    return $response; 
  }

  public function getOtherUsers(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $users = \Artist4all\Model\User\UserDB::getInstance()->getOtherUsers($username);
    if (is_null($users)) $response = $response->withStatus(500, 'Users not found');
    else $response = $response->withJson($users);
    return $response;
  }

  public function getUserByUsername(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) $response = $response->withStatus(404, 'User not found');
    else $response = $response->withJson($user);
    return $response;
  }

  public function isFollowingThatUser(Request $request, Response $response, array $args) {
    $this->isAuthorizated($request, $response); 
    $username_follower = $args['username_follower'];
    $username_followed = $args['username_followed']; 
    $follower = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_follower);
    $followed = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_followed);
    if (is_null($follower) || is_null($followed)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $result = \Artist4all\Model\User\UserDB::getInstance()->isFollowingThatUser($follower, $followed);
    if(is_null($result)) $response = $response->withStatus(200, 'User not followed');
    else $response = $response->withJson($result)->withStatus(200, 'User followed');
    return $response;
  }

  public function requestOrFollowUser(Request $request, Response $response, array $args) {
    $this->isAuthorizated($request, $response);   
    $data = $request->getParsedBody();
    if (!isset($data['id_follow'])) $id = null;
    else $id = $data['id_follow'];       
    return $this->createOrUpdateFollow($args, $data, $id, $response);
  }

  // TODO: una vez cambiado a patch, adaptar la function
  public function updateFollowRequest(Request $request, Response $response, array $args) {  
    $this->isAuthorizated($request, $response); 
     $id = $args['id_follow'];
    $data = $request->getParsedBody();   
    
    return $this->createOrUpdateFollow($args, $data, $id, $response);
  }

  private function createOrUpdateFollow($args, $data, $id, $response) { 
    $username_follower = $args['username_follower'];
    $username_followed = $args['username_followed'];   
    $follower = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_follower);
    $followed = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_followed);
    if (is_null($follower) || is_null($followed)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $data['id_follower'] = $follower->getId();
    $data['id_followed'] = $followed->getId(); 
    $logFollow = [
      'id' => $id,
      'id_follower' => $data['id_follower'],
      'id_followed' => $data['id_followed'], 
      'status_follow' => (int) $data['status_follow']
    ];
    $logFollow = \Artist4all\Model\User\UserDB::getInstance()->persistFollow($logFollow);
    if(is_null($logFollow)) {
      $response = $response->withStatus(500);
    } else {
      if ($logFollow['status_follow'] == 2) {
        $this->createNotification($follower, $followed, 'te ha enviado una solicitud de amistad', 2, $response);
      } else if ($logFollow['status_follow'] == 3) {
        $this->createNotification($follower, $followed, 'ha empezado a seguirte', 1, $response);
      }
      $response = $response->withJson($logFollow);
    } 
    return $response;
  }

  private function createNotification($user_responsible, $user_receiver, $bodyNotification, $typeNotification, $response) {
    $notification = new \Artist4all\Model\Notification\Notification(null, $user_responsible, $user_receiver, $bodyNotification, 0, $typeNotification, '');
    $newNotification = \Artist4all\Model\Notification\NotificationDB::getInstance()->insertNotification($notification);
    if (is_null($newNotification)) $response = $response->withStatus(500, 'Error at creating the notification');
    return $newNotification;
  }

  public function countFollowers(Request $request, Response $response, array $args) {
    $this->isAuthorizated($request, $response);   
    $username = $args['username'];
    return $this->countFollowersOrFollowedUsers($username, 'followers', $response);
  }

  public function countFollowed(Request $request, Response $response, array $args) {
    $this->isAuthorizated($request, $response);  
    $username = $args['username'];
    return $this->countFollowersOrFollowedUsers($username, 'followed', $response);
  }

  public function getFollowers(Request $request, Response $response, array $args) {
     $this->isAuthorizated($request, $response);  
    return $this->getFollowersOrFollowedUsers($args, 'followers', $response);
  }

  public function getUsersFollowed(Request $request, Response $response, array $args) {
    $this->isAuthorizated($request, $response);
    return $this->getFollowersOrFollowedUsers($args, 'followed', $response);
  } 

  public function logout(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $token = trim($data["token"]);
    $result = \Artist4all\Model\User\UserDB::getInstance()->logout($token);
    if(!$result) $response = $response->withStatus(500, 'Error at closing session');  
    else $response = $response->withStatus(200, 'Session closed');  
    return $response;
  }

  public function switchPrivateAccount(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $username = $data['username'];
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      $isPrivate = $data['isPrivate'];  
      $token = $data['token'];
      $user->setIsPrivate($isPrivate);
      $result = \Artist4all\Model\User\UserDB::getInstance()->switchPrivateAccount($user, $token);
      if (!$result) {
        $response = $response->withStatus(400, 'Error at switching private mode');
      } else {
        $session = new \Artist4all\Model\User\Session($user->getToken(), $user);
        $response = $response->withJson($session)->withStatus(200, 'Switched private mode');
      }
      return $response;
    }
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

  private function countFollowersOrFollowedUsers(string $username, string $followedOrFollowing, Response $response) {
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      $id = $user->getId();
      if ($followedOrFollowing == 'followers') $n_users = \Artist4all\Model\User\UserDB::getInstance()->countOrGetFollowers($id, 'count');
      else if ($followedOrFollowing == 'followed') $n_users = \Artist4all\Model\User\UserDB::getInstance()->countOrGetFollowed($id, 'count');
      if (is_null($n_users)) $response = $response->withStatus(500);
      else $response = $response->withJson($n_users);
      return $response;
    }
  }

  private function getFollowersOrFollowedUsers(array $args, string $followedOrFollowing, Response $response) {
    $username = $args['username'];
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if ($followedOrFollowing == 'followers') $idsUsers = \Artist4all\Model\User\UserDB::getInstance()->countOrGetFollowers($user->getId(), 'get');
    else if ($followedOrFollowing == 'followed') $idsUsers = \Artist4all\Model\User\UserDB::getInstance()->countOrGetFollowed($user->getId(), 'get');
    $users = [];
    if (!empty($idsUsers)) {
      foreach($idsUsers as $idUser) {
        $user = \Artist4all\Model\User\UserDB::getInstance()->getUserById($idUser);
        $users[] = $user;
      }
    }
    if (empty($idsUsers)) $response = $response->withStatus(200, 'No users collected');
    else $response = $response->withJson($users);
    return $response;
  }

  private function loginProcess(array $data, Response $response) {    
    // todo validacion email y password
    $email = trim($data["email"]);
    $password = trim($data["password"]);
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByEmail($email);
    if(is_null($user)) {
      $response = $response->withStatus(400, 'Unvalid user'); 
      return $response;
    }
    if (password_verify($password, $user->getPassword())) {  
      $arrayAux = array($user->getEmail(), $user->getPassword(), \Artist4all\Model\User\TokenGenerator::randomTokenPartGenerator());
      $content = implode(".", $arrayAux);
      // creamos el token a partir de la variable $content
      $token = \Artist4all\Model\User\TokenGenerator::tokenGenerator($content);
      $userWithToken = \Artist4all\Model\User\UserDB::getInstance()->createOrUpdateToken($token, $user);
      $session = new \Artist4all\Model\User\Session($token, $user);
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
      $folderUrl = "assets/img".DIRECTORY_SEPARATOR;
      //TODO:Mover la img pasada a una carpeta interna 'assets', limitar el tamaño y el formato de la img
      foreach ($_FILES as $file) {
          $nombreImg = $file["tmp_name"];
          $urlImg = $folderUrl.$file["name"];
          move_uploaded_file($nombreImg, $urlImg);
      }
      $currentImgAvatar = $data['imgAvatar'];
      if (isset($urlImg)) $data['imgAvatar'] = 'http://localhost:81/' . $urlImg;
      else $data['imgAvatar'] = $currentImgAvatar;  
    }
    $data['id'] = $id;
    $user = \Artist4all\Model\User\User::fromAssoc($data);
    $user = \Artist4all\Model\User\UserDB::getInstance()->persistUser($user);
    return $user; 
  }
}