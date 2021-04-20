<?php
namespace Artist4all\Model;

class Comment implements \JsonSerializable{
  private ?int $id;
  private \Artist4all\Model\User $user;
  private string $bodyComment;
  private int $isEdited;
  private string $comment_date;
  private int $id_publication;
  private ?int $id_comment_reference;


  public function __construct(
    ?int $id,
    \Artist4all\Model\User $user,
    string $bodyComment,
    int $isEdited,
    string $comment_date,
    int $id_publication,
    ?int $id_comment_reference
  ) {
    $this->id = $id;
    $this->user = $user;
    $this->bodyComment = $bodyComment;
    $this->isEdited = $isEdited;
    $this->comment_date = $comment_date;
    $this->id_publication = $id_publication;
    $this->id_comment_reference = $id_comment_reference;
  }

  public function getId(): ?int { return $this->id; }
  public function setId(?int $id) { $this->id = $id; }

  public function getUser(): \Artist4all\Model\User { return $this->user; }
  public function setUser(\Artist4all\Model\User $user) { $this->user = $user; }

  public function getBodyComment(): ?string { return $this->bodyComment; }
  public function setBodyComment(?string $bodyComment){ $this->bodyComment = $bodyComment; }

  public function isEdited(): int { return $this->isEdited; }
  public function setIsEdited(int $isEdited) { $this->isEdited = $isEdited; }

  public function getCommentDate(): string { return $this->comment_date; }
  public function setCommentDate(string $comment_date) { $this->comment_date = $comment_date; }

  public function getIdPublication(): ?int { return $this->id_publication; }
  public function setIdPublication(?int $id_publication) { $this->id_publication = $id_publication; }

  public function getIdCommentReference(): ?int { return $this->id_comment_reference; }
  public function setIdCommentReference(?int $id_comment_reference) { $this->id_comment_reference = $id_comment_reference; }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Comment {
    return new \Artist4all\Model\Comment(
      $data['id'],
      $data['user'],
      $data['bodyComment'],
      $data['isEdited'],
      $data['comment_date'],
      $data['id_publication'],
      $data['id_comment_reference']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'user' => $this->user,
      'bodyComment' => $this->bodyComment,
      'isEdited' => $this->isEdited,
      'comment_date' => $this->comment_date,
      'id_publication' => $this->id_publication,
      'id_comment_reference' => $this->id_comment_reference
    ];
  }

  public static function getCommentById(int $id): ?\Artist4all\Model\Comment {
    $sql = 'SELECT * FROM publication_comments WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    $commentAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$commentAssoc) return null;
    $commentAssoc['user'] = \Artist4all\Model\User::getUserById($commentAssoc['id_user']);
    $comment = \Artist4all\Model\Comment::fromAssoc($commentAssoc);
    return $comment;
  }

  public static function getPublicationComments(int $id): ?array {
    $sql = 'SELECT * FROM publication_comments WHERE id=:id AND id_comment_reference=:id_comment_reference ORDER BY id DESC';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $id,
      ':id_comment_reference' => null
    ]);
    $commentsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$commentsAssoc) return null;
    $comments = [];
    foreach ($commentsAssoc as $commentAssoc) {
      $comments[] = \Artist4all\Model\Publication::fromAssoc($commentAssoc);
    }
    return $comments;
  }

  public static function getCommentSubcomments(int $id): ?array {
    $sql = 'SELECT * FROM publication_comments WHERE id_comment_reference=:id_comment_reference ORDER BY id DESC';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id_comment_reference' => $id]);
    $subCommentsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$subCommentsAssoc) return null;
    $subComments = [];
    foreach ($subCommentsAssoc as $subCommentAssoc) {
      $subComments[] = \Artist4all\Model\Publication::fromAssoc($subCommentAssoc);
    }
    return $subComments;
  }

  public static function persistComment(Comment $comment): ?\Artist4all\Model\Comment {
    if (is_null($comment->getId())) return static::postComment($comment);
    else return static::editComment($comment);
  }

  public static function postComment(\Artist4all\Model\Comment $comment): ?\Artist4all\Model\Comment {
    $sql = 'INSERT INTO publication_comments VALUES(
        :id,
        :id_user,
        :bodyComment,
        :isEdited,
        :comment_date, 
        :id_publication,
        :id_comment_reference
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $comment->getId(),
      ':id_user' => $comment->getUser()->getId(),
      ':bodyComment' => $comment->getBodyComment(),
      ':isEdited' => $comment->isEdited(),
      ':comment_date' => date('Y-m-d H:i:s'),  
      ':id_publication' => $comment->getIdPublication(), 
      ':id_comment_reference' => $comment->getIdCommentReference() == null ? null : $comment->getIdCommentReference()
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $comment->setId($id);
    return $comment;
  }

  public static function editComment(\Artist4all\Model\Comment $comment): ?\Artist4all\Model\Comment {
    $sql = 'UPDATE publication_comments SET
        id_user=:id_user,
        bodyComment=:bodyComment,
        isEdited=:isEdited,
        comment_date=:comment_date
    WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $comment->getId(),
      ':id_user' => $comment->getUser()->getId(),
      ':bodyComment' => $comment->getBodyComment(), 
      ':isEdited' => $comment->isEdited(),
      ':comment_date' => date('Y-m-d H:i:s')
    ]);
    if (!$result) return null;
    return $comment;
  }

  public static function deleteCommentById(int $id): bool {
    $sql = "DELETE FROM publication_comments WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute(['id' => $id]);
    return $result;
  }
}