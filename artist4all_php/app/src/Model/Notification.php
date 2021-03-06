<?php
namespace Artist4all\Model;

class Notification implements \JsonSerializable {
  private ?int $id;
  private \Artist4all\Model\User $user_responsible;
  private \Artist4all\Model\User $user_receiver;
  private int $isRead;
  private string $typeNotification;
  private string $notification_date;


  public function __construct(
    ?int $id,
    \Artist4all\Model\User $user_responsible,
    \Artist4all\Model\User $user_receiver,
    int $isRead,
    string $typeNotification,
    string $notification_date
  ) {
    $this->id = $id;
    $this->user_responsible = $user_responsible;
    $this->user_receiver = $user_receiver;
    $this->isRead = $isRead;
    $this->typeNotification = $typeNotification;
    $this->notification_date = $notification_date;
  }

  public function getId(): ?int { return $this->id; }
  public function setId(?int $id) { $this->id = $id; }

  public function getUserResponsible(): \Artist4all\Model\User { return $this->user_responsible; }
  public function setUserResponsible(\Artist4all\Model\User $user_responsible) { $this->user_responsible = $user_responsible; }

  public function getUserReceiver(): \Artist4all\Model\User { return $this->user_receiver; }
  public function setUserReceiver(\Artist4all\Model\User $user_receiver) { $this->user_receiver = $user_receiver; }

  public function isRead(): int { return $this->isRead; }
  public function setIsRead(int $isRead) { $this->isRead = $isRead; }

  public function getTypeNotification(): string { return $this->typeNotification; }
  public function setTypeNotification(string $typeNotification) { $this->type = $typeNotification; }

  public function getNotificationDate(): string { return $this->notification_date; }
  public function setNotificationDate(string $notification_date) { $this->notification_date = $notification_date; }


  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Notification {
    return new \Artist4all\Model\Notification(
      $data['id'],
      $data['user_responsible'],
      $data['user_receiver'],
      $data['isRead'],
      $data['typeNotification'],
      $data['notification_date'],
    );
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return [
      'id' => $this->id,
      'user_responsible' => $this->user_responsible,
      'user_receiver' => $this->user_receiver,
      'isRead' => $this->isRead,
      'typeNotification' => $this->typeNotification,
      'notification_date' => $this->notification_date,
    ];
  }

  // DAO METHODS
  public static function getNotificationById(int $id): ?\Artist4all\Model\Notification {
    $sql = 'SELECT * FROM notifications WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    $notificationAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$notificationAssoc) return null;
    $id_responsible = $notificationAssoc['id_responsible'];
    $id_receiver = $notificationAssoc['id_receiver'];
    $user_responsible = \Artist4all\Model\User::getUserById($id_responsible);
    $user_receiver = \Artist4all\Model\User::getUserById($id_receiver);
    $notificationAssoc['user_responsible'] = $user_responsible;
    $notificationAssoc['user_receiver'] = $user_receiver;
    $notificationAssoc['bodyNotification'] = null;
    $notification = \Artist4all\Model\Notification::fromAssoc($notificationAssoc);
    return $notification;
  }

  public static function getNotifications(int $id_receiver): ?array {
    $sql = 'SELECT * FROM notifications WHERE id_receiver=:id_receiver AND isRead=:isRead ORDER BY id DESC';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_receiver' => $id_receiver, 
      ':isRead' => 0
    ]);
    $notificationsAssoc = $statement->fetchAll(\PDO::FETCH_ASSOC);
    if (!$notificationsAssoc) return null;
    $notifications = [];
    foreach ($notificationsAssoc as $notificationAssoc) {
      $id_responsible = $notificationAssoc['id_responsible'];
      $id_receiver = $notificationAssoc['id_receiver'];
      $user_responsible = \Artist4all\Model\User::getUserById($id_responsible);
      $user_receiver = \Artist4all\Model\User::getUserById($id_receiver);
      $notificationAssoc['user_responsible'] = $user_responsible;
      $notificationAssoc['user_receiver'] = $user_receiver;
      $notificationAssoc['bodyNotification'] = null;
      $notifications[] = \Artist4all\Model\Notification::fromAssoc($notificationAssoc);
    }
    return $notifications;
  }

  public static function insertNotification(\Artist4all\Model\Notification $notification): ?\Artist4all\Model\Notification {
    $sql = 'INSERT INTO notifications VALUES(
        :id,
        :id_responsible,
        :id_receiver,
        :isRead,
        :typeNotification,
        :notification_date
    )';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id' => $notification->getId(),
      ':id_responsible' => $notification->getUserResponsible()->getId(),
      ':id_receiver' => $notification->getUserReceiver()->getId(),
      ':isRead' => $notification->isRead(),
      ':typeNotification' => $notification->getTypeNotification(),
      ':notification_date' => date('Y-m-d H:i:s')
    ]);
    if (!$result) return null;
    $id = $conn->lastInsertId();
    $notification->setId($id);
    return $notification;
  }

  public static function existsNotification(int $id_responsible, int $id_receiver, int $typeNotification) {
      $sql = 'SELECT * FROM notifications WHERE id_responsible=:id_responsible 
      AND id_receiver=:id_receiver AND isRead=:isRead AND typeNotification=:typeNotification';
      $conn = Database::getInstance()->getConnection();
      $statement = $conn->prepare($sql);
      $result = $statement->execute([
        ':id_responsible' => $id_responsible,
        ':id_receiver' => $id_receiver,
        ':isRead' => 0,
        ':typeNotification' => $typeNotification     
      ]);
      $exists = $statement->rowCount();
      if ($exists == '') return false;
      return true;
  }

  public static function notificationRead(int $id): bool {
    $sql = 'UPDATE notifications SET isRead=:isRead WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':isRead' => 1,
      ':id' => $id
    ]);
    return $result;
  }

  public static function removeNotification(int $id) {
    $sql = 'DELETE FROM notifications WHERE id=:id';
    $conn = Database::getInstance()->getConnection();
    $statement = $conn->prepare($sql);
    $result = $statement->execute([':id' => $id]);
    return $result;
  }
}
