<?php

namespace Artist4all\Model;

class User implements \JsonSerializable
{
  private ?int $id;
  private string $name;
  private string $surname1;
  private string $surname2;
  private string $email;
  private string $username;
  private string $password;
  private int $isArtist;
  private ?string $imgAvatar;
  private ?string $aboutMe;
  private ?string $token;
  private int $isPrivate;

  public function __construct(
    ?int $id,
    string $name,
    string $surname1,
    string $surname2,
    string $email,
    string $username,
    string $password,
    int $isArtist,
    ?string $imgAvatar,
    ?string $aboutMe,
    ?string $token,
    int $isPrivate
  ) {
    $this->id = $id;
    $this->name = $name;
    $this->surname1 = $surname1;
    $this->surname2 = $surname2;
    $this->email = $email;
    $this->username = $username;
    $this->password = $password;
    $this->isArtist = $isArtist;
    $this->imgAvatar = $imgAvatar;
    $this->aboutMe = $aboutMe;
    $this->token = $token;
    $this->isPrivate = $isPrivate;
  }

  public function getId(): ?int
  {
    return $this->id;
  }
  public function setId(?int $id)
  {
    $this->id = $id;
  }
  public function getName(): string
  {
    return $this->name;
  }
  public function setName(string $name)
  {
    $this->name = $name;
  }
  public function getSurname1(): string
  {
    return $this->surname1;
  }
  public function setSurname1(string $surname1)
  {
    $this->surname1 = $surname1;
  }
  public function getSurname2(): string
  {
    return $this->surname2;
  }
  public function setSurname2(string $surname2)
  {
    $this->surname2 = $surname2;
  }
  public function getEmail(): string
  {
    return $this->email;
  }
  public function setEmail(string $email)
  {
    $this->email = $email;
  }
  public function getUsername(): string
  {
    return $this->username;
  }
  public function setUsername(string $username)
  {
    $this->username = $username;
  }
  public function getPassword(): string
  {
    return $this->password;
  }
  public function setPassword(string $password)
  {
    $this->password = $password;
  }
  public function isArtist()
  {
    return $this->isArtist;
  }
  public function setIsArtist(int $isArtist)
  {
    $this->isArtist = $isArtist;
  }
  public function getImgAvatar(): ?string
  {
    return $this->imgAvatar;
  }
  public function setImgAvatar(?string $imgAvatar)
  {
    $this->imgAvatar = $imgAvatar;
  }
  public function getAboutMe(): ?string
  {
    return $this->aboutMe;
  }
  public function setAboutMe(?string $aboutMe)
  {
    $this->aboutMe = $aboutMe;
  }
  public function getToken(): ?string
  {
    return $this->token;
  }
  public function setToken(?string $token)
  {
    $this->token = $token;
  }
  public function isPrivate()
  {
    return $this->isPrivate;
  }
  public function setIsPrivate(int $isPrivate)
  {
    $this->isPrivate = $isPrivate;
  }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\User
  {
    return new \Artist4all\Model\User(
      $data['id'],
      $data['name'],
      $data['surname1'],
      $data['surname2'],
      $data['email'],
      $data['username'],
      $data['password'],
      $data['isArtist'],
      $data['imgAvatar'],
      $data['aboutMe'],
      $data['token'],
      $data['isPrivate']
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize()
  {
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
      'isPrivate' => $this->isPrivate,
    ];
  }

  // DAO METHODS
  public static function getAllOtherUsers(string $username): ?array
  {
    $sql = 'SELECT * FROM users WHERE username!=:username';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':username' => $username]);
    $usersAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$usersAssoc) return null;
    $users = [];
    foreach ($usersAssoc as $userAssoc) {
      $users[] = \Artist4all\Model\User::fromAssoc($userAssoc);
    }
    return $users;
  }

  public static function getUserByUsername(string $username): ?\Artist4all\Model\User
  {
    $sql = 'SELECT * FROM users WHERE username=:username';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':username' => $username]);
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$userAssoc) return null;
    $user = \Artist4all\Model\User::fromAssoc($userAssoc);
    return $user;
  }

  public static function getUserById(int $id): ?\Artist4all\Model\User
  {
    $sql = 'SELECT * FROM users WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$userAssoc) return null;
    $user = \Artist4all\Model\User::fromAssoc($userAssoc);
    return $user;
  }

  public static function getUserByEmail(string $email): ?\Artist4all\Model\User
  {
    $sql = 'SELECT * FROM users WHERE email=:email AND deactivated=:deactivated';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':email' => $email,
      ':deactivated' => 0
    ]);
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$userAssoc) return null;
    $user = \Artist4all\Model\User::fromAssoc($userAssoc);
    return $user;
  }

  public static function persistUser(User $user): ?\Artist4all\Model\User
  {
    if (is_null($user->getId())) return static::insertUser($user);
    else return static::updateUser($user);
  }

  // registra un usuario
  public static function insertUser(\Artist4all\Model\User $user): ?\Artist4all\Model\User
  {
    $sql = 'INSERT INTO users VALUES (
        :id, 
        :name, 
        :surname1, 
        :surname2, 
        :email, 
        :username, 
        :password, 
        :isArtist, 
        :imgAvatar,
        :aboutMe,
        :token,
        :isPrivate,
        :deactivated
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $password = $user->getPassword();
    $password_hashed = password_hash($password, PASSWORD_DEFAULT);
    $result = $statement->execute([
      ':id' => $user->getId(),
      ':name' => $user->getName(),
      ':surname1' => $user->getSurname1(),
      ':surname2' => $user->getSurname2(),
      ':email' => $user->getEmail(),
      ':username' => $user->getUsername(),
      ':password' => $password_hashed,
      ':isArtist' => $user->isArtist(),
      ':imgAvatar' => $user->getImgAvatar(),
      ':aboutMe' => $user->getAboutMe(),
      ':token' => $user->getToken(),
      ':isPrivate' => $user->isPrivate(),
      ':deactivated' => 0
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $user->setId($id);
    return $user;
  }

  public static function updateUser(\Artist4all\Model\User $user): ?\Artist4all\Model\User
  {
    $sql = 'UPDATE users SET
        name=:name, 
        surname1=:surname1, 
        surname2=:surname2, 
        email=:email, 
        username=:username,  
        password=:password, 
        isArtist=:isArtist, 
        imgAvatar=:imgAvatar,
        aboutMe=:aboutMe
    WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':name' => $user->getName(),
      ':surname1' => $user->getSurname1(),
      ':surname2' => $user->getSurname2(),
      ':email' => $user->getEmail(),
      ':username' => $user->getUsername(),
      ':password' => $user->getPassword(),
      ':isArtist' => $user->isArtist(),
      ':imgAvatar' => $user->getImgAvatar(),
      ':aboutMe' => $user->getAboutMe(),
      ':id' => $user->getId(),
    ]);
    if (!$result) return null;
    return $user;
  }

  public static function createOrUpdateToken(string $token, \Artist4all\Model\User $user): bool
  {
    $sql = 'UPDATE users SET token=:token WHERE email=:email';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':token' => $token,
      ':email' => $user->getEmail()
    ]);
    return $result;
  }

  // todo pasar usuario
  public static function logout(int $id): bool
  {
    $sql = 'UPDATE users SET token=:token WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':token' => '',
      ':tokenReceived' => $id
    ]);
    return $result;
  }

  //TODO pasar usuario y contraseña
  public static function changePassword(string $password, int $id): bool
  {
    $sql = 'UPDATE users SET password=:password WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':password' => $password,
      ':id' => $id
    ]);
    return $result;
  }

  public static function isFollowingThatUser(
    \Artist4all\Model\User $user_follower,
    \Artist4all\Model\User $user_followed
  ): ?array {
    $sql = 'SELECT * FROM users_followed WHERE 
    id_follower=:id_follower AND 
    id_followed=:id_followed';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_follower' => $user_follower->getId(),
      ':id_followed' => $user_followed->getId(),
    ]);
    $followed = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$followed) return null;
    return $followed;
  }

  public static function persistFollow(array $logFollow): ?array
  {
    if (is_null($logFollow['id'])) return static::insertRequestOrFollow($logFollow);
    else return static::updateFollowStatus($logFollow);
  }

  public static function insertRequestOrFollow(array $logFollow): ?array
  {
    $sql = 'INSERT INTO users_followed VALUES (
        :id,
        :id_follower,
        :id_followed,
        :status_follow
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $logFollow['id'],
      ':id_follower' => $logFollow['id_follower'],
      ':id_followed' => $logFollow['id_followed'],
      ':status_follow' => $logFollow['status_follow']
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $logFollow['id'] = (int) $id;
    return $logFollow;
  }

  public static function updateFollowStatus(array $logFollow): ?array
  {
    $sql = 'UPDATE users_followed SET status_follow=:status_follow WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => (int) $logFollow['id'],
      ':status_follow' => $logFollow['status_follow']
    ]);
    if (!$result) return null;
    return $logFollow;
  }

  public static function getFollowers(int $id_followed): ?array
  {
    $sql = 'SELECT id_follower FROM users_followed WHERE id_followed=:id_followed AND status_follow=:status_follow';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_followed' => $id_followed,
      ':status_follow' => 3
    ]);
    $ids = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$ids) return null;
    $listFollowers = [];
    foreach ($ids as $id) {
      $listFollowers[] = \Artist4all\Model\User::getUserById($id['id_follower']);
    }
    return $listFollowers;
  }

  public static function getFollowed(int $id_follower): ?array
  {
    $sql = 'SELECT id_followed FROM users_followed WHERE id_follower=:id_follower AND status_follow=:status_follow';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_follower' => $id_follower,
      ':status_follow' => 3
    ]);
    $ids = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$ids) return null;
    $listFollowed = [];
    foreach ($ids as $id) {
      $listFollowed[] = \Artist4all\Model\User::getUserById($id['id_followed']);
    }
    return $listFollowed;
  }

  public static function getIdByToken(string $token): ?int
  {
    $sql = 'SELECT id FROM users WHERE token=:token';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $id_user = $statement->execute([':token' => $token]);
    if (!$id_user) return null;
    return $id_user;
  }

  public static function privateAccountSwitcher(
    int $isPrivate,
    int $id
  ): bool {
    $sql = 'UPDATE users SET isPrivate=:isPrivate WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':isPrivate' => $isPrivate,
      ':id' => $id
    ]);
    return $result;
  }
}
