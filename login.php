<?php
require 'vendor/autoload.php'; // Asegúrate de que la ubicación del archivo autoload.php sea correcta

use Firebase\JWT\JWT;
//incluyendo el archivo que permite llamar la configuración:
include_once 'installer.php';

if (empty($_POST['nombre_usuario']) || empty($_POST['contrasena'])) {
    // Redirigir a una página de error o una plantilla HTML específica
    header(GeneralConfig::loginPageUrl->value);
    exit(); // Importante: asegúrate de que el script se detenga después de la redirección
}
// Sanitizar la entrada del usuario y la contraseña
$enteredUsername = CleanEntries($_POST['nombre_usuario']);
$enteredPassword = CleanEntries($_POST['contrasena']);

try{
    // Consulta SQL para obtener el hash de la contraseña y la sal del usuario
    //Nótese cómo se usa el enum para evitar colocar la consulta SQL como 'magic literal'
    $stmt = $connection->prepare(GeneralConfig::getSqlCommandQuery->value);
    $stmt->bind_param("s", $enteredUsername);
    $stmt->execute();
    $stmt->bind_result($hashedPassword, $salt);

    if ($stmt->fetch()) {
        // Combinar la contraseña ingresada con la sal almacenada en la base de datos
        $passwordWithSalt = $enteredPassword . $salt;
        
        //Usamos sha256 para encriptar (Revisar en el archivo general_config.php la propiedad 'encryptAlgorith')
        $hashedEnteredPassword = hash(GeneralConfig::encryptAlgorith->value, $passwordWithSalt);

        // Verificar si la contraseña ingresada coincide con la almacenada en la base de datos
        if ($hashedEnteredPassword === $hashedPassword) {
            //Crear un tiket JWT para el usuario y el role:
            // Debes usar una clave secreta fuerte y guardada de forma segura
            $key = "esta_es_mi_clave";

            $payload = array(
                "nombre_usuario" => $enteredUsername,
                "role" => "admin", //El rol generalmente se encuentra almacenado en la BD y permite o
                                //deniega acceso dependiendo de las condiciones del programador
                "exp" => time() + 3600 // Tiempo de expiración del token (1 hora)
            );
            JWT::encode($payload, $key, GeneralConfig::encryptTokenAlgorith->value);
            almacenar_cookie($token);//si ocurre Inicio de sesión exitoso, cree una cookie con la información básica del usuario:
            session_start();
            $_SESSION["usuario"] = $usuario;
            header(GeneralConfig::welcomePageUrl->value); // Redirigir a la página de bienvenida
        } else {
            // Configurar el archivo de log
            Logger::setLogFile(GeneralConfig::logFileWarnings->value);
            Logger::logError(ErrorMessages::INVALID_CREDENTIALS->value, GeneralConfig::logFileWarnings->value);
            header(GeneralConfig::loginPageUrl->value); // Redirigir a la página de inicio de sesión
        }
    } else {
        Logger::logError(ErrorMessages::USER_NOT_FOUND->value, GeneralConfig::logFileWarnings->value);
        header(GeneralConfig::loginPageUrl->value); // Redirigir a la página de inicio de sesión
    }
    // Cierra la conexión y la declaración
    $stmt->close();
    $connection->close();

}catch(Exception $ex){
    $error_message = "Ocurrió una excepción al intenter loguear el usuario: " . $ex->getMessage();
    Logger::logError($error_message, $log_file);
    header(GeneralConfig::errorPageUrl->value); // Redirigir a la página de bienvenida

}

function almacenar_cookie($token) {
    // Implementa tu lógica de encriptación aquí (por ejemplo, usando OpenSSL)
    // Aquí hay un ejemplo simple utilizando base64 para demostración:
    $token_encoded =  base64_encode($token);
    // Define la vida de la cookie en segundos (por ejemplo, 7 días)
    $tiempo_expiracion = time() + (3600);
    // Establece la cookie encriptada
    setcookie(GeneralConfig::tokenCookieName->value, $token_encoded, $tiempo_expiracion, '/');
}

function CleanEntries($entrada) {
    // Eliminar espacios en blanco al principio y al final
    $entradaSinEspacios = trim($entrada);
    // Codificar caracteres especiales en HTML
    $cadenaLimpia = htmlspecialchars($entradaSinEspacios, ENT_QUOTES, GeneralConfig::characterGame->value);
    return $cadenaLimpia;
}

?>
