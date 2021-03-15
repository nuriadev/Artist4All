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
    $n_followers = $_POST["n_followers"];
    //$img = "http://localhost/daw2/Artist4all/artist4all_php/User/assets/img/imgUnknown.png";
    $img = "http://localhost:8888/daw2/Artist4all/artist4all_php/User/assets/img/imgUnknown.png";
    $aboutMe = "Bienvendio a mi perfil";

    // creamos un usuario con los datos recogidos
    $user = new \Artist4All\Model\User(null, $name, $surname1, $surname2, $email, $username, $password, $n_followers, $type_user, $img, $aboutMe);
    
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
      :aboutMe,
      :token,
      :deleted
    )";
    $statement = $conn->prepare($sql);
    $password_hashed = password_hash($user->getPassword(), PASSWORD_DEFAULT);
    $arrayAux = array($email, $password_hashed, randomTokenPartGenerator());
    $content = implode(".", $arrayAux);
    // creamos el token a partir de la variable $content
    $token = tokenGenerator($content);

    $result = $statement->execute([
      ':id_user' => $user->getId(),
      ':name_user' => $user->getName(),
      ':surname1' => $user->getSurname1(),
      ':surname2' => $user->getSurname2(),
      ':email'=> $user->getEmail(),
      ':username' => $user->getUsername(),
      ':passwd' => $password_hashed,
      ':type_user' => $user->getTypeUser(),
      ':img' => $user->getImg(),
      ':aboutMe' => $user->getAboutMe(),
      ':token' => $token,
      ':deleted' => 0
    ]);

    // recogemos el id del usuario registrado para comprobar 
    // si se ha registrado o no y devolvemos una resupesta
    $insertId = $conn->lastInsertId();
    if ($insertId) {
      $sql = "SELECT * FROM users WHERE id_user=:id_user";
      $statement = $conn->prepare($sql);
      $result = $statement->execute([
          ':id_user'=> $insertId
      ]);  
      
      // nos traemos los datos del select
      $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
      // si el return es nulo, lo indicamos
      if (!$userAssoc) {
        echo "Usuario incorrecto";   
      // en caso contrario, hacemos la comprobación de la contraseña 
      // e indicamos la respuesta correspondiente
      } else {
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
      }
    } else {
      echo json_encode("Error en el registro");
    } 
    // en caso de error en el connect
} catch (\PDOException $e) {
    echo "Error con la base de datos";
}
