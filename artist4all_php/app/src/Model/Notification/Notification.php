<?php
namespace Artist4all\Model\Notification;

class Notification implements \JsonSerializable {
  private ?int $id;
  private  \Artist4all\Model\User\User $user_responsible;
  private  \Artist4all\Model\User\User $user_receiver;
  private string $bodyNotification;
  private int $isRead;
  private string $typeNotification;
  private string $notification_date;


  public function __construct(
    ?int $id,
    \Artist4all\Model\User\User  $user_responsible,
    \Artist4all\Model\User\User  $user_receiver,
    string $bodyNotification, 
    int $isRead,  
    string $typeNotification,
    string $notification_date) {
      $this->id = $id;
      $this->user_responsible = $user_responsible;
      $this->user_receiver = $user_receiver;
      $this->bodyNotification = $bodyNotification; 
      $this->isRead = $isRead;
      $this->typeNotification = $typeNotification;
      $this->notification_date = $notification_date;
    }

    public function getId() : ?int {
      return $this->id;
    }

    public function setId(?int $id) {
      $this->id = $id;
    }

    public function getUserResponsible() : \Artist4all\Model\User\User {
      return $this->user_responsible;
    }
  
    public function setUserResponsible(string $user_responsible) {
      $this->user_responsible = $user_responsible;
    }

    public function getUserReceiver() : \Artist4all\Model\User\User {
      return $this->user_receiver;
    }

    public function setUserReceiver(int $user_receiver) {
      $this->user_receiver = $user_receiver;
    }

    public function getBodyNotification() : string {
      return $this->bodyNotification;
    }

    public function setBodyNotification(string $bodyNotification) {
      $this->bodyNotification = $bodyNotification;
    }

    public function isRead() : int {
      return $this->isRead;
    }

    public function setIsRead(int $isRead) {
      $this->isRead = $isRead;
    }

    public function getTypeNotification() : string {
      return $this->typeNotification;
    }

    public function setTypeNotification(string $typeNotification) {
      $this->type = $typeNotification;
    }

    public function getNotificationDate() : string {
      return $this->notification_date;
    }

    public function setNotificationDate(string $notification_date) {
      $this->notification_date = $notification_date;
    }


    // Needed to deserialize an object from an associative array
    public static function fromAssoc(array $data) : \Artist4all\Model\Notification\Notification {
      return new \Artist4all\Model\Notification\Notification(
        $data['id'], 
        $data['user_responsible'],
        $data['user_receiver'],
        $data['bodyNotification'], 
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
        'bodyNotification' => $this->bodyNotification,
        'isRead' => $this->isRead,
        'typeNotification' => $this->typeNotification,
        'notification_date' => $this->notification_date,
      ];
    }
}