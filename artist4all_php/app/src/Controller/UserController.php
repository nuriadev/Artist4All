<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    $app->post('/register', '\Artist4all\Controller\UserController:register');
    $app->post('/login', '\Artist4all\Controller\UserController:login');
    $app->post('/logout', '\Artist4all\Controller\UserController:logout');
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
      $createOrUpdateToken = \Artist4All\Model\UserDB::getInstance()->updateToken($token, $user);
      $session = new \Artist4All\Model\Session($token, $user);
      $response = $response->withJson($session)->withStatus(200, 'Sesión iniciada');          
    } else {
      $response = $response->withStatus(400, 'Usuario incorrecto'); 
    }     
    return $response;    
  } 
}