<?php

namespace Artist4all\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicationController
{
  public static function initRoutes($app) {
    $app->post('/user/my/publication', '\Artist4all\Controller\PublicationController:createPublication');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/publication', '\Artist4all\Controller\PublicationController:getUserPublications');
    $app->get('/user/{username:[a-zA-Z0-9 ]+}/publication/{id:[0-9 ]+}', '\Artist4all\Controller\PublicationController:getPublicationById');
    // TODO: pasar a patch
    $app->post('/user/my/publication/{id:[0-9 ]+}', '\Artist4all\Controller\PublicationController:editPublication');
    $app->delete('/user/my/publication/{id:[0-9 ]+}', '\Artist4all\Controller\PublicationController:deletePublication');
  }

  public function getPublicationById(Request $request, Response $response, array $args) {
    $authorized = $this->isAuthorizated($request, $response);
    $id = $args['id'];
    $username = $args['username'];
    $publication = \Artist4all\Model\Publication\PublicationDB::getInstance()->getPublicationById($id);
    if (is_null($publication)) $response = $response->withStatus(404, 'Publication not found');
    else $response = $response->withJson($publication);
    return $response;
  }

  public function getUserPublications(Request $request, Response $response, array $args) {
    $authorized = $this->isAuthorizated($request, $response);
    $username = $args['username'];
    $user = \Artist4all\Model\User\UserDB::getInstance()->getUserByUsername($username);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      $publications = \Artist4all\Model\Publication\PublicationDB::getInstance()->getUserPublications($user->getId());
      if (empty($publications)) $response = $response->withStatus(200, 'Without results');
      else $response = $response->withJson($publications);
      return $response;
    }
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

  // todo: view, edit, delete, comentarios, likes
  public function createPublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $publication = $this->validatePersist($data, null, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at publishing');
    else $response = $response->withJson($publication)->withStatus(200, 'Publication created');
    return $response;
  }

  public function editPublication(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $data = $request->getParsedBody();
    $publication = \Artist4all\Model\Publication\PublicationDB::getInstance()->getPublicationById($id);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    }
    if (!isset($data['id_user'])) $data['id_user'] = $publication->getIdUser();
    if (!isset($data['n_likes'])) $data['n_likes'] = $publication->getLikes();
    if (!isset($data['n_comments'])) $data['n_comments'] = $publication->getComments();
    if (!isset($data['upload_date'])) $data['upload_date'] = $publication->getUploadDatePublication();
    if (empty($data['imgsPublication'])) $data['imgsPublication'] = implode(',', \Artist4all\Model\Publication\PublicationDB::getInstance()->getPublicationImgs($publication->getId()));
    else $deleteImgs = \Artist4all\Model\Publication\PublicationDB::getInstance()->deletePublicationImgs($publication->getId()); 
    $publication = $this->validatePersist($data, $id, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at editing publication');
    else $response = $response->withJson($publication)->withStatus(200, 'Publication edited');
    return $response;
  }

  public function deletePublication(Request $request, Response $response, array $args) {
    $data = $request->getParsedBody();
    $id = $args['id'];
    $publication = \Artist4all\Model\Publication\PublicationDB::getInstance()->getPublicationById($id);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
    } else {
      $result = \Artist4all\Model\Publication\PublicationDB::getInstance()->deletePublicationById($id);
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

  public function removeLikePublication(Request $request, Response $response, array $args) {
    $id = $args['id'];
    $result = \Artist4all\Model\UserDB::getInstance()->unfollowUser($id);
    if(!$result) $response = $response->withStatus(500, 'Error at unfollwing');
    else $response = $response->withJson($result)->withStatus(200, 'Removed from your followed list');
    return $response;
  } */

  private function validatePersist($data, $id, $response) {
    //todo: mirar si hay que validar algo
    $data['imgsPublication'] = json_decode($data['imgsPublication']);
    
    //!No mueve la img seleccionada al apartado querido, lo mismo con los usuarios
    //todo validar tamaño, max, formato, etc. 
    $folderUrl = "assets/img" . DIRECTORY_SEPARATOR;
    foreach ($_FILES as $file) {
      $nombreImg= $file["tmp_name"]; 
      $urlImg = $folderUrl.$file["name"];
      move_uploaded_file($nombreImg, $urlImg);
    }

    if (empty($data["imgsPublication"])) $data['imgsPublication'] = null;
    // todo validar que no sea empty
    $token = trim($data['token']);
    $data['id'] = $id;
    $publication = \Artist4all\Model\Publication\Publication::fromAssoc($data);
    $canPublish = \Artist4all\Model\Publication\PublicationDB::getInstance()->canPublish($publication, $token);
    if (!$canPublish) {
      $response = $response->withStatus(500, 'Error at publishing');
      return $response;
    } else {
      $publication = \Artist4all\Model\Publication\PublicationDB::getInstance()->persistPublication($publication);
      if (!empty($publication->getImgsPublication())) {
        foreach ($publication->getImgsPublication() as $img) {
          $resultImg = \Artist4all\Model\Publication\PublicationDB::getInstance()->insertPublicationImgs($publication->getId(), $img);
          if (!$resultImg) {
            $response = $response->withStatus(500, 'Error at publishing');
            return $response;
          }
        }
      }
      $publication = \Artist4all\Model\Publication\PublicationDB::getInstance()->getPublicationById($publication->getId());
      return $publication; 
    }  
  }
}
