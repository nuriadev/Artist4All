<?php

namespace Artist4all\Model;

class Publication implements \JsonSerializable
{
  private ?int $id;
  private int $id_user;
  private ?array $imgsPublication;
  private string $bodyPublication;
  private string $upload_date;
  private int $n_likes;
  private int $n_comments;

  public function __construct(
    ?int $id,
    int $id_user,
    ?array $imgsPublication,
    string $bodyPublication,
    string $upload_date,
    int $n_likes,
    int $n_comments
  ) {
    $this->id = $id;
    $this->id_user = $id_user;
    $this->imgsPublication = $imgsPublication;
    $this->bodyPublication = $bodyPublication;
    $this->upload_date = $upload_date;
    $this->n_likes = $n_likes;
    $this->n_comments = $n_comments;
  }

  public function getId(): ?int
  {
    return $this->id;
  }

  public function setId(?int $id)
  {
    $this->id = $id;
  }

  public function getIdUser(): int
  {
    return $this->id_user;
  }

  public function setIdUser(int $id_user)
  {
    $this->id_user = $id_user;
  }

  public function getImgsPublication(): ?array
  {
    return $this->imgsPublication;
  }

  public function setImgsPublication(?array $imgsPublication)
  {
    $this->imgsPublication = $imgsPublication;
  }

  public function getBodyPublication(): string
  {
    return $this->bodyPublication;
  }

  public function setBodyPublication(string $bodyPublication)
  {
    $this->bodyPublication = $bodyPublication;
  }

  public function getUploadDatePublication(): string
  {
    return $this->upload_date;
  }

  public function setUploadDatePublication(string $upload_date)
  {
    $this->upload_date = $upload_date;
  }

  public function getLikes(): int
  {
    return $this->n_likes;
  }

  public function setLikes(int $n_likes)
  {
    $this->n_likes = $n_likes;
  }

  public function getComments(): int
  {
    return $this->n_comments;
  }

  public function setComments(int $n_comments)
  {
    $this->n_comments = $n_comments;
  }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Publication
  {
    return new \Artist4all\Model\Publication(
      $data['id'],
      $data['id_user'],
      $data['imgsPublication'],
      $data['bodyPublication'],
      $data['upload_date'],
      $data['n_likes'],
      $data['n_comments']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize()
  {
    return [
      'id' => $this->id,
      'id_user' => $this->id_user,
      'imgsPublication' => $this->imgsPublication,
      'bodyPublication' => $this->bodyPublication,
      'upload_date' => $this->upload_date,
      'n_likes' => $this->n_likes,
      'n_comments' => $this->n_comments
    ];
  }

  // DAO METHODS

  public static function getPublicationById(int $id): ?\Artist4all\Model\Publication
  {
    $sql = 'SELECT * FROM publications WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    $publicationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$publicationAssoc) return null;
    $imgsPublication = static::getPublicationImgs($id);
    $publicationAssoc['imgsPublication'] = $imgsPublication;
    $publication = \Artist4all\Model\Publication::fromAssoc($publicationAssoc);
    return $publication;
  }

  public static function getUserPublications(int $id_user): ?array
  {
    $sql = 'SELECT * FROM publications WHERE id_user=:id_user ORDER BY id DESC';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id_user' => $id_user]);
    $publicationsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$publicationsAssoc) return null;
    $publications = [];
    foreach ($publicationsAssoc as $publicationAssoc) {
      $publicationAssoc['imgsPublication'] = static::getPublicationImgs($publicationAssoc['id']);
      $publications[] = \Artist4all\Model\Publication::fromAssoc($publicationAssoc);
    }
    return $publications;
  }

  public static function getPublicationImgs(int $id): ?array
  {
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

  public static function deletePublicationImgs(int $id): bool
  {
    $sql = "DELETE FROM imgs_publications WHERE id_publication=:id_publication";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute(['id_publication' => $id]);
    return $result;
  }

  public static function persistPublication(Publication $publication): ?\Artist4all\Model\Publication
  {
    if (is_null($publication->getId())) return static::insertPublication($publication);
    else return static::updatePublication($publication);
  }

  public static function insertPublication(\Artist4all\Model\Publication $publication): ?\Artist4all\Model\Publication
  {
    $sql = 'INSERT INTO publications VALUES(
        :id,
        :id_user,
        :bodyPublication,
        :upload_date,
        :n_likes,
        :n_comments
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $publication->getId(),
      ':id_user' => $publication->getIdUser(),
      ':bodyPublication' => $publication->getBodyPublication(),
      ':upload_date' => date('Y-m-d H:i:s'),
      ':n_likes' => $publication->getLikes(),
      ':n_comments' => $publication->getComments()
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $publication->setId($id);
    return $publication;
  }

  public static function updatePublication(\Artist4all\Model\Publication $publication): ?\Artist4all\Model\Publication
  {
    $sql = 'UPDATE publications SET
        id_user=:id_user,
        bodyPublication=:bodyPublication,
        upload_date=:upload_date,
        n_likes=:n_likes,
        n_comments=:n_comments
    WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $publication->getId(),
      ':id_user' => $publication->getIdUser(),
      ':bodyPublication' => $publication->getBodyPublication(),
      ':upload_date' => date('Y-m-d H:i:s'),
      ':n_likes' => $publication->getLikes(),
      ':n_comments' => $publication->getComments()
    ]);
    if (!$result) return null;
    return $publication;
  }

  public static function insertPublicationImgs(int $id, string $img): bool
  {
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

  //TODO Remove when set middleware
  public static function canPublish(
    \Artist4all\Model\Publication $publication,
    string $token
  ): bool {
    $sql = 'SELECT * FROM users WHERE token=:token';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':token' => $token]);
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$userAssoc) return false;
    $user = \Artist4all\Model\User::fromAssoc($userAssoc);
    if ($user->getId() == $publication->getIdUser()) return true;
    return false;
  }

  public static function isValidToken(string $token): bool
  {
    $sql = 'SELECT * FROM users WHERE token=:token';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':token' => $token]);
    return $result;
  }

  public static function deletePublicationById(int $id): bool
  {
    $sql = "DELETE FROM publications WHERE id=:id";
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute(['id' => $id]);
    return $result;
  }
}
