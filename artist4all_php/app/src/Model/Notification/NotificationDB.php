<?php
namespace Artist4all\Model\Notification;

class NotificationDB {
    protected static ?\Artist4all\Model\Notification\NotificationDB $instance = null;

    public static function getInstance() : \Artist4all\Model\Notification\NotificationDB {
        if(is_null(static::$instance)) {
            static::$instance = new \Artist4all\Model\Notification\NotificationDB();
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

    public function getNotificationById(int $id) : ?\Artist4all\Model\Notification\Notification {
        $sql = 'SELECT * FROM notifications WHERE id=:id';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ ':id' => $id ]);
        $notificationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
        if (!$notificationAssoc) return null;
        $notification = \Artist4all\Model\Notification\Notification::fromAssoc($notificationAssoc);
        return $notification;
    }

    public function getMyNotifications(int $id_receiver) : ?array {
        $sql = 'SELECT * FROM notifications WHERE 
        id_receiver=:id_receiver AND
        isRead=:isRead ORDER BY id DESC';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([ 
            ':id_receiver' => $id_receiver,
            ':isRead' => 0
        ]);
        $notificationsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
        if (!$notificationsAssoc) return null;
        $notifications = [];
        foreach($notificationsAssoc as $notificationAssoc) {
            $id_responsible = $notificationAssoc['id_responsible'];
            $id_receiver = $notificationAssoc['id_receiver'];
            $user_responsible = \Artist4all\Model\User\UserDB::getInstance()->getUserById($id_responsible);
            $user_receiver = \Artist4all\Model\User\UserDB::getInstance()->getUserById($id_receiver);
            $notificationAssoc['user_responsible'] = $user_responsible;
            $notificationAssoc['user_receiver'] = $user_receiver;
            $notifications[] = \Artist4all\Model\Notification\Notification::fromAssoc($notificationAssoc);
        }  
        return $notifications;
    }

    public function insertNotification(\Artist4all\Model\Notification\Notification $notification) : ?\Artist4all\Model\Notification\Notification {
        $sql = 'INSERT INTO notifications VALUES(
            :id,
            :id_responsible,
            :id_receiver,
            :bodyNotification,
            :isRead,
            :typeNotification,
            :notification_date
        )';
        $statement = $this->conn->prepare($sql);
        $result = $statement->execute([
            ':id' => $notification->getId(),
            ':id_responsible' => $notification->getUserResponsible()->getId(),
            ':id_receiver' => $notification->getUserReceiver()->getId(),
            ':bodyNotification' => $notification->getBodyNotification(),
            ':isRead' => $notification->isRead(),
            ':typeNotification' => $notification->getTypeNotification(),
            ':notification_date' => date('Y-m-d H:i:s')
        ]);
        if(!$result) return null;
        $id = $this->conn->lastInsertId();
        $notification->setId($id);
        return $notification;
    }
}