<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class UserController {
  public static function initRoutes($app) {
    
    // $app->get('/color', function (Request $request, Response $response, array $args) {
    //   $colors = \ApiSudoku\Model\ColorDB::getInstance()->getAllColors();
    //   if (is_null($colors)) {
    //     $response = $response->withStatus(500);
    //   }
    //   else {
    //     $response = $response->withJson($colors);
    //   }
    //   return $response;
    // });

    // $app->get('/color/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
    //   $id = $args['id'];
    //   $color = \ApiSudoku\Model\ColorDB::getInstance()->getColorById($id);
    //   if (is_null($color)) {
    //     $response = $response->withStatus(404);
    //   }
    //   else {
    //     $response = $response->withJson($color);
    //   }
    //   return $response;
    // });

    // $app->get('/color/{name:[a-zA-Z]+}', function (Request $request, Response $response, array $args) {
    //   $name = $args['name'];
    //   $color = \ApiSudoku\Model\ColorDB::getInstance()->getColorByName($name);
    //   if (is_null($color)) {
    //     $response = $response->withStatus(404);
    //   }
    //   else {
    //     $response = $response->withJson($color);
    //   }
    //   return $response;
    // });

    $app->get('/login', function (Request $request, Response $response, array $args) {
      // $data = $request->getParsedBody();
      // $email = trim($data["email"]);
      // $password = trim($data["password"]);
      // $feedbackData = \Artist4all\Model\UserDB::getInstance()->login($email, $password);
      // if (is_null($feedbackData)) $response = $response->withStatus(400, "Usuario incorrecto");
      // else $response = $response->withJson($feedbackData);
      // return $response;
    });

    $app->post('/register', function (Request $request, Response $response, array $args) {
      // $data = $request->getParsedBody();
      // // $email = trim($data["email"]);
      // // $user = \Artist4all\Model\UserDB::getInstance()->getUserByEmail($email);
      // // if (!is_null($user)) {
      // //   $response = $response->withStatus(400, "Ya existe este usuario");
      // //   return $response;
      // // }
      // // All ok
      // $data['id'] = null;
      // $user = \Artist4all\Model\User::fromAssoc($data);
      // $feedbackData = \Artist4all\Model\UserDB::getInstance()->registerUser($user);
      // if (is_null($user)) $response = $response->withStatus(500); 
      // else $response = $response->withJson($feedbackData);
      // return $response;
    });

  //   // TODO PUT /color/{id}
  //   $app->put('/color/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
  //     $id = $args['id'];
  //     $data = $request->getParsedBody();
  //     // Check all fields are set
  //     foreach(["name", "red", "green", "blue"] as $key) {
  //       if (!isset($data[$key])) {
  //         $response = $response->withStatus(400);
  //         return $response;
  //       }
  //     }
  //     // Validate numbers
  //     foreach(["red", "green", "blue"] as $key) {
  //       $value = $data[$key];
  //       if (!is_numeric($value) || ($value < 0) || ($value > 255)) {
  //         $response = $response->withStatus(400);
  //         return $response;
  //       }
  //     }
  //     // Validate name
  //     $name = trim($data["name"]);
  //     if (empty($name)) {
  //       $response = $response->withStatus(400);
  //       return $response;
  //     }
  //     $color = \ApiSudoku\Model\ColorDB::getInstance()->getColorByName($name);
  //     if (!is_null($color) && $id != $color->getId()) {
  //       $response = $response->withStatus(400, 'This color name already exists in another color');
  //       return $response;
  //     }        
  //     $data['id'] = $id;
  //     $color = \ApiSudoku\Model\Color::fromAssoc($data);
  //     $color = \ApiSudoku\Model\ColorDB::getInstance()->updateColor($color);
  //     if (is_null($color)) {
  //       $response = $response->withStatus(500);
  //     }
  //     else {
  //       $response = $response->withJson($color);
  //     }
  //     return $response;
  //   });

  //   // TODO PATCH /color/{id}
    
  //   $app->delete('/color/{id:[0-9]+}', function (Request $request, Response $response, array $args) {
  //     $id = $args['id'];
  //     $color = \ApiSudoku\Model\ColorDB::getInstance()->getColorById($id);
  //     if (is_null($color)) {
  //       $response = $response->withStatus(404, 'Color not found');
  //     }
  //     else {
  //       $result = \ApiSudoku\Model\ColorDB::getInstance()->deleteColorById($id);
  //       $response = $response->withStatus($result ? 200 : 500);
  //     }
  //     return $response;
  //   }); 

    }
}