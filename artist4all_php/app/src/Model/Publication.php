<?php
namespace Artist4All\Model;
class Publication implements \JsonSerializable {

  private ?int $id;
  private int $id_user;
  private string $imgPublication;
  private string $bodyPublication;
  private string $upload_date;
  private int $n_likes;
  private int $n_comments;
  private int $n_views;

  public function __construct(
    ?int $id,
    int $id_user,
/*     string $imgPublication, */
    string $bodyPublication,
    string $upload_date,
    int $n_likes,
    int $n_comments,
    int $n_views) {
      $this->id = $id;
      $this->id_user = $id_user;
/*       $this->imgPublication = $imgPublication; */
      $this->bodyPublication = $bodyPublication;
      $this->upload_date = $upload_date;
      $this->n_likes = $n_likes;
      $this->n_comments = $n_comments;
      $this->n_views = $n_views;
    }

    public function getId() : ?int {
      return $this->id;
    }

    public function setId(?int $id) {
      $this->$id = $id;
    }

    public function getIdUser() : int {
      return $this->id_user;
    }

    public function setIdUser(int $id_user) {
      $this->id_user = $id_user;
    }

    public function getImgPublication() : string {
      return $this->imgPublication;
    }

    public function setImgPublication(string $imgPublication) {
      $this->imgPublication = $imgPublication;
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

    public function getViews() : int {
      return $this->n_views;
    }

    public function setViews(int $n_views) {
      $this->n_views = $n_views;
    }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data) : \Artist4All\Model\Publication {
    return new \Artist4All\Model\Publication(
      $data['id'], 
      $data['id_user'],
/*       $data['imgPublication'],  */
      $data['bodyPublication'], 
      $data['upload_date'],
      $data['n_likes'],
      $data['n_comments'],
      $data['n_views']
    );
  }

    // Needed for implicit JSON serialization with json_encode()
    public function jsonSerialize() {
      return [
        'id' => $this->id,
        'id_user' => $this->id_user,
 /*        'imgPublication' => $this->imgPublication, */
        'bodyPublication' => $this->bodyPublication,
        'upload_date' => $this->upload_date,
        'n_likes' => $this->n_likes,
        'n_comments' => $this->n_comments,
        'n_views' => $this->n_views,
      ];
    }
}