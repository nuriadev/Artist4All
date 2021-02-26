<?php

namespace Artist4All\Model;

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
        $dsn = "mysql:host=localhost;dbname=artist4alldb";
        $dbusername = "root";
        $dbpassword = "";
        $options = array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
        );
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    // todo: comprobar si ya existe un usuario registrado en la db según el email y el username antes de registrar

    // devuelve el nº de seguidores de un usuario según su id
    public function getUserN_Followers(int $id) : int {
        $sql = "SELECT count(id_logfollow) FROM users_followed WHERE id_followed=:id_user";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':id_user' => $id
        ]);
        $nFollowers = $statement->fetch(\PDO::FETCH_ASSOC);
        return $nFollowers;
    }

    // devuelve todos los usuarios registrados
    public function getAllUsers() : array {
        $sql = "SELECT * FROM users";
        $result = $this->conn->query($sql);
        $usersAssoc = $result->fetchAll(\PDO::FETCH_ASSOC);
        if (!$usersAssoc) return [];
        $users = [];
        foreach ($usersAssoc as $userAssoc)
        $users[] = new \Artist4All\Model\User(
            $userAssoc['id_user'], 
            $userAssoc['name_user'],
            $userAssoc['surname1'], 
            $userAssoc['surname2'],     
            $userAssoc['email'],
            $userAssoc['username'], 
            $userAssoc['passwd'], 
            $userAssoc['type_user'],
            $this->getUserN_Followers($userAssoc['id_user']),
            $userAssoc['deleted']
        );
        return $users;
    }

    // devuelve un usuario según su nombre de usuario
    public function getUserByUsername(string $username) : ?\Artist4All\Model\UserDB {
        $sql = "SELECT * FROM users WHERE username=:username";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':username' => $username
        ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = new \Artist4All\Model\User(
            $userAssoc['id_user'], 
            $userAssoc['name_user'],
            $userAssoc['surname1'], 
            $userAssoc['surname2'],     
            $userAssoc['email'],
            $userAssoc['username'], 
            $userAssoc['passwd'], 
            $userAssoc['type_user'],
            $this->getUserN_Followers($userAssoc['id_user']),
            $userAssoc['deleted']
        );
        return $user;
    }

    // devuelve un usuario según su email
    public function getUserByEmail(string $email) : ?\Artist4All\Model\UserDB {
        $sql = "SELECT * FROM users WHERE email=:email";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':email' => $email
        ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = new \Artist4All\Model\User(
            $userAssoc['id_user'], 
            $userAssoc['name_user'],
            $userAssoc['surname1'], 
            $userAssoc['surname2'],     
            $userAssoc['email'],
            $userAssoc['username'], 
            $userAssoc['passwd'], 
            $userAssoc['type_user'],
            $this->getUserN_Followers($userAssoc['id_user']),
            $userAssoc['deleted']
        );
        return $user;
    }

    // devuelve un usuario según su id
    public function getUserById(int $id) : ?\Artist4All\Model\UserDB {
        $sql = "SELECT * FROM users WHERE id_user=:id_user";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':id_user' => $id
        ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = new \Artist4All\Model\User(
            $userAssoc['id_user'], 
            $userAssoc['name_user'],
            $userAssoc['surname1'], 
            $userAssoc['surname2'],     
            $userAssoc['email'],
            $userAssoc['username'], 
            $userAssoc['passwd'], 
            $userAssoc['type_user'],
            $this->getUserN_Followers($userAssoc['id_user']),
            $userAssoc['deleted']
        );
        return $user;
    }

    // registra un usuario
    // todo: comprobar que el usuario no exista según el email o el username
    public function registerUser(\Artist4All\Model\User $user) {  
        $sql = "INSERT INTO users VALUES (
            :id_user, 
            :name_user, 
            :surname1, 
            :surname2, 
            :email, 
            :username, 
            :passwd, 
            :type_user, 
            :deleted)";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':id_user' => $user->getId(),
          ':name_user' => $user->getName(),
          ':surname1' => $user->getSurname1(),
          ':surname2' => $user->getSurname2(),
          ':email'=> $user->getEmail(),
          ':username' => $user->getUsername(),
          ':passwd' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
          ':type_user' => $user->getType_user(),
          ':deleted' => 0
        ]);
        $insertId = $this->conn->lastInsertId();
        return $this->getUserById($insertId);
    }

    // permite modificar algunos campos del usuario
    // ver si son todos los cambios de campos que necesitamos 
    public function modifyUser($user) : ?\Artist4All\Model\User {
        $id = $user->getId();
        if (is_null($this->getUserById($id))) return null;
        $sql = "UPDATE users SET 
            username=:username, 
            passwd=:passwd, 
            email=:email 
            WHERE id_user=:id_user";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':id_user' => $id,
          ':username' => $user->getUsername(),
          ':passwd' => $user->getPassword(),
          ':email' => $user->getEmail()
        ]);
        return $this->getUserById($id);
    }

    // desactiva la cuenta de un usuario
    // todo: falta que no pueda entrar en el login con los credenciales registrados
    public function deactivateUserAccount($user) : ?\Artist4All\Model\User {
        $id = $user->getId();
        if (is_null($this->getUserById($id))) return null;
        $sql = "UPDATE users SET deleted=:deleted WHERE id_user=:id_user";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
          ':id_user' => $id,
          ':deleted' => 1
        ]);
        return $result;
    }
}