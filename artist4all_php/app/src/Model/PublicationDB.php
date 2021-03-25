<?php
namespace Artist4All\Model;
// todo: que pille las rutas sin el require_once
require_once 'Publication.php';

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
        $dsn = "mysql:host=artist4all_db;dbname=artist4alldb";
        $dbusername = "root";
        $dbpassword = "password";
        $options = array(
            \PDO::ATTR_ERRMODE=>\PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_EMULATE_PREPARES => FALSE, 
        );
        $this->conn = new \PDO($dsn, $dbusername, $dbpassword, $options);
    }

    public function getPublicationById(int $id) : ?\Artist4All\Model\Publication {
        $sql = "SELECT * FROM publications WHERE id=:id";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $publicationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$publicationAssoc) return null;
        $publication = \Artist4All\Model\Publication::fromAssoc($publicationAssoc);
        return $publication;
    }

    public function createPublication(\Artist4All\Model\Publication $publication) : bool {
        $sql = "INSERT INTO publications VALUES(
            :id,
            :id_user,
            :bodyPublication,
            :upload_date,
            :n_likes,
            :n_comments,
            :n_views
        )";
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $publication->getId(),
            ':id_user' => $publication->getIdUser(),
            ':bodyPublication' => $publication->getBodyPublication(),
            ':upload_date' => date("Y-m-d H:i:s"),
            ':n_likes' => $publication->getLikes(),
            ':n_comments' => $publication->getComments(),
            ':n_views' => $publication->getViews()
        ]);
        return $result;
    }
}