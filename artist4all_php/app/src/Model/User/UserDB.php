<?php
namespace Artist4all\Model\User;

class UserDB {
    protected static ?\Artist4all\Model\User\UserDB $instance = null;

    public static function getInstance() : \Artist4all\Model\User\UserDB {
        if(is_null(static::$instance)) {
            static::$instance = new \Artist4all\Model\User\UserDB();
        }
        return static::$instance;
    }

    private \PDO $conn;

    protected function __construct() {
        $dsn = 'mysql:host=artist4all_db;dbname=artist4alldb';
        $dbusername = 'root';
        $dbpassword = 'password';
        $options = array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
        );
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    public function getOtherUsers(string $username) : ?array {
        $sql = 'SELECT * FROM users WHERE username!=:username';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':username' => $username ]);
        $usersAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (!$usersAssoc) return null;
        $users = [];
        foreach($usersAssoc as $userAssoc) {
          $users[] = \Artist4all\Model\User\User::fromAssoc($userAssoc);
        }
        return $users;
    }

    public function getUserByUsername(string $username) : ?\Artist4all\Model\User\User {
        $sql = 'SELECT * FROM users WHERE username=:username';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':username' => $username ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4all\Model\User\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserById(int $id) : ?\Artist4all\Model\User\User {
        $sql = 'SELECT * FROM users WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4all\Model\User\User::fromAssoc($userAssoc);
        return $user;
    }

    public function getUserByEmail(string $email) : ?\Artist4all\Model\User\User {
        $sql = 'SELECT * FROM users WHERE email=:email AND deactivated=:deactivated';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':email'=> $email,
            ':deactivated' => 0
        ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return null;
        $user = \Artist4all\Model\User\User::fromAssoc($userAssoc);
        return $user;
    }

    public function persistUser(User $user) : ?\Artist4all\Model\User\User {
        if (is_null($user->getId())) return $this->insertUser($user);
        else return $this->updateUser($user);        
    }

    // registra un usuario
    public function insertUser(\Artist4all\Model\User\User $user) : ?\Artist4all\Model\User\User {  
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
            :deactivated
        )';
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
            ':token' => $user->getToken(),
            ':deactivated' => 0
        ]);
        if (!$result) return null;
        $id = $this->conn->lastInsertId();
        $user->setId($id);
        return $user;
    }

    public function updateUser(
        \Artist4all\Model\User\User $user) : ?\Artist4all\Model\User\User {
        $sql = 'UPDATE users SET
            id=:id,
            name=:name, 
            surname1=:surname1, 
            surname2=:surname2, 
            email=:email, 
            username=:username,  
            password=:password, 
            isArtist=:isArtist, 
            imgAvatar=:imgAvatar,
            aboutMe=:aboutMe
        WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $user->getId(),
            ':name' => $user->getName(),
            ':surname1' => $user->getSurname1(),
            ':surname2' => $user->getSurname2(),
            ':email' => $user->getEmail(),
            ':username' => $user->getUsername(),
            ':password' => $user->getPassword(),
            ':isArtist' => $user->isArtist(),
            ':imgAvatar' => $user->getImgAvatar(),
            ':aboutMe' => $user->getAboutMe(),
            ':token' => $user->getToken(),
        ]);
        if (!$result) return null;
        return $user;
    }
    
    public function createOrUpdateToken(string $token, \Artist4all\Model\User\User $user) : bool {
        $sql = 'UPDATE users SET token=:token WHERE email=:email';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':token' => $token,
            ':email' => $user->getEmail()
        ]);
        return $result;
    }

    public function logout(string $token) : bool {
        $sql = 'UPDATE users SET token=:token WHERE token=:tokenReceived';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':token' => '',
            ':tokenReceived' => $token
        ]);
        return $result;
    }

    public function changePassword(string $password, string $token) : bool {
        $sql = 'UPDATE users SET password=:password WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':password' => $password,
            ':token' => $token
        ]);
        return $result;
    }

    public function isFollowingThatUser(
        \Artist4all\Model\User\User $user_follower,
        \Artist4all\Model\User\User $user_followed) : ?array {
        $sql = 'SELECT id FROM users_followed WHERE 
        id_follower=:id_follower AND 
        id_followed=:id_followed';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id_follower' => $user_follower->getId(),
            ':id_followed' => $user_followed->getId()
        ]);
        $followed = $statement->fetch(\PDO::FETCH_ASSOC);
        if(!$followed) return null;
        $response = [
            'id_follow' => $followed['id'],
            'isFollowing' => 'yes'     
        ];
        return $response;
    }

    public function followUser(
        \Artist4all\Model\User\User $user_follower,
        \Artist4all\Model\User\User $user_followed) : ?array {
        $sql = 'INSERT INTO users_followed VALUES (
            :id,
            :id_follower,
            :id_followed,
            :status_follow
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => null,
            ':id_follower' => $user_follower->getId(),
            ':id_followed' => $user_followed->getId(),
            ':status_follow' => 0
        ]);
        if (!$result) return null;
        $response = [
            'id_follow' => $this->conn->lastInsertId(),
            'message' => 'User followed'
        ];
        return $response;
    }

    public function unfollowUser(int $id) : ?array {
        $sql = 'DELETE FROM users_followed WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        if (!$result) return null;
        $response = [
            'message' => 'User unfollowed'
        ];
        return $response;
    }
    
    public function countOrGetFollowers(int $id, string $tipo) : ?array {
        $sql = 'SELECT id_follower FROM users_followed WHERE id_followed=:my_id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':my_id' => $id ]); 
        if ($tipo == 'count') {
            if (!$result) return null;
            $n_followers = ['n_followers' => $statement->rowCount()];
            return $n_followers;
        } else if ($tipo == 'get') {
            $idsFollowersAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (!$idsFollowersAssoc) return null;
            $idsFollower = [];
            foreach($idsFollowersAssoc as $idsFollowerAssoc) {
            $idsFollower[] = $idsFollowerAssoc['id_follower'];
            }
            return $idsFollower; 
        }
    }

    public function countOrGetFollowed(int $id, string $tipo) : ?array {
        $sql = 'SELECT id_followed FROM users_followed WHERE id_follower=:my_id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':my_id' => $id ]);
        if ($tipo == 'count') {
            if (!$result) return null;
            $n_followed = ['n_followed' => $statement->rowCount()];
            return $n_followed; 
        } else if ($tipo == 'get') {
            $idsFollowedsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
            if (!$idsFollowedsAssoc) return null;
            $idsFollowed = [];
            foreach($idsFollowedsAssoc as $idsFollowedAssoc) {
              $idsFollowed[] = $idsFollowedAssoc['id_followed'];
            }
            return $idsFollowed;
        }
    }

    public function isValidToken(string $token) : bool {
        $sql = 'SELECT * FROM users WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':token' => $token ]);
        return $result;
    }

}