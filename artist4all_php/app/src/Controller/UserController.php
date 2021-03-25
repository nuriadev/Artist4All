<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    $app->post('/register', '\Artist4all\Controller\UserController:register');
    $app->post('/login', '\Artist4all\Controller\UserController:login');
    $app->post('/logout', '\Artist4all\Controller\UserController:logout');
    $app->put('/edit', '\Artist4all\Controller\UserController:edit');
    $app->patch('/editPassword', '\Artist4all\Controller\UserController:editPassword');
    $app->get('/user/{username:[a-zA-Z0-9]+}', '\Artist4all\Controller\UserController:getOtherUsers');
    $app->get('/profile/{username:[a-zA-Z0-9]+}', '\Artist4all\Controller\UserController:getUserByUsername');
  }

  public function register(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $data['id'] = null;
    // todo: validación campos
    // todo: comprobar si ya existe un usuario registrado en la db según el email y el username antes de registrar 
    // todo: si está dado de baja para volver a activar su acc
    $user = \Artist4all\Model\User::fromAssoc($data);
    $result = \Artist4all\Model\UserDB::getInstance()->registerUser($user);
    if (!$result) {
      $response = $response->withStatus(500, 'Error en el registro'); 
      return $response;
    } else {
      return $this->loginProcess($data, $response);
    }
  } 

  public function login(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    return $this->loginProcess($data, $response);
  }

  //!NO VA
  public function edit(Request $request, Response $response, array $args) {  
    $data = $request->getParsedBody();
    $id = trim($data["id"]);
    $userEdited = \Artist4all\Model\UserDB::getInstance()->getUserById($id);
    $name = trim($data["name"]); // validar
    $surname1 = trim($data["surname1"]); // validar
    $surname2 = trim($data["surname2"]); // validar 
    $email = trim($data["email"]); // validar
    $username = trim($data["username"]); // validar
    $aboutMe = trim($data["aboutMe"]); 
    $newImgUser = trim($data["newImgAvatar"]);
    $token = trim($data["token"]);
    if (!isset($data["password"])) $data["password"] = $userEdited->getPassword();
    if (!isset($data["isArtist"])) $data["isArtist"] = $userEdited->isArtist();
    if (!isset($data["imgAvatar"])) $data["imgAvatar"] = $userEdited->getImgAvatar();
    
    if (!is_null($newImgUser)) {
      $newImgAvatar = 'http://localhost:81/assets/img/'. $newImgUser;
      $userEdited = new \Artist4All\Model\User($id, $name, $surname1, $surname2, $email, $data["password"], $username, $data["isArtist"], $newImgAvatar, $aboutMe);
    } else {
      $userEdited = new \Artist4All\Model\User($id, $name, $surname1, $surname2, $email, $data["password"], $username, $data["isArtist"], $data["imgAvatar"], $aboutMe);
    }
    $result = \Artist4all\Model\UserDB::getInstance()->editUser($userEdited, $token);
    if (!$result) {
      $response = $response->withStatus(500, 'Error en la modificación');  
    } else {
      $user = \Artist4all\Model\UserDB::getInstance()->getUserById($id);
      $response = $response->withJson($user)->withStatus(200, 'Usuario editado');
    } 
    return $response;
  }

  public function editPassword(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data["id"]);
    $password = trim($data["password"]); //validar
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $token = trim($data["token"]);
    $result = \Artist4all\Model\UserDB::getInstance()->editPassword($password_hashed, $token);
    if (!$result) {
      $response = $response->withStatus(500, 'Error al modificar la contraseña'); 
      return $response;
    } else {
      $user = \Artist4all\Model\UserDB::getInstance()->getUserById($id);
      $response = $response->withJson($user)->withStatus(200, 'Contraseña modificada');
    }
    // $2y$10$XSD0WWjE4gUGmK7fPglziuQUpJSfofxGhQNhMCFFGBmjXbyoj0HiO
    return $response; 
  }
  //!

  public function getOtherUsers(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $users = \Artist4all\Model\UserDB::getInstance()->getOtherUsers($username);
    if (is_null($users)) {
      $response = $response->withStatus(500, 'No se encontraron otros usuarios');
    } else {
      $response = $response->withJson($users);
    }
    return $response;
  }

  public function getUserByUsername(Request $request, Response $response, array $args) {
    $username = $args['username'];
    $user = \Artist4all\Model\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'Usuario no encontrado');
    } else {
      $response = $response->withJson($user);
    }
    return $response;
  }

  public function logout(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $token = trim($data["token"]);
    $result = \Artist4all\Model\UserDB::getInstance()->logout($token);
    if(!$result) {
      $response = $response->withStatus(500, 'Error al cerrar la sesión');  
    } else {
      $response = $response->withStatus(200, 'Sesión cerrada');  
    }
    return $response;
  }

  private function loginProcess(array $data, Response $response) {       
    $email = trim($data["email"]);
    $password = trim($data["password"]);
    // todo: validación
    $user = \Artist4all\Model\UserDB::getInstance()->getUserByEmail($email);
    if(is_null($user)) {
      $response = $response->withStatus(400, 'Usuario incorrecto'); 
      return $response;
    }
    if (password_verify($password, $user->getPassword())) {  
      $arrayAux = array($user->getEmail(), $user->getPassword(), \Artist4All\Model\TokenGenerator::randomTokenPartGenerator());
      $content = implode(".", $arrayAux);
      // creamos el token a partir de la variable $content
      $token = \Artist4All\Model\TokenGenerator::tokenGenerator($content);
      $userToken = \Artist4All\Model\UserDB::getInstance()->createOrUpdateToken($token, $user);
      $session = new \Artist4All\Model\Session($token, $user);
      $response = $response->withJson($session)->withStatus(200, 'Sesión iniciada');          
    } else {
      $response = $response->withStatus(400, 'Usuario incorrecto'); 
    }     
    return $response;    
  } 
}