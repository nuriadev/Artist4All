<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
require_once 'TokenSection/TokenController.php';
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
    
    // // recogemos los parámetro pasado por formData del UserService
    // $token = $_POST["token"];

    // // hacemos la petición sql y ejecutamos la sentencia
    // $sql = "SELECT id_user FROM users WHERE token=:token";
    // $statement = $conn->prepare($sql);
    // $result = $statement->execute([
    //     ':token'=> $token
    // ]);

    // // nos traemos los datos del select
    // $idUser = $statement->fetch(\PDO::FETCH_ASSOC);
    // // si el return es nulo, lo indicamos
    // if (!$idUser) {
    //     echo "Usuario no autorizado";   
    // // en caso contrario, hacemos la comprobación de la contraseña 
    // // e indicamos la respuesta correspondiente
    // } else {            
    //     return true;
    // }
    
    // en caso de error en el connect
} catch (PDOException $e) {
    echo "Error con la base de datos";
}
  