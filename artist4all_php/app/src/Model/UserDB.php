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

     public function getUserById(int $id) : ?\Artist4All\Model\User {
        $sql = "SELECT 
            id, 
            name, 
            surname1, 
            surname2,
            email,
            username,
            password,
            isArtist,
            imgAvatar,
            aboutMe 
        FROM users WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserByEmail(string $email) : ?\Artist4All\Model\User {
        $sql = "SELECT 
            id, 
            name, 
            surname1, 
            surname2,
            email,
            username,
            password,
            isArtist,
            imgAvatar,
            aboutMe
        FROM users WHERE email=:email AND deleted=:deleted";
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

    public function updateToken(string $token, \Artist4All\Model\User $user) : bool {
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