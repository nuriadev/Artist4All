<?php
namespace Artist4all\Model;

class Session implements \JsonSerializable
{
  private string $token;
  private \Artist4all\Model\User $user;

  public function __construct(string $token, \Artist4all\Model\User $user ) {
    $this->token = $token;
    $this->user = $user;
  }

  public function getToken(): string { return $this->string; }
  public function setToken(string $token) { $this->token = $token; }

  public function getUser(): \Artist4all\Model\User { return $this->user; }
  public function setUser(\Artist4all\Model\User $user) { $this->user = $user; }

  // Needed to deserialize an object from an associative array
  public static function fromAssoc(array $data): \Artist4all\Model\Session {
    return new \Artist4all\Model\Session($data['token'], $data['user']);
  }

  // Needed for implicit JSON serialization with json_encode()
  public function jsonSerialize() {
    return ['token' => $this->token, 'user' => $this->user];
  }

  // Token related functions
  public static function tokenGenerator($content) {
    // identifica el algoritmo para generar la firma
    $header = base64_encode(json_encode(array('alg' => 'HS256', 'typ' => 'JWT')));
    // tiene la informacion de los privilegios token
    $payload = base64_encode($content);
    // palabra secreta para generar la firma
    $secret_key = 'my secret key';
    // se calcula codificando el encabezamiento y el contenido en base64url y concatenandolas con un punto
    $signature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, $secret_key, true));
    // token formado por el encabezado, el contenido y la firma que se concatenan con puntos
    $token = $header . '.' . $payload . '.' . $signature;
    return $token;
  }

  public static function randomTokenPart(int $length = 10) {
    $chars1 = "abcdefghijklmnopqrstuvwxyz";
    $chars2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $chars3 = "0123456789";
    $chars4 = "!$%&/+-_.:*";
    $randomTokenPart = substr(str_shuffle($chars1), 0, $length / 2 - 1);
    $randomTokenPart .= substr(str_shuffle($chars2), 0, $length / 2 - 1);
    $randomTokenPart .= substr(str_shuffle($chars3), 0, 1);
    $randomTokenPart .= substr(str_shuffle($chars4), 0, 1);
    $randomTokenPart = str_shuffle($randomTokenPart);
    return $randomTokenPart;
  }

  public static function verifyToken($token) {
    $secret_key = 'my secret key';
    $token_values = explode('.', $token);
    $header = $token[0];
    $payload = $token[1];
    $signature = $token[2];
    // se calcula codificando el encabezamiento y el contenido en base64url y concatenandolas con un punto
    $resulted_signature = base64_encode(hash_hmac('sha256', $header . '.' . $payload, $secret_key, true));
    // token formado por el encabezado, el contenido y la firma que se concatenan con puntos
    if ($resulted_signature == $signature) return $token;
    else return null;
  }
}
