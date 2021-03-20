<?php
namespace Artist4All\Model;
require_once 'TokenGenerator.php';

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
    // todo: validación
    // todo: comprobar si ya existe un usuario registrado en la db según el email y el username antes de registrar o si está dado de baja para volver a activar su acc,

     public function getUserById(int $id) : \Artist4All\Model\User {
        $sql = "SELECT * FROM users WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
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
        if (password_verify($password, $userAssoc['password'])) {       
            $arrayAux = array($userAssoc['email'], $userAssoc['password'], \Artist4All\Model\TokenGenerator::randomTokenPartGenerator());
            $content = implode(".", $arrayAux);
            // creamos el token a partir de la variable $content
            $token = \Artist4All\Model\TokenGenerator::tokenGenerator($content);
            // insertamos el token en la db
            $sql = "UPDATE users SET token=:token WHERE email=:email";
            $statement = $this->conn->prepare($sql);
            $result = $statement->execute([
                ':token' => $token,
                ':email' => $userAssoc['email']
            ]);
            // todo trabajar con una clase sesión
            //$session = new \Artist4All\Model\Session($token, $user);
            $data = array(
                'token' => $token,
                'name' => $userAssoc['name'],
                'surname1' => $userAssoc['surname1'],
                'surname2' => $userAssoc['surname2'],
                'email' => $userAssoc['email'],
                'username' => $userAssoc['username'],
                'password' => $userAssoc['password'],
                'isArtist' => $userAssoc['isArtist'],
                // todo: cambiar el 0 por el n followers 
                'n_followers' => 0,
                'imgAvatar' => $userAssoc['imgAvatar'],
                'aboutMe' => $userAssoc['aboutMe']
            );
            return $data;
        } else { 
            $data = array("Usuario incorrecto");
            return $data; 
        }
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