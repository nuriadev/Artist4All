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
    $token = $_POST["token"];

    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "SELECT id_user,
                   name_user, 
                   surname1,
                   surname2, 
                   email, 
                   username, 
                   img 
    FROM users WHERE token=:token";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':token'=> $token
    ]);

    // nos traemos los datos del select
    $user = $statement->fetch(\PDO::FETCH_ASSOC);
    // si el return es nulo, lo indicamos
    if (!$user) {
        echo "Usuario no encontrado";   
    // en caso contrario, hacemos la comprobación de la contraseña 
    // e indicamos la respuesta correspondiente
    } else {            
        $feedbackMessage = array(
            'id' => $user["id_user"],
            'name' => $user["name_user"], 
            'surname1' => $user["surname1"],
            'surname2' => $user["surname2"], 
            'email' => $user["email"], 
            'username' => $user["username"], 
            'img' => $user["img"]
        );
        echo json_encode($feedbackMessage);
    }
    
    // en caso de error en el connect
} catch (PDOException $e) {
    echo "Error con la base de datos";
}
  