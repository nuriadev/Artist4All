<?php
namespace Artist4All\Model;
// todo: que pille las rutas sin el require_once
require_once 'Publication.php';
require_once 'User.php';

class PublicationDB {
    protected static ?\Artist4All\Model\PublicationDB $instance = null;

    public static function getInstance() : \Artist4All\Model\PublicationDB {
        if(is_null(static::$instance)) {
            static::$instance = new \Artist4All\Model\PublicationDB();
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

    public function getPublicationById(int $id) : ?\Artist4All\Model\Publication {
        $sql = 'SELECT * FROM publications WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $publicationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$publicationAssoc) return null;
        $imgsPublication = $this->getPublicationImgs($id);
        $data['imgsPublication'] = $imgsPublication;
        $publication = \Artist4All\Model\Publication::fromAssoc($publicationAssoc);
        return $publication;
    }

    public function getPublicationImgs(int $id) : ?array {
        $sql = 'SELECT imgPublication FROM imgsPublications WHERE id_publication=:id_publication';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id_publication' => $id ]);
        $imgsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (!$imgsAssoc) return null;
        $imgsPublication = [];
        foreach ($imgsAssoc as $imgAssoc) {
            $imgsPublication[] = $imgAssoc;
        }
        return $imgsPublication;
    }

    public function persistPublication(Publication $publication) : ?\Artist4All\Model\Publication {
        if (is_null($publication->getId())) return $this->insertPublication($publication);
        else return $this->updatePublication($publication);
    }

    public function insertPublication(\Artist4All\Model\Publication $publication) : ?\Artist4All\Model\Publication {
        $sql = 'INSERT INTO publications VALUES(
            :id,
            :id_user,
            :bodyPublication,
            :upload_date,
            :n_likes,
            :n_comments,
            :n_views
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $publication->getId(),
            ':id_user' => $publication->getIdUser(),
            ':bodyPublication' => $publication->getBodyPublication(),
            ':upload_date' => date('Y-m-d H:i:s'),
            ':n_likes' => $publication->getLikes(),
            ':n_comments' => $publication->getComments(),
            ':n_views' => $publication->getViews()
        ]);
        if(!$result) return null;
        $id = $this->conn->lastInsertId();
        $publication->setId($id);
        return $publication;
    }

    public function updatePublication(\Artist4All\Model\Publication $publication) : ?\Artist4All\Model\Publication {
        $sql = 'UPDATE publications SET
            id_user=:id_user,
            bodyPublication=:bodyPublication,
            upload_date=:upload_date,
            n_likes=:n_likes,
            n_comments=:n_comments,
            n_views=:n_views
        WHERE id=:id
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $publication->getId(),
            ':id_user' => $publication->getIdUser(),
            ':bodyPublication' => $publication->getBodyPublication(),
            ':upload_date' => date('Y-m-d H:i:s'),
            ':n_likes' => $publication->getLikes(),
            ':n_comments' => $publication->getComments(),
            ':n_views' => $publication->getViews()
        ]);
        if(!$result) return null;
        return $publication;
    }

    public function insertPublicationImgs(int $id, string $img) : bool {
        $sql = 'INSERT INTO imgsPublications VALUES(
            :id,
            :imgPublication,
            :id_publication
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ 
            ':id' => null,
            ':imgPublication' => 'http://localhost:81/assets/img/' . $img,
            ':id_publication' => $id
        ]);
        return $result;
    }

    public function isAuthorizated(
        \Artist4All\Model\Publication $publication, 
        string $token) : bool {
        $sql = 'SELECT * FROM users WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':token' => $token ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return false;
        $user = \Artist4All\Model\User::fromAssoc($userAssoc);
        if ($user->getId() == $publication->getIdUser()) return true;
        return false;
    }

    public function deletePublicationById(int $id) : bool {
        $sql = "DELETE FROM publications WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ 'id' => $id ]);
        return $result; 
    }
}