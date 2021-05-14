<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class PublicationController {
  public static function initRoutes($app) {
    $app->post('/user/{id_user:[0-9 ]+}/publication', '\Artist4all\Controller\PublicationController:createPublication');
    // TODO: pasar a patch
    $app->post('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:editPublication');
    $app->delete('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:deletePublication');
    $app->get('/user/{id_user:[0-9 ]+}/publication', '\Artist4all\Controller\PublicationController:getUserPublications');
    $app->get('/user/{id_user:[0-9 ]+}/followedPublications', '\Artist4all\Controller\PublicationController:getFollowedPublications');  
    $app->get('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:getPublicationById');

    $app->post('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}', '\Artist4all\Controller\PublicationController:likePublication');
    $app->post('/user/{my_id:[0-9 ]+}/like/publication/{id_publication:[0-9 ]+}/update', '\Artist4all\Controller\PublicationController:updateLikeStatus');
  }

  public function getPublicationById(Request $request, Response $response, array $args) {
    $id_publication = $args['id_publication'];
    $id_user = $args['id_user'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByUser($request, $id_publication, $response);
    $response = $response->withJson($publication);
    return $response;
  }

  public function getFollowedPublications(Request $request, Response $response, array $args) {
    $id = $args['id_user'];
    $me = \Artist4all\Controller\UserController::getUserByIdSummary($id, $response);
    $users = \Artist4all\Model\User::getFollowed($me->getId());
    if (empty($users)) {
      $response = $response->withStatus(204, 'No users collected');
      return $response;
    }
    $publications = [];
    foreach ($users as $user) {
      $values = \Artist4all\Model\Publication::getUserPublications($me->getId(), $user->getId());
      foreach ($values as $value) {
        $publications[] = $value;
      }
    }
    array_values($publications);
    rsort($publications);
    if (empty($publications)) $response = $response->withStatus(204, 'No publications collected');
    else $response = $response->withJson($publications);
    return $response;
  }

  public static function getPublicationByUser(Request $request, int $id_publication, Response $response) {
    $token = $request->getHeader('Authorization')[0];
    $token = trim(substr($token, 6));
    $me = \Artist4all\Model\User::getUserByToken($token);
    $publication = \Artist4all\Model\Publication::getPublicationById($me->getId(), $id_publication);
    if (is_null($publication)) {
      $response = $response->withStatus(404, 'Publication not found');
      return $response;
    } else {
      return $publication;
    }
  }
  
  public function getUserPublications(Request $request, Response $response, array $args) {
    $id = $args['id_user'];
    $token = $request->getHeader('Authorization')[0];
    $token = trim(substr($token, 6));
    $me = \Artist4all\Model\User::getUserByToken($token);
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id, $response);
    $publications = \Artist4all\Model\Publication::getUserPublications($me->getId(), $user->getId());
    if (empty($publications)) $response = $response->withStatus(204, 'Without results');
    else $response = $response->withJson($publications);
    return $response;
  }

  public function createPublication(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $data = $request->getParsedBody();  
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $data['user'] = $user; 
    return $this->validatePersist($request, $data, null, $response, 'publish');
  }

  public function editPublication(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $id_publication = $args['id_publication'];
    $data = $request->getParsedBody();
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByUser($request, $id_publication, $response);
    $data['user'] = $user;
    $data['isLiking'] = 0;
    if (!isset($data['isEdited'])) $data['isEdited'] = $publication->isEdited();
    if (!isset($data['n_likes'])) $data['n_likes'] = $publication->getLikes();
    if (!isset($data['n_comments'])) $data['n_comments'] = $publication->getComments();
    if (!isset($data['upload_date'])) $data['upload_date'] = $publication->getUploadDatePublication();
    else $deleteImgs = \Artist4all\Model\Publication::deletePublicationImgs($publication->getId());
    return $this->validatePersist($request, $data, $id_publication, $response, 'edit');
  }

  public function deletePublication(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $id_publication = $args['id_publication'];
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $publication = static::getPublicationByUser($request, $id_publication, $response);
    $result = \Artist4all\Model\Publication::deletePublicationById($publication->getId());
    if (!$result) $response = $response->withStatus(500, 'Error at removing publication');
    else $response = $response->withStatus(200, 'Publication deleted');
    return $response;
  }

  public function likePublication(Request $request, Response $response, array $args) {
    $my_id = $args['my_id'];
    $id_publication = $args['id_publication'];
    $logLike = \Artist4all\Model\Publication::getLogLikeByUserAndPublication($my_id, $id_publication, 0);
    if (is_null($logLike)) { 
      $logLike = array(
        'id' => null,
        'my_id' => $my_id,
        'id_publication' => $id_publication,
        'status_like' => 1,
      );
    } else {
      $logLike['status_like'] = 1;
    }
    $logLike = \Artist4all\Model\Publication::persistLike($logLike);
    if (is_null($logLike)) $response = $response->withStatus(400, 'Error at liking the publication');
    else $response = $response->withJson($logLike);
    return $response;
  }

  public function updateLikeStatus(Request $request, Response $response, array $args) {
    $my_id = $args['my_id'];
    $id_publication = $args['id_publication'];
    $logLike = \Artist4all\Model\Publication::getLogLikeByUserAndPublication($my_id, $id_publication, 1);
    if (is_null($logLike)) $logLike['status_like'] = 1;
    else $logLike['status_like'] = 0; 
    $logLike = \Artist4all\Model\Publication::persistLike($logLike);
    if (is_null($logLike)) $response = $response->withStatus(400, 'Error at removing like');
    else $response = $response->withJson($logLike);
    return $response;
  }

  private function validatePersist($request, $data, $id, $response, $type) {
    $data['id'] = $id;
    $bodyPublication = $data['bodyPublication'];
    if (strlen($bodyPublication) > 255) {
      $response = $response->withStatus(400, 'Maximum character length surpassed');
      return $response;
    }
    $uploadedFiles = $request->getUploadedFiles();
    $data['imgsPublication'] = $uploadedFiles;
    $publication = \Artist4all\Model\Publication::fromAssoc($data);
    $publication = \Artist4all\Model\Publication::persistPublication($publication);
    \Artist4all\Model\Publication::deletePublicationImgs($publication->getId());
    $filePath = '/var/www/html/assets/img/';
    if (!empty($_FILES) || $_FILES != null) {
        $allowed = array('image/gif', 'image/png', 'image/jpg', 'image/jpeg');
        foreach ($_FILES as $file) {
          $finfo = finfo_open(FILEINFO_MIME_TYPE);
          if (!in_array(finfo_file($finfo, $file['tmp_name']), $allowed)) {
            $response = $response->withStatus(400, 'File is not an image');
            return $response;
          }     
          finfo_close($finfo);

          $imgName = $file["tmp_name"]; 
          $pathImg = $filePath.$file["name"];
          if (!file_exists($pathImg)) move_uploaded_file($imgName, $pathImg);   
          else continue;          
        }
        foreach ($_FILES as $file) {        
          $resultImg = \Artist4all\Model\Publication::insertPublicationImgs($publication->getId(), $file['name']);
          if (!$resultImg) {
            $response = $response->withStatus(500, 'Error at setting images');
            return $response;
          }  
        } 
    }
    $publication = static::getPublicationByUser($request, $publication->getId(), $response);
    if (is_null($publication)) {
      if (is_null($id)) $response = $response->withStatus(500, 'Error at publishing');         
      else $response = $response->withStatus(500, 'Error at editing publication');
      return $response;
    }
    if ($type == 'publish') $response = $response->withJson($publication)->withStatus(201, 'Publication created');
    else if ($type == 'edit') $response = $response->withJson($publication)->withStatus(200, 'Publication edited');  
    return $response;
  }

}
