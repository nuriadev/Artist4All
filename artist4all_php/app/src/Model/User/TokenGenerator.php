<?php
namespace Artist4all\Model\User;
class TokenGenerator {

    public static function tokenGenerator($content) {
        // identifica el algoritmo para generar la firma
        $header = base64_encode(json_encode(array('alg' => 'HS256', 'typ' => 'JWT')));
        // tiene la informacion de los privilegios token
        $payload = base64_encode($content);
        // palabra secreta para generar la firma
        $secret_key = 'my secret key';
        // se calcula codificando el encabezamiento y el contenido en base64url y concatenandolas con un punto
        $signature = base64_encode(hash_hmac('sha256',$header.'.'.$payload,$secret_key,true));
        // token formado por el encabezado, el contenido y la firma que se concatenan con puntos
        $token = $header.'.'.$payload.'.'.$signature;
        return $token;
    }

    public static function randomTokenPartGenerator(int $length = 10) {
        $chars1 = "abcdefghijklmnopqrstuvwxyz";
        $chars2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $chars3 = "0123456789";
        $chars4 = "!$%&/+-_.:*";
        $randomTokenPart = substr(str_shuffle($chars1), 0, $length / 2 - 1);
        $randomTokenPart.= substr(str_shuffle($chars2), 0, $length / 2 - 1);
        $randomTokenPart.= substr(str_shuffle($chars3), 0, 1);
        $randomTokenPart.= substr(str_shuffle($chars4), 0, 1);
        $randomTokenPart = str_shuffle($randomTokenPart);
        return $randomTokenPart;
    }
}