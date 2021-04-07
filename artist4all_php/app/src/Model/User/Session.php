<?php
namespace Artist4all\Model\User;
class Session implements \JsonSerializable {

  private string $token;
  private \Artist4all\Model\User\User $user; 

  public function __construct(
    string $token,
    \Artist4all\Model\User\User $user
    ) {
      $this->token = $token;
      $this->user = $user;
    }

    public function getToken() : string {
      return $this->string;
    }

    public function setToken(string $token) {
      $this->token = $token;
    }

    public function getUser() : \Artist4all\Model\User\User  {
      return $this->user;
    }

    public function setUser(\Artist4all\Model\User\User $user) {
      $this->user = $user;
    }

    // Needed to deserialize an object from an associative array
    public static function fromAssoc(array $data) : \Artist4all\Model\User\Session {
      return new \Artist4all\Model\User\Session(
        $data['token'], 
        $data['user']
      );
    }

    // Needed for implicit JSON serialization with json_encode()
    public function jsonSerialize() {
      return [
        'token' => $this->token,
        'user' => $this->user
      ];
    }
}