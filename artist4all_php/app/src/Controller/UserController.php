<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {

  // TODO: avisar de que he cambiado de delete a deactivated, crear notificaciones y los logs, contact page on session/ footer removed from app component
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
    $app->post('/user/{username_follower:[a-zA-Z0-9 ]+}/follow/{username_followed:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:followUser');
    $app->delete('/user/{username_follower:[a-zA-Z0-9 ]+}/follow/{username_followed:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:unfollowUser'); 
    
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/follower', '\Artist4all\Controller\UserController:countFollowers');  
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/followed', '\Artist4all\Controller\UserController:countFollowed'); 

    $app->get('/user/{username:[a-zA-Z0-9 ]+}/list/follower', '\Artist4all\Controller\UserController:getFollowers');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/list/followed', '\Artist4all\Controller\UserController:getUsersFollowed');
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
      return $response;
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
    $authorizated = $this->isAuthorizated($request, $response); 
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

  public function followUser(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $token = trim($data['token']);
    $result = \Artist4all\Model\User\UserDB::getInstance()->isValidToken($token);
    if (!$result) {
      $response = $response->withStatus(500, 'Unauthenticated user');
      return $response;
    } else {
      $username_follower = $args['username_follower'];
      $username_followed = $args['username_followed']; 
      $follower = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_follower);
      $followed = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username_followed);
      if (is_null($follower) || is_null($followed)) {
        $response = $response->withStatus(404, 'User not found');
        return $response;
      }
      $result = \Artist4all\Model\User\UserDB::getInstance()->followUser($follower, $followed);
      if(is_null($result)) $response = $response->withStatus(500, 'Error at following');
      else $response = $response->withJson($result)->withStatus(200, 'Added to your followed list');
      return $response;
    }
  }

  public function unfollowUser(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $authorizated = $this->isAuthorizated($request, $response); 
    $id = (int) $data['id_follow'];
    $result = \Artist4all\Model\User\UserDB::getInstance()->unfollowUser($id);
    if(is_null($result)) $response = $response->withStatus(500, 'Error at unfollowing');
    else $response = $response->withJson($result)->withStatus(200, 'Removed from your followed list');
    return $response; 
  }

  public function countFollowers(Request $request, Response $response, array $args) {
    $authorizated = $this->isAuthorizated($request, $response);   
    $username = $args['username'];
    return $this->countFollowersOrFollowedUsers($username, 'followers', $response);
  }

  public function countFollowed(Request $request, Response $response, array $args) {
    $authorizated = $this->isAuthorizated($request, $response);  
    $username = $args['username'];
    return $this->countFollowersOrFollowedUsers($username, 'followed', $response);
  }

  public function getFollowers(Request $request, Response $response, array $args) {
    $authorizated = $this->isAuthorizated($request, $response);  
    return $this->getFollowersOrFollowedUsers($args, 'followers', $response);
  }

  public function getUsersFollowed(Request $request, Response $response, array $args) {
    $authorizated = $this->isAuthorizated($request, $response);
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