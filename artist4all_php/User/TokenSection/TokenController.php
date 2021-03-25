<?php

function tokenGenerator($content) {
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

function randomTokenPartGenerator(int $length = 10) {
    $chars1 = "abcdefghijklmnopqrstuvwxyz";
    $chars2 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    $chars3 = "0123456789";
    $chars4 = "!$%&/+-_.:*";
    $password = substr(str_shuffle($chars1), 0, $length / 2 - 1);
    $password.= substr(str_shuffle($chars2), 0, $length / 2 - 1);
    $password.= substr(str_shuffle($chars3), 0, 1);
    $password.= substr(str_shuffle($chars4), 0, 1);
    $password = str_shuffle($password);
    return $password;
}

function tokenChecker($token) {
    $secret_key = 'my secret key';
    $jwt_token_values = explode('.',$token);
    // extramemos las 3 partes del token en diferentes variables
    $header = $jwt_token_values[0];
    $payload = $jwt_token_values[1];
    $signature = $jwt_token_values[2];

    // si la firma resultante es la misma que la que he formada 
    // por las mismas variables de la función de arriba 
    // implica que son iguales 
    $matching_signature = base64_encode(hash_hmac('sha256',$header.'.'.$payload,$secret_key,true));
    // comprueba si la firma creada por $matching_signature es la misma de la del token recibido
    if ($matching_signature == $signature) return true;
    else return false; 
}

function verifyRequestToken() {
    // recogemos la cabezera de la web
    $headers = apache_request_headers();
    // miramos si el apartado Authorization existe y no está vacío
    if((isset($headers['Authorization']) && !empty($headers['Authorization']))) {
        // lo recogemos en una variable si se cumple la condición de arriba
        $token_receiver = $headers['Authorization'];
        // verificamos si el token recibido es válido 
        if (tokenChecker($token_receiver)) return true;
        else return false;
    }
}