<?php
namespace Artist4All\Model;
class User implements \JsonSerializable {

  private ?int $id;
  private string $name;
  private string $surname1;
  private string $surname2;
  private string $email;
  private string $username;
  private string $password;
  private int $n_followers;
  private int $isArtist;
  private ?string $imgAvatar;
  private ?string $aboutMe;

  public function __construct(
    ?int $id,
    string $name,
    string $surname1,
    string $surname2,
    string $email,
    string $username,
    string $password,
    int $n_followers,
    int $isArtist,
    ?string $imgAvatar,
    ?string $aboutMe) {
      $this->id = $id;
      $this->name = $name;
      $this->surname1 = $surname1;
      $this->surname2 = $surname2;
      $this->email = $email;
      $this->username = $username;
      $this->password = $password;
      $this->n_followers = $n_followers;
      $this->isArtist = $isArtist;
      $this->imgAvatar = $imgAvatar;
      $this->aboutMe = $aboutMe;
    }

    public function getId() : ?int {
      return $this->id;
    }

    public function setId(?int $id) {
      $this->$id = $id;
    }

    public function getName() : string {
      return $this->name;
    }

    public function setName(string $name) {
      $this->name = $name;
    }

    public function getSurname1() : string {
      return $this->surname1;
    }

    public function setSurname1(string $surname1) {
      $this->surname1 = $surname1;
    }

    public function getSurname2() : string {
      return $this->surname2;
    }

    public function setSurname2(string $surname2) {
      $this->surname2 = $surname2;
    }

    public function getEmail() : string {
      return $this->email;
    }

    public function setEmail(string $email) {
      $this->email = $email;
    }

    public function getUsername() : string {
      return $this->username;
    }

    public function setUsername(string $username) {
      $this->username = $username;
    }

    public function getPassword() : string {
      return $this->password;
    }

    public function setPassword(string $password) {
      $this->password = $password;
    }

    public function getN_followers() : int {
      return $this->n_followers;
    }

    public function setN_followers(int $n_followers) {
      $this->n_followers = $n_followers;
    }

    public function isArtist() {
      return $this->isArtist;
    }

    public function setIsArtist(int $isArtist) {
      $this->isArtist = $isArtist;
    }

    public function getImgAvatar() : ?string {
      return $this->imgAvatar;
    }

    public function setImgAvatar(?string $imgAvatar) {
      $this->imgAvatar = $imgAvatar;
    }

    public function getAboutMe() : ?string {
      return $this->aboutMe;
    }

    public function setAboutMe(?string $aboutMe) {
      $this->aboutMe = $aboutMe;
    }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data) : User {
    return new \Artist4All\Model\User(
      $data['id'], 
      $data['name'],
      $data['surname1'], 
      $data['surname2'], 
      $data['email'],
      $data['username'],
      $data['password'],
      $data["n_followers"],
      $data['isArtist'],
      $data['imgAvatar'],
      $data['aboutMe']
    );
  }

    // Needed for implicit JSON serialization with json_encode()
    public function jsonSerialize() {
      return [
        'id' => $this->id,
        'name' => $this->name,
        'surname1' => $this->surname1,
        'surname2' => $this->surname2,
        'email' => $this->email,
        'username' => $this->username,
        'password' => $this->password,
        'isArtist' => $this->isArtist,
        'imgAvatar' => $this->imgAvatar,
        'aboutMe' => $this->aboutMe,
        'token' => $this->token
      ];
    }
}