<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");

try {
    // todo hacerlo con modelo user
    // conectamos mediante PDO con la bbdd
    $dsn = "mysql:host=localhost;dbname=artist4alldb";
    $dbusername = "root";
    $dbpassword = "";
    $options = array(
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => FALSE, 
    );
    $conn = new PDO($dsn, $dbusername, $dbpassword, $options);

    // recogemos los parámetro pasado por formData del UserService
    $email = $_POST["email"];
    $password = $_POST["password"];

    // todo: comprobar que las contraseñas sean iguales
    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "INSERT INTO usuarios VALUES (:id_usuario, :email, :passwd)";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_usuario' => null,
      ':email'=> $email,
      ':passwd' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    // recogemos el id del usuario registrado para comprobar 
    // si se ha registrado o no y devolvemos una resupesta
    $insertId = $conn->lastInsertId();
    if ($insertId) echo "Usuario registrado";
    else echo "Error en el insert";

    // en caso de error en el connect
} catch (\PDOException $e) {
    echo "Error con la base de datos";
}
