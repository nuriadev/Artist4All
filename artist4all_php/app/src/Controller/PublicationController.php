<?php

namespace Artist4all\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicationController
{
  public static function initRoutes($app)
  {
    $app->post('/user/{id:[0-9 ]+}/publication', '\Artist4all\Controller\PublicationController:createPublication');
    // TODO: pasar a patch
    $app->post('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:editPublication');
    $app->delete('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:deletePublication');
    $app->get('/user/{id:[0-9 ]+}/publication', '\Artist4all\Controller\PublicationController:getUserPublications');
    $app->get('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:getPublicationById');

    $app->post('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:addLike');
    $app->delete('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:removeLike');
  }

  public function getPublicationById(Request $request, Response $response, array $args)
  {
    $id_publication = $args['id_publication'];
    $id_user = $args['id_user'];
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $publication = \Artist4all\Model\Publication::getPublicationById($id_publication);
    if (is_null($publication)) $response = $response->withStatus(404, 'Publication not found');
    else $response = $response->withJson($publication);
    return $response;
  }

  public function getUserPublications(Request $request, Response $response, array $args)
  {
    $id = $args['id'];
    $user = \Artist4all\Model\User::getUserById($id);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    } else {
      $publications = \Artist4all\Model\Publication::getUserPublications($user->getId());
      if (empty($publications)) $response = $response->withStatus(204, 'Without results');
      else $response = $response->withJson($publications);
      return $response;
    }
  }

  // todo: view, edit, delete, comentarios, likes
  public function createPublication(Request $request, Response $response, array $args)
  {
    $id_user = $args['id'];
    $data = $request->getParsedBody();
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $publication = $this->validatePersist($data, null, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at publishing');
    else $response = $response->withJson($publication)->withStatus(201, 'Publication created');
    return $response;
  }

  public function editPublication(Request $request, Response $response, array $args)
  {
    $id_user = $args['id_user'];
    $id_publication = $args['id_publication'];
    $data = $request->getParsedBody();
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $publication = \Artist4all\Model\Publication::getPublicationById($id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    }
    $data['id_user'] = $id_user;
    if (!isset($data['n_likes'])) $data['n_likes'] = $publication->getLikes();
    if (!isset($data['n_comments'])) $data['n_comments'] = $publication->getComments();
    if (!isset($data['upload_date'])) $data['upload_date'] = $publication->getUploadDatePublication();
    if (empty($data['imgsPublication'])) $data['imgsPublication'] = implode(',', \Artist4all\Model\Publication::getPublicationImgs($publication->getId()));
    else $deleteImgs = \Artist4all\Model\Publication::deletePublicationImgs($publication->getId());
    $publication = $this->validatePersist($data, $id_publication, $response);
    if (is_null($publication)) $response = $response->withStatus(500, 'Error at editing publication');
    else $response = $response->withJson($publication)->withStatus(200, 'Publication edited');
    return $response;
  }

  public function deletePublication(Request $request, Response $response, array $args)
  {
    $id_user = $args['id_user'];
    $id_publication = $args['id_publication'];
    $user = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($user)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    $publication = \Artist4all\Model\Publication::getPublicationById($id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
    } else {
      $result = \Artist4all\Model\Publication::deletePublicationById($id_publication);
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
  */
  public function addLike(Request $request, Response $response, array $args)
  {
    $id_user = $args['my_id'];
    $data = $request->getParsedBody();
    $id_publisher = $data['id_user'];
    $publisherUser = \Artist4all\Model\User::getUserById($id_publisher);
    $likeGiverUser = \Artist4all\Model\User::getUserById($id_user);
    if (is_null($publisherUser) || is_null($likeGiverUser)) {
      $response = $response->withStatus(404, 'User not found');
      return $response;
    }
    // $result = \Artist4all\Model\User\UserDB::getInstance()->followUser($follower, $followed);
    // if(is_null($result)) $response = $response->withStatus(400, 'Error at following');
    // else $response = $response->withJson($result)->withStatus(200, 'Added to your followed list');
    // return $response;
  }

  public function removeLike(Request $request, Response $response, array $args)
  {
    // $id = $args['id'];
    // $result = \Artist4all\Model\User\UserDB::getInstance()->unfollowUser($id);
    // if(!$result) $response = $response->withStatus(500, 'Error at unfollwing');
    // else $response = $response->withJson($result)->withStatus(200, 'Removed from your followed list');
    // return $response;
  }

  private function validatePersist($data, $id, $response)
  {
    //todo: mirar si hay que validar algo
    $data['imgsPublication'] = json_decode($data['imgsPublication']);

    //!No mueve la img seleccionada al apartado querido, lo mismo con los usuarios
    //todo validar tamaño, max, formato, etc. 
    $folderUrl = "assets/img" . DIRECTORY_SEPARATOR;
    foreach ($_FILES as $file) {
      $nombreImg = $file["tmp_name"];
      $urlImg = $folderUrl . $file["name"];
      move_uploaded_file($nombreImg, $urlImg);
    }

    if (empty($data["imgsPublication"])) $data['imgsPublication'] = null;
    $data['id'] = $id;
    $publication = \Artist4all\Model\Publication::fromAssoc($data);
    $publication = \Artist4all\Model\Publication::persistPublication($publication);
    if (!empty($publication->getImgsPublication())) {
      foreach ($publication->getImgsPublication() as $img) {
        $resultImg = \Artist4all\Model\Publication::insertPublicationImgs($publication->getId(), $img);
        if (!$resultImg) {
          $response = $response->withStatus(500, 'Error at publishing');
          return $response;
        }
      }
    }
    $publication = \Artist4all\Model\Publication::getPublicationById($publication->getId());
    return $publication;
  }
}
