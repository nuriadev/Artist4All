<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    
  
    $app->get('/login', function (Request $request, Response $response, array $args) {
      $data = $request->getParsedBody();
      $email = trim($data["email"]);
      $password = trim($data["password"]);
      $feedbackData = \Artist4all\Model\UserDB::getInstance()->login($email, $password);
      //if (is_null($feedbackData)) $response = $response->withStatus(400, "Usuario incorrecto");
      //else 
      $response = $response->withJson($feedbackData);
      return $response;
    });

    $app->post('/register', function (Request $request, Response $response, array $args) {
      $data = $request->getParsedBody();
      // $email = trim($data["email"]);
      // $user = \Artist4all\Model\UserDB::getInstance()->getUserByEmail($email);
      // if (!is_null($user)) {
      //   $response = $response->withStatus(400, "Ya existe este usuario");
      //   return $response;
      // }
      // All ok
      $data['id'] = null;
      $user = \Artist4all\Model\User::fromAssoc($data);
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
    }
}