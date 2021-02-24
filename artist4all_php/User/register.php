<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
  
try {
    // todo hacerlo con modelo user
    $dsn = "mysql:host=localhost;dbname=artist4alldb";
    $dbusername = "root";
    $dbpassword = "";
    $options = array(
      PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
      PDO::ATTR_EMULATE_PREPARES => FALSE, 
    );
    $conn = new PDO($dsn, $dbusername, $dbpassword, $options);

    $email = $_POST["email"];
    $password = $_POST["password"];

    // todo: comprobar que las contraseñas sean iguales
    $sql = "INSERT INTO usuarios VALUES (:id_usuario, :email, :passwd)";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
      ':id_usuario' => null,
      ':email'=> $email,
      ':passwd' => password_hash($password, PASSWORD_DEFAULT)
    ]);

    $insertId = $conn->lastInsertId();
    if ($insertId) echo "Usuario registrado";
    else echo "Error en el insert";

} catch (\PDOException $e) {
    echo "Error con la base de datos";
}
