<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

require_once 'TokenSection/TokenController.php';
require_once '../app/src/Model/User.php';

try {
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
    $name = $_POST["name"];
    $surname1 = $_POST["surname1"];
    $surname2 = $_POST["surname2"];
    $email = $_POST["email"];
    $username = $_POST["username"];
    $password = $_POST["password"];
    $type_user = $_POST["type_user"];

    // creamos un usuario con los datos recogidos
    $user = new \Artist4All\Model\User(null, $name, $surname1, $surname2, $email, $username, $password, 0, $type_user);
    // todo: comprobar que las contraseñas sean iguales
    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "INSERT INTO users VALUES (
      :id_user, 
      :name_user, 
      :surname1, 
      :surname2, 
      :email, 
      :username, 
      :passwd, 
      :type_user, 
      :img,
      :token,
      :deleted
    )";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_user' => $user->getId(),
      ':name_user' => $user->getName(),
      ':surname1' => $user->getSurname1(),
      ':surname2' => $user->getSurname2(),
      ':email'=> $user->getEmail(),
      ':username' => $user->getUsername(),
      ':passwd' => password_hash($user->getPassword(), PASSWORD_DEFAULT),
      ':type_user' => $user->getType_user(),
      // todo: añadir img base,
      ':img' => '',
      ':token' => '',
      ':deleted' => 0
    ]);

    // recogemos el id del usuario registrado para comprobar 
    // si se ha registrado o no y devolvemos una resupesta
    // $insertId = $conn->lastInsertId();
    // if ($insertId) {echo "Usuario registrado";
    // else echo "Error en el insert";

    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "SELECT * FROM users WHERE email=:email AND deleted=:deleted";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':email'=> $email,
        ':deleted' => 0
    ]);

    // nos traemos los datos del select
    $user = $statement->fetch(\PDO::FETCH_ASSOC);
    // si el return es nulo, lo indicamos
    if (!$user) {
        echo "Usuario incorrecto";   
    // en caso contrario, hacemos la comprobación de la contraseña 
    // e indicamos la respuesta correspondiente
    } else {
        if (password_verify($password, $user["passwd"])) {        
            $arrayAux = array($user["email"], $user["passwd"], randomTokenPartGenerator());
            $content = implode(".", $arrayAux);
            // creamos el token a partir de la variable $content
            $token = tokenGenerator($content);
            // insertamos el token en la db
            $sql = "UPDATE users SET token=:token WHERE email=:email";
            $statement = $conn->prepare($sql);
            $result = $statement->execute([
              ':token' => $token,
              ':email' => $user["email"]
            ]);
            $feedbackMessage = array(
                'response' => 'Logueado',
                'token' => $token
            );
            echo json_encode($feedbackMessage);
        } else { 
            echo json_encode("Usuario incorrecto");   
        }
    }

    // en caso de error en el connect
} catch (\PDOException $e) {
    echo "Error con la base de datos";
}
