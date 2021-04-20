<?php
namespace Artist4all\Controller;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class CommentController {
  public static function initRoutes($app) {
    $app->get('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}/comment', '\Artist4all\Controller\CommentController:getPublicationComments');
    $app->post('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}/comment', '\Artist4all\Controller\CommentController:postComment');

    $app->get('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}/comment/{id_comment:[0-9 ]+}', '\Artist4all\Controller\CommentController:getCommentSubcomments');
    // TODO: pasar a patch
    $app->post('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}/comment/{id_comment:[0-9 ]+}', '\Artist4all\Controller\CommentController:editComment');
    $app->delete('/user/{id_user:[0-9 ]+}/publication/{id_publication:[0-9 ]+}/comment/{id_comment:[0-9 ]+}', '\Artist4all\Controller\CommentController:deleteComment');
  }


  public static function getPublicationComments(Request $request, Response $response, array $args) {
    $id_publication = $args['id_publication'];
    $comments = \Artist4all\Model\Comment::getPublicationComments($id_publication);
    if (is_null($comments)) $response = $response->withStatus(204, 'No comments collected'); 
    else $response = $response->withJson($comments);
    return $response; 
  }

  public function getCommentSubcomments(Request $request, Response $response, array $args) {
    $id_comment = $args['id_comment'];
    $subComments = \Artist4all\Model\Comment::getCommentSubcomments($id_comment);
    if (is_null($subComments)) $response = $response->withStatus(204, 'No subcomments collected'); 
    else $response = $response->withJson($subComments);
    return $response; 
  }
  
  public function postComment(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $data = $request->getParsedBody();  
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $data['user'] = $user;
    $data['id_publication'] = $args['id_publication'];
    if ($data['id_comment_reference'] == null) $data['id_comment_reference'] = null;
    else $data['id_comment_reference'] = intval($data['id_comment_reference']); 
    $comment = $this->validatePersist($request, $data, null, $response);
    if (is_null($comment)) $response = $response->withStatus(500, 'Error at commenting');
    else $response = $response->withJson($data)->withStatus(201, 'Comment uploaded');
    return $response; 
  }

  public function editComment(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $data = $request->getParsedBody();
    $id_comment = $data['id_comment'];
    $comment = \Artist4all\Model\Comment::getCommentById($id_comment);
    $user = \Artist4all\Controller\UserController::getUserByIdSummary($id_user, $response);
    $data['user'] = $user;
    if (!isset($data['isEdited'])) $data['isEdited'] = $comment->isEdited();
    if (!isset($data['comment_date'])) $data['comment_date'] = $comment->getCommentDate();
    $comment = $this->validatePersist($request, $data, $id_comment, $response);
    if (is_null($comment)) $response = $response->withStatus(500, 'Error at editing comment');
    else $response = $response->withJson($comment)->withStatus(200, 'Comment edited');
    return $response;
  }

  public function deleteComment(Request $request, Response $response, array $args) {
    $id_user = $args['id_user'];
    $id_comment = $args['id_comment'];
    $result = \Artist4all\Model\Comment::deleteCommentById($id_comment->getId());
    if (!$result) $response = $response->withStatus(500, 'Error at deleting comment');
    else $response = $response->withStatus(200, 'Comment deleted');
    return $response;
  }

  private function validatePersist($request, $data, $id, $response) {
      //todo: Validar los campos
    $data['id'] = $id;
    
    $comment = \Artist4all\Model\Comment::fromAssoc($data);
    $comment = \Artist4all\Model\Comment::persistComment($comment);
    $comment = static::getCommentById($comment->getId(), $response);
    return $comment; 
  }

  public static function getCommentById(int $id_comment, Response $response) {
    $comment = \Artist4all\Model\Comment::getCommentById($id_comment);
    if (is_null($comment)) {
      $response = $response->withStatus(404, 'Comment not found');
      return $response;
    } else {
      return $comment;
    }
  }

}
