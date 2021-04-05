<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicationController {
  public static function initRoutes($app) {
    $app->post('/publication', '\Artist4all\Controller\PublicationController:createPublication');
  }

  public function getPublicationById(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data['id']);
    $publication = \Artist4all\Model\PublicationDB::getInstance()->getPublicationById($id);
    if (is_null($publication)) $response = $response->withStatus(404, 'Publication not found');
    else $response = $response->withJson($publication);
    return $response;
  }
  
  // todo: view, edit, delete, comentarios, likes
  public function createPublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
/*     $data['id'] = null;
    $data['imgsPublication'] = json_decode($data['imgsPublication']);
    $folderUrl = "assets/img".DIRECTORY_SEPARATOR;
    foreach ($_FILES as $file) {
        $nombreImg = $file["tmp_name"];
        $urlImg = $folderUrl.$file["name"];
        move_uploaded_file($nombreImg, $urlImg);   
    }
    if (empty($data["imgsPublication"])) $data['imgsPublication'] = null;
    // todo validar que no sea empty
    $token = trim($data['token']);
    $publication = \Artist4all\Model\Publication::fromAssoc($data);
    $isAuthorizated = \Artist4all\Model\PublicationDB::getInstance()->isAuthorizated($publication, $token);
    if(!$isAuthorizated) {
      $response = $response->withStatus(500, 'Error at publishing');  
      return $response;
    } else {
      $newPublication = \Artist4all\Model\PublicationDB::getInstance()->createPublication($publication);
      if (!empty($newPublication->getImgsPublication())) {
        foreach ($newPublication->getImgsPublication() as $img) {
          $resultImg = \Artist4all\Model\PublicationDB::getInstance()->insertPublicationImgs($newPublication->getId(), $img);
          if (!$resultImg) {
            $response = $response->withStatus(500, 'Error at publishing');  
            return $response;
          }
        } 
      }    
      if (is_null($publication)) $response = $response->withStatus(500, 'Error at publishing');  
      else $response = $response->withStatus(200, 'Publication created');
      return $response;
    } */
    $publication = $this->validatePersist($data, null, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at publishing');  
    else $response = $response->withStatus(200, 'Publication created');
    return $response;
  }

  public function editPublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data["id"]);
    $publication = $this->validatePersist($data, $id, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at editing publication');  
    else $response = $response->withStatus(200, 'Publication edited');
    return $response;
  }

  public function removePublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = trim($data['id']);
    $publication = \Artist4all\Model\PublicationDB::getInstance()->getPublicationById($id);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
    } else {
      $result = \Artist4all\Model\PublicationDB::getInstance()->deletePublicationById($id);
      if (!$result) $response = $response->withStatus(500, 'Error at removing publication');
      else $response = $response->withStatus(200, 'Publication deleted');
    }
    return $response;
  }

 /*  public function isLikingThatPublication(Request $request, Response $response, array $args) {
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

  public function likePublication(Request $request, Response $response, array $args) {
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

  public function dislikePublication(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $result = \Artist4all\Model\UserDB::getInstance()->unfollowUser($id);
    if(!$result) $response = $response->withStatus(500, 'Error at unfollwing');
    else $response = $response->withJson($result)->withStatus(200, 'Removed from your followed list');
    return $response;
  } */

  private function validatePersist($data, $id, $response) {
    //todo: mirar si hay que validar algo
    $data['imgsPublication'] = json_decode($data['imgsPublication']);
    $folderUrl = "assets/img".DIRECTORY_SEPARATOR;
    foreach ($_FILES as $file) {
        $nombreImg = $file["tmp_name"];
        $urlImg = $folderUrl.$file["name"];
        move_uploaded_file($nombreImg, $urlImg);   
    }
    if (empty($data["imgsPublication"])) $data['imgsPublication'] = null;
    // todo validar que no sea empty
    $token = trim($data['token']);
    $data['id'] = $id;
    $publication = \Artist4all\Model\Publication::fromAssoc($data);
    $isAuthorizated = \Artist4all\Model\PublicationDB::getInstance()->isAuthorizated($publication, $token);
    if(!$isAuthorizated) {
      $response = $response->withStatus(500, 'Error at publishing');  
      return $response;
    } else {
      $publication = \Artist4all\Model\PublicationDB::getInstance()->persistPublication($publication);
      if (!empty($publication->getImgsPublication())) {
        foreach ($publication->getImgsPublication() as $img) {
          $resultImg = \Artist4all\Model\PublicationDB::getInstance()->insertPublicationImgs($newPublication->getId(), $img);
          if (!$resultImg) {
            $response = $response->withStatus(500, 'Error at publishing');  
            return $response;
          }
        } 
      }    
      return $publication; 
    }
  }
}