<?php
header('Access-Control-Allow-Origin: *');
header("Access-Control-Allow-Headers: X-API-KEY, Origin, X-Requested-With, Content-Type, Accept, Access-Control-Request-Method");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
header("Allow: GET, POST, OPTIONS, PUT, DELETE");
require_once 'TokenSection/TokenController.php';

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
    $dbpassword = "";
    $options = array(
        PDO::ATTR_ERRMODE=>PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_EMULATE_PREPARES => FALSE, 
    );
    $conn = new PDO($dsn, $dbusername, $dbpassword, $options);
    
    // recogemos los parámetro pasado por formData del UserService
    $email = $_POST["email"];
    $password = $_POST["password"];

    // hacemos la petición sql y ejecutamos la sentencia
    $sql = "SELECT * FROM usuarios WHERE email=:email";
    $statement = $conn->prepare($sql);
    $result = $statement->execute([
        ':email'=> $email
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
            // todo: cambiar las variables del array (por determinar)        
            $arrayAux = array($user["email"], $user["passwd"]);
            $content = implode(".", $arrayAux);
            // creamos el token a partir de la variable $content
            $token = tokenGenerator($content);
            $feedbackMessage = array(
                'respuesta' => 'Logueado',
                'usuario' => $user,
                'token' => $token
            );
            echo json_encode($feedbackMessage);
        } else { 
            echo json_encode("Usuario incorrecto");   
        }
    }
    
    // en caso de error en el connect
} catch (PDOException $e) {
    echo "Error con la base de datos";
}
  