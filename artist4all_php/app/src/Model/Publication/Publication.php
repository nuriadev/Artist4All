<?php
namespace Artist4all\Model\Publication;
class Publication implements \JsonSerializable {
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
    int $n_comments) {
      $this->id = $id;
      $this->id_user = $id_user;
      $this->imgsPublication = $imgsPublication; 
      $this->bodyPublication = $bodyPublication;
      $this->upload_date = $upload_date;
      $this->n_likes = $n_likes;
      $this->n_comments = $n_comments;
    }

    public function getId() : ?int {
      return $this->id;
    }

    public function setId(?int $id) {
      $this->id = $id;
    }

    public function getIdUser() : int {
      return $this->id_user;
    }

    public function setIdUser(int $id_user) {
      $this->id_user = $id_user;
    }

    public function getImgsPublication() : ?array {
      return $this->imgsPublication;
    }

    public function setImgsPublication(?array $imgsPublication) {
      $this->imgsPublication = $imgsPublication;
    }

    public function getBodyPublication() : string {
      return $this->bodyPublication;
    }

    public function setBodyPublication(string $bodyPublication) {
      $this->bodyPublication = $bodyPublication;
    }

    public function getUploadDatePublication() : string {
      return $this->upload_date;
    }

    public function setUploadDatePublication(string $upload_date) {
      $this->upload_date = $upload_date;
    }

    public function getLikes() : int {
      return $this->n_likes;
    }

    public function setLikes(int $n_likes) {
      $this->n_likes = $n_likes;
    }

    public function getComments() : int {
      return $this->n_comments;
    }

    public function setComments(int $n_comments) {
      $this->n_comments = $n_comments;
    }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data) : \Artist4all\Model\Publication\Publication {
    return new \Artist4all\Model\Publication\Publication(
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
    public function jsonSerialize() {
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
}