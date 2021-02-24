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

    $sql = "SELECT * FROM usuarios WHERE email=:email";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':email'=> $email
    ]);

    $userAssoc = $statement->fetch(\PDO::FETCH_ASSOC);
    if (!$userAssoc) {
        echo "Usuario incorrecto";   
    } else {
        // todo: añadir token
        if (password_verify($password, $userAssoc["passwd"])) echo json_encode(["Logueado", $userAssoc]);
        else echo "Usuario incorrecto";
    }
    
} catch (PDOException $e) {
    echo "Error con la base de datos";
}
  