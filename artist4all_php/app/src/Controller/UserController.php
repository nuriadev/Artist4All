<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    
    $app->post('/register', function (Request $request, Response $response, array $args) {
      $data = $request->getParsedBody();
      // $email = trim($data["email"]);
      // $user = \Artist4all\Model\UserDB::getInstance()->getUserByEmail($email);
      // if (!is_null($user)) {
      //   $response = $response->withStatus(400, "Ya existe este usuario");
      //   return $response;
      // }
      // All ok
      $img = "http://localhost:81/artist4all_php/User/assets/img/imgUnknown.png";
      $aboutMe = "Bienvendio a mi perfil!!!";
      $user = new \Artist4all\Model\User(
        null, 
        $data['name'], 
        $data['surname1'], 
        $data['surname2'], 
        $data['email'], 
        $data['username'], 
        $data['password'], 
        $data['type_user'], 
        $data['n_followers'], 
        $img, 
        $aboutMe, 
      );
      $result = \Artist4all\Model\UserDB::getInstance()->registerUser($user);
      if (!$result) {
        $response = $response->withStatus(500); 
      } else {
        $email = trim($data["email"]);
        $password = trim($data["password"]);
        $feedbackData = \Artist4all\Model\UserDB::getInstance()->login($email, $password);
        $response = $response->withJson($feedbackData); 
      }
      return $response;
    });

    $app->post('/login', function (Request $request, Response $response, array $args) {
      $data = $request->getParsedBody();
      $email = trim($data["email"]);
      $password = trim($data["password"]);
      $feedbackData = \Artist4all\Model\UserDB::getInstance()->login($email, $password);
      $response = $response->withJson($feedbackData);  
      return $response;
    });

    }
}