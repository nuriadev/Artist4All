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

    $app->get('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:isPublicationLiked');
    $app->post('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:likePublication');
    $app->delete('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:updateLikeStatus');
  }

  public function getPublicationById(Request $request, Response $response, array $args)
  {
    $id_publication = $args['id_publication'];
    $id_user = $args['id_user'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByIdSummary($id_publication, $response);
    $response = $response->withJson($publication);
    return $response;
  }

  public static function getPublicationByIdSummary(int $id_publication, Response $response)
  {
    $publication = \Artist4all\Model\Publication::getPublicationById($id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    } else {
      return $publication;
    }
  }

  public function getUserPublications(Request $request, Response $response, array $args)
  {
    $id = $args['id'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id, $response);
    $publications = \Artist4all\Model\Publication::getUserPublications($user->getId());
    if (empty($publications)) $response = $response->withStatus(204, 'Without results');
    else $response = $response->withJson($publications);
    return $response;
  }

  // todo: view, edit, delete, comentarios, likes
  public function createPublication(Request $request, Response $response, array $args)
  {
    $id_user = $args['id'];
    $data = $request->getParsedBody();
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
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
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByIdSummary($id_publication, $response);
    $data['id_user'] = $user->getId();
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
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByIdSummary($id_publication, $response);
    $result = \Artist4all\Model\Publication::deletePublicationById($publication->getId());
    if (!$result) $response = $response->withStatus(500, 'Error at removing publication');
    else $response = $response->withStatus(200, 'Publication deleted');
    return $response;
  }

  public function isPublicationLiked(Request $request, Response $response, array $args)
  {
    $my_id = $args['my_id'];
    $id_publication = $args['id_publication'];
    $publication = \Artist4all\Controller\PublicationController::getPublicationByIdSummary($id_publication, $response);
    $id_publisher = $publication->getIdUser();
    $me = \Artist4all\Controller\UserController::getUserByIdSummary($my_id, $response);
    $publisherUser = \Artist4all\Controller\UserController::getUserByIdSummary($id_publisher, $response);

    $logLike = \Artist4all\Model\Publication::isPublicationLiked($me->getId(), $publisherUser->getId(), $id_publication);
    if (is_null($logLike)) $response = $response->withStatus(204, 'Publication not liked');
    else  $response = $response->withJson($logLike);
    return $response;
  }

  public function likePublication(Request $request, Response $response, array $args)
  {
    $data = $request->getParsedBody();
    if (!isset($data['id'])) $id = null;
    else $id = $data['id'];
    return $this->insertOrUpdateLike($args, $data, $id, $response);
  }

  public function updateLikeStatus(Request $request, Response $response, array $args)
  {
    $id = $args['id_like'];
    $data = $request->getParsedBody();
    return $this->insertOrUpdateLike($args, $data, $id, $response);
  }

  private function insertOrUpdateLike($args, $data, $id, $response)
  {
    $my_id = $args['my_id'];
    $id_publisher = $data['id_publisher'];
    $publisherUser = \Artist4all\Controller\UserController::getUserByIdSummary($id_publisher, $response);
    $me = \Artist4all\Controller\UserController::getUserByIdSummary($my_id, $response);
    $logLike = array(
      'id' => $id,
      'my_id' => $me->getId(),
      'id_publisher' => $publisherUser->getId(),
      'id_publication' => $args['id_publication'],
      'status_like' => (int) $data['status_like'],
    );
    $logLike = \Artist4all\Model\Publication::persistLike($logLike);
    if (is_null($logLike)) {
      $response = $response->withStatus(400, 'Error at liking the publication');
    } else {
      $response = $response->withJson($logLike);
    }
    return $response;
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
    $publication = static::getPublicationByIdSummary($publication->getId(), $response);
    return $publication;
  }
}
