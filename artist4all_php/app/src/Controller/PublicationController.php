<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicationController {
  public static function initRoutes($app) {
    $app->post('/publication', '\Artist4all\Controller\PublicationController:createPublication');
  }

  public function createPublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id_user = trim($data['id_user']);
    $body = trim($data['bodyPublication']); 
    /* $img = trim($data['imgPublication']); */
    $upload_date = trim($data['upload_date']);
    $n_likes = trim($data['n_likes']);
    $n_comments = trim($data['n_comments']);
    $n_views = trim($data['n_views']);
    // todo implementar la /las imgs
    $token = trim($data['token']);
    $publication = new \Artist4all\Model\Publication(null, $id_user, $body, $upload_date, $n_likes, $n_comments, $n_views);
    $result = \Artist4all\Model\PublicationDB::getInstance()->createPublication($publication, $token);
    if (!$result) {
      $response = $response->withStatus(500, 'Error al publicar');  
    } else {
      $response = $response->withStatus(200, 'Publicación creada');
    }
    return $response;
  }
}