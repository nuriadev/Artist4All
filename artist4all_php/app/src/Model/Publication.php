<?php
namespace Artist4all\Model;

class Publication implements \JsonSerializable {
  private ?int $id;
  private \Artist4all\Model\User $user;
  private ?array $imgsPublication;
  private string $bodyPublication;
  private string $upload_date;
  private ?int $n_likes;
  private ?int $n_comments;
  private int $isLiking;

  public function __construct(
    ?int $id,
    \Artist4all\Model\User $user,
    ?array $imgsPublication,
    string $bodyPublication,
    string $upload_date,
    ?int $n_likes,
    ?int $n_comments,
    int $isLiking
  ) {
    $this->id = $id;
    $this->user = $user;
    $this->imgsPublication = $imgsPublication;
    $this->bodyPublication = $bodyPublication;
    $this->upload_date = $upload_date;
    $this->n_likes = $n_likes;
    $this->n_comments = $n_comments;
    $this->isLiking = $isLiking;
  }

  public function getId(): ?int { return $this->id; }
  public function setId(?int $id) { $this->id = $id; }

  public function getUser(): \Artist4all\Model\User { return $this->user; }
  public function setUser(\Artist4all\Model\User $user) { $this->user = $user; }

  public function getImgsPublication(): ?array { return $this->imgsPublication; }
  public function setImgsPublication(?array $imgsPublication) { $this->imgsPublication = $imgsPublication; }

  public function getBodyPublication(): string { return $this->bodyPublication; }
  public function setBodyPublication(string $bodyPublication) { $this->bodyPublication = $bodyPublication; }

  public function getUploadDatePublication(): string { return $this->upload_date; }

  public function setUploadDatePublication(string $upload_date) { $this->upload_date = $upload_date; }

  public function getLikes(): ?int { return $this->n_likes; }
  public function setLikes(?int $n_likes) { $this->n_likes = $n_likes; }

  public function getComments(): ?int { return $this->n_comments; }
  public function setComments(?int $n_comments) { $this->n_comments = $n_comments;}

  public function isLiking(): ?int { return $this->isLiking; }
  public function setIsLiking(?int $isLiking) { $this->isLiking = $isLiking;}

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Publication {
    return new \Artist4all\Model\Publication(
      $data['id'],
      $data['user'],
      $data['imgsPublication'],
      $data['bodyPublication'],
      $data['upload_date'],
      $data['n_likes'],
      $data['n_comments'],
      $data['isLiking']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'user' => $this->user,
      'imgsPublication' => $this->imgsPublication,
      'bodyPublication' => $this->bodyPublication,
      'upload_date' => $this->upload_date,
      'n_likes' => $this->n_likes,
      'n_comments' => $this->n_comments,
      'isLiking' => $this->isLiking
    ];
  }

  // DAO METHODS

  public static function getPublicationById(int $my_id, int $id): ?\Artist4all\Model\Publication {
    $sql = 'SELECT * FROM publications WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    $publicationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$publicationAssoc) return null;
    $publicationAssoc['user'] = \Artist4all\Model\User::getUserById($publicationAssoc['id_user']);
    $publicationAssoc['n_likes'] = static::countLikes($publicationAssoc['id']);    
    $publicationAssoc['n_comments'] = static::countComments($publicationAssoc['id']);
    $publicationAssoc['isLiking'] = static::likingPublication($my_id, $publicationAssoc['id']);
    $publicationAssoc['imgsPublication'] = static::getPublicationImgs($id);
    $publication = \Artist4all\Model\Publication::fromAssoc($publicationAssoc);
    return $publication;
  }


  // TODO: le pasamos a cada publicacion una variable isLiking para saber si el usuario le da ha dado a like 
  // TODO: y ya podriamos modificar si fuese necesario
  // TODO PASAR TODO POR TOKEN EN VEZ DE POR ID
  public static function getUserPublications(int $my_id, int $id_user): ?array {
    $sql = 'SELECT * FROM publications WHERE id_user=:id_user ORDER BY id DESC';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id_user' => $id_user]);
    $publicationsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$publicationsAssoc) return null;
    $publications = [];
    foreach ($publicationsAssoc as $publicationAssoc) {
      $publicationAssoc['user'] = \Artist4all\Model\User::getUserById($id_user);
      $publicationAssoc['n_likes'] = static::countLikes($publicationAssoc['id']);    
      $publicationAssoc['n_comments'] = static::countComments($publicationAssoc['id']);
      $publicationAssoc['isLiking'] = static::likingPublication($my_id, $publicationAssoc['id']);
      $publicationAssoc['imgsPublication'] = static::getPublicationImgs($publicationAssoc['id']);
      $publications[] = \Artist4all\Model\Publication::fromAssoc($publicationAssoc);
    }
    return $publications;
  }

  public static function getPublicationImgs(int $id): ?array {
    $sql = 'SELECT imgPublication FROM imgs_publications WHERE id_publication=:id_publication';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id_publication' => $id]);
    $imgsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$imgsAssoc) return null;
    $imgsPublication = [];
    foreach ($imgsAssoc as $imgAssoc) {
      $imgsPublication[] = $imgAssoc;
    }
    return $imgsPublication;
  }

  public static function deletePublicationImgs(int $id): bool {
    $sql = "DELETE FROM imgs_publications WHERE id_publication=:id_publication";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute(['id_publication' => $id]);
    return $result;
  }

  public static function persistPublication(Publication $publication): ?\Artist4all\Model\Publication {
    if (is_null($publication->getId())) return static::insertPublication($publication);
    else return static::updatePublication($publication);
  }

  public static function insertPublication(\Artist4all\Model\Publication $publication): ?\Artist4all\Model\Publication {
    $sql = 'INSERT INTO publications VALUES(
        :id,
        :id_user,
        :bodyPublication,
        :upload_date
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $publication->getId(),
      ':id_user' => $publication->getUser()->getId(),
      ':bodyPublication' => $publication->getBodyPublication(),
      ':upload_date' => date('Y-m-d H:i:s')
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $publication->setId($id);
    return $publication;
  }

  public static function updatePublication(\Artist4all\Model\Publication $publication): ?\Artist4all\Model\Publication {
    $sql = 'UPDATE publications SET
        id_user=:id_user,
        bodyPublication=:bodyPublication,
        upload_date=:upload_date
    WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $publication->getId(),
      ':id_user' => $publication->getUser()->getId(),
      ':bodyPublication' => $publication->getBodyPublication(),
      ':upload_date' => date('Y-m-d H:i:s')
    ]);
    if (!$result) return null;
    return $publication;
  }

  public static function insertPublicationImgs(int $id, string $img): bool {
    $sql = 'INSERT INTO imgs_publications VALUES(
        :id,
        :imgPublication,
        :id_publication
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => null,
      ':imgPublication' => 'http://localhost:81/assets/img/' . $img,
      ':id_publication' => $id
    ]);
    return $result;
  }

  public static function deletePublicationById(int $id): bool {
    $sql = "DELETE FROM publications WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute(['id' => $id]);
    return $result;
  }

  public static function persistLike(array $logLike): ?array {
    if (is_null($logLike['id'])) return static::insertLikePublication($logLike);
    else return static::updateLikePublication($logLike);
  }

  public static function insertLikePublication(array $logLike): ?array {
    $sql =  'INSERT INTO users_likes_publications VALUES(
      :id,
      :my_id,
      :id_publication,
      :status_like
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $logLike['id'],
      ':my_id' => $logLike['my_id'],
      ':id_publication' => (int)$logLike['id_publication'],
      ':status_like' => $logLike['status_like']
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $logLike['id'] = $id;
    return $logLike;
  }

  public static function updateLikePublication(array $logLike): ?array {
    $sql = 'UPDATE users_likes_publications SET status_like=:status_like WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => (int) $logLike['id'],
      ':status_like' => $logLike['status_like']
    ]);
    if (!$result) return null;
    return $logLike;
  }

  // TODO: lo haré por separado, contaré los likes y comentarios de las publicaciones en 2 funciones distintas,
  // TODO: luego miraré si le estoy dando like a esa publicación
  public static function likingPublication(int $my_id, int $id_publication): ?int {
    $sql = 'SELECT * FROM users_likes_publications WHERE my_id=:my_id AND id_publication=:id_publication AND status_like=:status_like';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':my_id' => $my_id,
      ':id_publication' => $id_publication,
      ':status_like' => 1
    ]);
    if (!$result) return null;
    $isLiking = $statement->rowCount();
    if ($isLiking == '') $isLiking = 0;
    return $isLiking;
  }

  public static function getLogLikeByUserAndPublication(int $my_id, int $id_publication, int $status_like): ?array {
    $sql = 'SELECT * FROM users_likes_publications WHERE my_id=:my_id AND id_publication=:id_publication AND status_like=:status_like';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':my_id' => $my_id,
      ':id_publication' => $id_publication,
      ':status_like' => $status_like
    ]);
    $logLike = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$logLike) return null;
    return $logLike;
  }

  public static function countLikes(int $id_publication): ?int {
    $sql = 'SELECT * FROM users_likes_publications WHERE id_publication=:id_publication AND status_like=:status_like';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_publication' => $id_publication,
      ':status_like' => 1
    ]);
    if (!$result) return null; 
    $likes = $statement->rowCount();
    return $likes;
  }

  public static function countComments(int $id_publication): ?int {
    $sql = 'SELECT * FROM publication_comments WHERE id_publication=:id_publication';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_publication' => $id_publication
    ]);
    if (!$result) return null; 
    $comments = $statement->rowCount();
    return $comments;
  }
}
