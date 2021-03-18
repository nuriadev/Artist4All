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
        $dbpassword = "root";
        $options = array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
        );
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    // todo: comprobar si ya existe un usuario registrado en la db según el email y el username antes de registrar

    public function getUserById(int $id) : \Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE id_user=:id_user";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id_user' => $id ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserByEmail(string $email) : \Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE email=:email";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':email' => $email ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        return $user;
    }

    // registra un usuario
    // todo: comprobar que el usuario no exista según el email o el username
    public function registerUser(\Artist4All\Model\User $user) : bool {  
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

        $password = $user->getPassword();
        $password_hashed = password_hash($password, PASSWORD_DEFAULT);

        $result = $statement->execute([
          ':id_user' => $user->getId(),
          ':name_user' => $user->getName(),
          ':surname1' => $user->getSurname1(),
          ':surname2' => $user->getSurname2(),
          ':email'=> $user->getEmail(),
          ':username' => $user->getUsername(),
          ':passwd' => $password_hashed,
          ':type_user' => $user->getTypeUser(),
          ':deleted' => 0
        ]);
        return $result;
    }

    public function login(string $email, string $password) : array {
        $sql = "SELECT * FROM users WHERE email=:email AND deleted=:deleted";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':email'=> $email,
            ':deleted' => 0
        ]);
    
        // nos traemos los datos del select
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);    
        // si el return es nulo, lo indicamos
        if (!$userAssoc) return null;
        $user = $this->getUserById($userAssoc['id_user']);
        if (password_verify($password, $user->getPassword())) {       
            $arrayAux = array($user->getEmail(), $user->getPassword(), randomTokenPartGenerator());
            $content = implode(".", $arrayAux);
            // creamos el token a partir de la variable $content
            $token = tokenGenerator($content);
            // insertamos el token en la db
            $sql = "UPDATE users SET token=:token WHERE email=:email";
            $statement = $this->conn->prepare($sql);
            $result = $statement->execute([
                ':token' => $token,
                ':email' => $userAssoc["email"]
            ]);
    
            $data = array(
                'token' => $token,
                'name' => $userAssoc['name_user'],
                'surname1' => $userAssoc['surname1'],
                'surname2' => $userAssoc['surname2'],
                'email' => $userAssoc['email'],
                'username' => $userAssoc['username'],
                'password' => $userAssoc['passwd'],
                'type_user' => $userAssoc['type_user'],
                // todo: cambiar el 0 por el n followers 
                'n_followers' => 0,
                'img' => $userAssoc['img'],
                'aboutMe' => $userAssoc['aboutMe']
            );
            return $data;
        } else { 
            $data = array("Usuario incorrecto");
            return $data; 
        }
    }

    // public function modifyUser(string $username, string $aboutMe, string $img) {

    // }

    // // permite modificar algunos campos del usuario
    // // ver si son todos los cambios de campos que necesitamos 
    // public function modifyUser($user) : ?\Artist4All\Model\User {
    //     $id = $user->getId();
    //     if (is_null($this->getUserById($id))) return null;
    //     $sql = "UPDATE users SET 
    //         username=:username, 
    //         passwd=:passwd, 
    //         email=:email 
    //         WHERE id_user=:id_user";
    //     $statement = $this->conn->prepare($sql);
    //     $result = $statement->execute([
    //       ':id_user' => $id,
    //       ':username' => $user->getUsername(),
    //       ':passwd' => $user->getPassword(),
    //       ':email' => $user->getEmail()
    //     ]);
    //     return $this->getUserById($id);
    // }

    // // desactiva la cuenta de un usuario
    // // todo: falta que no pueda entrar en el login con los credenciales registrados
    // public function deactivateUserAccount($user) : ?\Artist4All\Model\User {
    //     $id = $user->getId();
    //     if (is_null($this->getUserById($id))) return null;
    //     $sql = "UPDATE users SET deleted=:deleted WHERE id_user=:id_user";
    //     $statement = $this->conn->prepare($sql);
    //     $result = $statement->execute([
    //       ':id_user' => $id,
    //       ':deleted' => 1
    //     ]);
    //     return $result;
    // }
}