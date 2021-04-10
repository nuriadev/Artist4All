<?php
namespace Artist4all\Model\Publication;

class PublicationDB {
    protected static ?\Artist4all\Model\Publication\PublicationDB $instance = null;

    public static function getInstance() : \Artist4all\Model\Publication\PublicationDB {
        if(is_null(static::$instance)) {
            static::$instance = new \Artist4all\Model\Publication\PublicationDB();
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

    public function getPublicationById(int $id) : ?\Artist4all\Model\Publication\Publication {
        $sql = 'SELECT * FROM publications WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $publicationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$publicationAssoc) return null;
        $imgsPublication = $this->getPublicationImgs($id);
        $publicationAssoc['imgsPublication'] = $imgsPublication;
        $publication = \Artist4all\Model\Publication\Publication::fromAssoc($publicationAssoc);
        return $publication;
    }

    public function getUserPublications(int $id_user) : ?array {
        $sql = 'SELECT * FROM publications WHERE id_user=:id_user ORDER BY id DESC';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id_user' => $id_user ]);
        $publicationsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (!$publicationsAssoc) return null;
        $publications = [];
        foreach($publicationsAssoc as $publicationAssoc) {
            $publicationAssoc['imgsPublication'] = $this->getPublicationImgs($publicationAssoc['id']);
            $publications[] = \Artist4all\Model\Publication\Publication::fromAssoc($publicationAssoc);
        }  
        return $publications;
    }

    public function getPublicationImgs(int $id) : ?array {
        $sql = 'SELECT imgPublication FROM imgs_publications WHERE id_publication=:id_publication';
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

    public function deletePublicationImgs(int $id) : bool {
        $sql = "DELETE FROM imgs_publications WHERE id_publication=:id_publication";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ 'id_publication' => $id ]);
        return $result; 
    }

    public function persistPublication(Publication $publication) : ?\Artist4all\Model\Publication\Publication {
        if (is_null($publication->getId())) return $this->insertPublication($publication);
        else return $this->updatePublication($publication);
    }

    public function insertPublication(\Artist4all\Model\Publication\Publication $publication) : ?\Artist4all\Model\Publication\Publication {
        $sql = 'INSERT INTO publications VALUES(
            :id,
            :id_user,
            :bodyPublication,
            :upload_date,
            :n_likes,
            :n_comments
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $publication->getId(),
            ':id_user' => $publication->getIdUser(),
            ':bodyPublication' => $publication->getBodyPublication(),
            ':upload_date' => date('Y-m-d H:i:s'),
            ':n_likes' => $publication->getLikes(),
            ':n_comments' => $publication->getComments()
        ]);
        if(!$result) return null;
        $id = $this->conn->lastInsertId();
        $publication->setId($id);
        return $publication;
    }

    public function updatePublication(\Artist4all\Model\Publication\Publication $publication) : ?\Artist4all\Model\Publication\Publication {
        $sql = 'UPDATE publications SET
            id_user=:id_user,
            bodyPublication=:bodyPublication,
            upload_date=:upload_date,
            n_likes=:n_likes,
            n_comments=:n_comments
        WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $publication->getId(),
            ':id_user' => $publication->getIdUser(),
            ':bodyPublication' => $publication->getBodyPublication(),
            ':upload_date' => date('Y-m-d H:i:s'),
            ':n_likes' => $publication->getLikes(),
            ':n_comments' => $publication->getComments()
        ]);
        if(!$result) return null;
        return $publication;
    }

    public function insertPublicationImgs(int $id, string $img) : bool {
        $sql = 'INSERT INTO imgs_publications VALUES(
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

    public function canPublish(
        \Artist4all\Model\Publication\Publication $publication, 
        string $token) : bool {
        $sql = 'SELECT * FROM users WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':token' => $token ]);
        $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$userAssoc) return false;
        $user = \Artist4all\Model\User\User::fromAssoc($userAssoc);
        if ($user->getId() == $publication->getIdUser()) return true;
        return false;
    }

    public function isValidToken(string $token) : bool {
        $sql = 'SELECT * FROM users WHERE token=:token';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':token' => $token ]);
        return $result;
    }

    public function deletePublicationById(int $id) : bool {
        $sql = "DELETE FROM publications WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ 'id' => $id ]);
        return $result; 
    }
}
