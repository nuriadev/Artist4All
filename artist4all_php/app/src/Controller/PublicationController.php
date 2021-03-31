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
    $data['id'] = null;
    $data['imgsPublication'] = json_decode($data['imgsPublication']);
    $folderUrl = "assets/img".DIRECTORY_SEPARATOR;
    foreach ($_FILES as $file) {
        $nombreImg = $file["tmp_name"];
        $urlImg = $folderUrl.$file["name"];
        move_uploaded_file($nombreImg, $urlImg);   
    }
    if (empty($data["imgsPublication"])) $data['imgsPublication'] = null;
    // todo implementar las imgs 
    // todo validar que no sea empty
    $token = trim($data['token']);
    $publication = \Artist4all\Model\Publication::fromAssoc($data);
    $isAuthorizated = \Artist4all\Model\PublicationDB::getInstance()->isAuthorizated($publication, $token);
    if(!$isAuthorizated) {
      $response = $response->withStatus(500, 'Error al publicar');  
      return $response;
    } else {
      $newPublication = \Artist4all\Model\PublicationDB::getInstance()->createPublication($publication);
      if (!empty($newPublication->getImgsPublication)) {
        foreach ($newPublication->getImgsPublication() as $img) {
          $resultImg = \Artist4all\Model\PublicationDB::getInstance()->insertPublicationImgs($newPublication->getId(), $img);
          if (!$resultImg) {
            $response = $response->withStatus(500, 'Error al publicar');  
            return $response;
          }
        } 
      }    
      if (is_null($publication)) $response = $response->withStatus(500, 'Error al publicar');  
      else $response = $response->withStatus(200, 'Publicación creada');
      return $response;
    }
    
  }
}