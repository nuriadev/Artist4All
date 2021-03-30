<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {

  // TODO: hacer que se pueda modificar sin seleccionar una foto de usuario, avisar de que he cambiado de delete a deactivated, crear notificaciones y los logs, contact page on session/ footer removed from app component
  public static function initRoutes($app) {
    $app->post('/register', '\Artist4all\Controller\UserController:register');
    $app->post('/login', '\Artist4all\Controller\UserController:login');
    $app->post('/logout', '\Artist4all\Controller\UserController:logout');
    // TODO: cambiar a patch los 2 edits
    $app->post('/settings/profile', '\Artist4all\Controller\UserController:edit');
    $app->post('/settings/password', '\Artist4all\Controller\UserController:changePassword');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:getOtherUsers');
    $app->get('/profile/{username:[a-zA-Z0-9 ]+}', '\Artist4all\Controller\UserController:getUserByUsername');
    $app->post('/profile', '\Artist4all\Controller\UserController:followUser');
    $app->delete('/profile/{id:[0-9]+}', '\Artist4all\Controller\UserController:unfollowUser'); 
    $app->post('/profile/my', '\Artist4all\Controller\UserController:isFollowingThatUser');  
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
    $user= \Artist4all\Model\UserDB::getInstance()->getUserById($id);
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
    $session = new \Artist4All\Model\Session($user->getToken(), $user);
    $response = $response->withJson($session)->withStatus(200, 'User edited');
    return $response;
  }

  public function changePassword(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data["id"]);
    $user= \Artist4all\Model\UserDB::getInstance()->getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $password = trim($data["password"]); // todo validar campos
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = trim($data["token"]);
    $result = \Artist4all\Model\UserDB::getInstance()->changePassword($password_hashed, $token);
    if (!$result) {
      $response = $response->withStatus(500, 'Error on the password modification'); 
      return $response;
    } else {
      $session = new \Artist4All\Model\Session($user->getToken(), $user);
      $response = $response->withJson($session)->withStatus(200, 'Password changed');
    }
    return $response; 
  }

  public function getOtherUsers(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $users = \Artist4all\Model\UserDB::getInstance()->getOtherUsers($username);
    if (is_null($users)) $response = $response->withStatus(500, 'Users not found');
    else $response = $response->withJson($users);
    return $response;
  }

  public function getUserByUsername(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $user = \Artist4all\Model\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) $response = $response->withStatus(404, 'User not found');
    else $response = $response->withJson($user);
    return $response;
  }

  public function isFollowingThatUser(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id_follower = $data['id_follower'];
    $id_followed = $data['id_followed']; 
    $follower = \Artist4all\Model\UserDB::getInstance()->getUserById($id_follower);
    $followed = \Artist4all\Model\UserDB::getInstance()->getUserById($id_followed);
    if (is_null($follower) || is_null($followed)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $result = \Artist4all\Model\UserDB::getInstance()->isFollowingThatUser($follower, $followed);
    if(is_null($result)) $response = $response->withJson($result)->withStatus(200, 'User unfollowed');
    else $response = $response->withJson($result)->withStatus(200, 'User followed');
    return $response;
  }

  public function followUser(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id_follower = $data['id_follower'];
    $id_followed = $data['id_followed']; 
    $follower = \Artist4all\Model\UserDB::getInstance()->getUserById($id_follower);
    $followed = \Artist4all\Model\UserDB::getInstance()->getUserById($id_followed);
    if (is_null($follower) || is_null($followed)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $result = \Artist4all\Model\UserDB::getInstance()->followUser($follower, $followed);
    if(is_null($result)) $response = $response->withStatus(400, 'Error at following');
    else $response = $response->withJson($result)->withStatus(200, 'Added to your followed list');
    return $response;
  }

  public function unfollowUser(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $result = \Artist4all\Model\UserDB::getInstance()->unfollowUser($id);
    if(!$result) $response = $response->withStatus(500, 'Error at unfollwing');
    else $response = $response->withJson($result)->withStatus(200, 'Removed from your followed list');
    return $response;
  }

  public function logout(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $token = trim($data["token"]);
    $result = \Artist4all\Model\UserDB::getInstance()->logout($token);
    if(!$result) $response = $response->withStatus(500, 'Error at closing session');  
    else $response = $response->withStatus(200, 'Session closed');  
    return $response;
  }

  private function loginProcess(array $data, Response $response) {    
    // todo validacion email y password
    $email = trim($data["email"]);
    $password = trim($data["password"]);
    $user = \Artist4all\Model\UserDB::getInstance()->getUserByEmail($email);
    if(is_null($user)) {
      $response = $response->withStatus(400, 'Unvalid user'); 
      return $response;
    }
    if (password_verify($password, $user->getPassword())) {  
      $arrayAux = array($user->getEmail(), $user->getPassword(), \Artist4All\Model\TokenGenerator::randomTokenPartGenerator());
      $content = implode(".", $arrayAux);
      // creamos el token a partir de la variable $content
      $token = \Artist4All\Model\TokenGenerator::tokenGenerator($content);
      $userWithToken = \Artist4All\Model\UserDB::getInstance()->createOrUpdateToken($token, $user);
      $session = new \Artist4All\Model\Session($token, $user);
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
      $newImg = json_decode(file_get_contents("php://input"), true);
      $folderUrl = "assets/img".DIRECTORY_SEPARATOR;
      foreach ($_FILES as $file) {
          $nombreImg = $file["tmp_name"];
          $urlImg = $folderUrl.$file["name"];
          move_uploaded_file($nombreImg, $urlImg);
      }
      $currentImgAvatar = $data['imgAvatar'];
      if (!is_null($urlImg)) $data['imgAvatar'] = 'http://localhost:81/' . $urlImg;
      else $data['imgAvatar'] = 'http://localhost:81/assets/img/' . $currentImgAvatar;  
    }
    $data['id'] = $id;
    $user = \Artist4all\Model\User::fromAssoc($data);
    $user = \Artist4all\Model\UserDB::getInstance()->persistUser($user);
    return $user; 
  }
}