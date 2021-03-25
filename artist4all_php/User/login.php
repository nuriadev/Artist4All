<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
require_once 'TokenSection/TokenController.php';
require_once '../app/src/Model/User.php';

try {
    // todo
    // 1 - hacerlo dentro de una clase UsuarioDB o por el estilo con funciones
    // 2 - hacer una classe de conexion y hacer que todas las clases de  
    //     interaccion con bbdd sean un extends de esa clase, para no repetir la conexion en cada php
    // 3 - hacerlo con modelo de constructor de user en vez de con variables
    // todo
    // conectamos mediante PDO con la bbdd
    $dsn = "mysql:host=localhost;dbname=artist4alldb";
    $dbusername = "root";
    $dbpassword = "root";
    //$dbpassword = "";
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => FALSE, 
    );
    $conn = new PDO($dsn, $dbusername, $dbpassword, $options);
    
    // recogemos los parámetro pasado por formData del UserService
    $email = $_POST["email"];
    $password = $_POST["password"];

    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "SELECT * FROM users WHERE email=:email AND deleted=:deleted";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':email'=> $email,
        ':deleted' => 0
    ]);

    // nos traemos los datos del select
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    // si el return es nulo, lo indicamos
    if (!$userAssoc) {
        echo "Usuario incorrecto";   
    // en caso contrario, hacemos la comprobación de la contraseña 
    // e indicamos la respuesta correspondiente
    } else {
        if (password_verify($password, $userAssoc["passwd"])) {       
            $arrayAux = array($userAssoc["email"], $userAssoc["passwd"], randomTokenPartGenerator());
            $content = implode(".", $arrayAux);
            // creamos el token a partir de la variable $content
            $token = tokenGenerator($content);
            // insertamos el token en la db
            $sql = "UPDATE users SET token=:token WHERE email=:email";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([
              ':token' => $token,
              ':email' => $userAssoc["email"]
            ]);

            $data = array(
                'token' => $token,
                'name' => $userAssoc['name_user'],
                'surname1' => $userAssoc['surname1'],
                'surname2' => $userAssoc['surname2'],
                'email' => $userAssoc['email'],
                'username' => $userAssoc['username'],
                'password' => $userAssoc['passwd'],
                'type_user' => $userAssoc['type_user'],
                // todo: cambiar el 0 por el n followers 
                'n_followers' => 0,
                'img' => $userAssoc['img'],
                'aboutMe' => $userAssoc['aboutMe']
            );
            echo json_encode($data);
        } else { 
            echo json_encode("Usuario incorrecto");   
        }
    }
    
    // en caso de error en el connect
} catch (PDOException $e) {
    echo "Error con la base de datos";
}
  