<?php
namespace Artist4All\Model;
// todo: que pille las rutas sin el require_once
require_once 'TokenGenerator.php';
require_once 'User.php';
require_once 'Session.php';
class UserDB {
    protected static ?\Artist4All\Model\UserDB $instance = null;

    public static function getInstance() : \Artist4All\Model\UserDB {
        if(is_null(static::$instance)) {
            static::$instance = new \Artist4All\Model\UserDB();
        }
        return static::$instance;
    }

    private \PDO $conn;

    protected function __construct() {
        $dsn = "mysql:host=artist4all_db;dbname=artist4alldb";
        $dbusername = "root";
        $dbpassword = "password";
        $options = array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
        );
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    public function getOtherUsers(string $username) : ?array {
        $sql = "SELECT * FROM users WHERE username!=:username";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':username' => $username ]);
        $usersAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (!$usersAssoc) return null;
        $users = [];
        foreach($usersAssoc as $userAssoc) {
          $users[] = \Artist4All\Model\User::fromAssoc($userAssoc);
        }
        return $users;
    }

    public function getUserByUsername(string $username) : ?\Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE username=:username";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':username' => $username ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserById(int $id) : ?\Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserByEmail(string $email) : ?\Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE email=:email AND deleted=:deleted";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':email'=> $email,
            ':deleted' => 0
        ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    // registra un usuario
    public function registerUser(\Artist4All\Model\User $user) : bool {  
        $sql = "INSERT INTO users VALUES (
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
            :deleted
        )";
        $statement = $this->conn->prepare($sql);
        $password = $user->getPassword();
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
        $result = $statement->execute([
            ':id' => $user->getId(),
            ':name' => $user->getName(),
            ':surname1' => $user->getSurname1(),
            ':surname2' => $user->getSurname2(),
            ':email'=> $user->getEmail(),
            ':username' => $user->getUsername(),
            ':password' => $password_hashed,
            ':isArtist' => $user->isArtist(),
            ':imgAvatar' => $user->getImgAvatar(),
            ':aboutMe' => $user->getAboutMe(),
            ':token' => '',
            ':deleted' => 0
        ]);
        return $result;
    }

    public function editUser(
        \Artist4All\Model\User $user,
        string $token) : bool {
        $sql = "UPDATE users SET
            name=:name, 
            surname1=:surname1, 
            surname2=:surname2, 
            email=:email, 
            username=:username,  
            imgAvatar=:imgAvatar,
            aboutMe=:aboutMe,
        WHERE token=:token";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':name' => $user->getName(),
            ':surname1' => $user->getSurname1(),
            ':surname2' => $user->getSurname2(),
            ':email' => $user->getEmail(),
            ':username' => $user->getUsername(),
            ':imgAvatar' => $user->getImgAvatar(),
            ':aboutMe' => $user->getAboutMe(),
            ':token' => $token
        ]);
/*         $result = $statement->execute([
            ':name' => $user->getName(), 
            ':surname1' => $user->getSurname1(), 
            ':surname2' => $user->getSurname2(),
            ':email' => $user->getEmail(), 
            ':username' => $user->getUsername(), 
            ':imgAvatar' => $user->getImgAvatar(),
            ':aboutMe' => $user->getAboutMe(),
            ':id' => $user->getId()
        ]); */
        return $result;
    }

    public function editPassword(string $password, string $token) : bool {
        $sql = "UPDATE users SET password=:password WHERE token=:token";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':password' => $password,
            ':token' => $token
        ]);
        return $result;
    }

    public function createOrUpdateToken(string $token, \Artist4All\Model\User $user) : bool {
        $sql = "UPDATE users SET token=:token WHERE email=:email";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':token' => $token,
            ':email' => $user->getEmail()
        ]);
        return $result;
    }

    public function logout(string $token) : bool {
        $sql = "UPDATE users SET token=:token WHERE token=:tokenReceived";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':token' => '',
            ':tokenReceived' => $token
        ]);
        return $result;
    }

    
}