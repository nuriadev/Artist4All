<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

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
    $aboutMe = $_POST["aboutMe"];
    $token = $_POST["token"];
   
    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "UPDATE users SET name_user=:name_user, surname1=:surname1, surname2=:surname2, email=:email, aboutMe=:aboutMe WHERE token=:token";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':name_user' => $name,
      ':surname1' => $surname1,
      ':surname2' => $surname2,
      ':email'=> $email,
      ':aboutMe' => $aboutMe,
      ':token' => $token
    ]);

    $sql = "SELECT * FROM users WHERE token=:token";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':token'=> $token
    ]);  
      
    // nos traemos los datos del select
    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
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
    
    // en caso de error en el connect
} catch (\PDOException $e) {
    echo "Error con la base de datos";
}
