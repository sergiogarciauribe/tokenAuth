<?php
// Importar configuraciones y utilidades
include_once 'logger.php';
include_once 'enums/general_config.php';
include_once 'enums/error_messages.php';

// Configurar archivos
const CREDENTIALS_FILE = GeneralConfig::credentialFile->value;
const LOG_FILE = GeneralConfig::logFile->value;

// Configurar el archivo de log
Logger::setLogFile(LOG_FILE);

// Función para manejar errores y redirecciones
function handleError(string $errorMessage, string $logFile, string $redirectPage): void
{
    Logger::logError($errorMessage, $logFile);
    header("Location: $redirectPage");
    exit; // Asegura que el script se detenga después de la redirección
}

// Función para cargar las credenciales desde el archivo JSON
function loadCredentials(string $credentialsFile): ?array
{
    if (!file_exists($credentialsFile) || !is_readable($credentialsFile)) {
        handleError(
            ErrorMessages::FILE_NOT_FOUND->value,
            LOG_FILE,
            GeneralConfig::errorPageUrl->value
        );
        return null;
    }

    $credentials = json_decode(file_get_contents($credentialsFile), true);
    if ($credentials === null) {
        handleError(
            ErrorMessages::INVALID_JSON->value,
            LOG_FILE,
            GeneralConfig::errorPageUrl->value
        );
        return null;
    }

    return $credentials;
}

// Función para validar las credenciales
function validateCredentials(array $credentials): bool
{
    $requiredKeys = [
        GeneralConfig::host->name,
        GeneralConfig::username->name,
        GeneralConfig::password->name,
        GeneralConfig::database->name
    ];

    return count(array_diff($requiredKeys, array_keys($credentials))) === 0;
}

// Función para establecer la conexión a la base de datos
function establishConnection(array $credentials): ?mysqli
{
    $host = $credentials[GeneralConfig::host->name];
    $username = $credentials[GeneralConfig::username->name];
    $password = $credentials[GeneralConfig::password->name];
    $database = $credentials[GeneralConfig::database->name];

    try {
        $connection = new mysqli($host, $username, $password, $database);
        if ($connection->connect_error) {
            throw new Exception($connection->connect_error);
        }
        return $connection;
    } catch (Exception $ex) {
        handleError(
            ErrorMessages::DATABASE_CONNECTION_FAILED->value . $ex->getMessage(),
            LOG_FILE,
            GeneralConfig::errorPageUrl->value
        );
        return null;
    }
}

// Cargar credenciales
$credentials = loadCredentials(CREDENTIALS_FILE);
if ($credentials === null) {
    exit; // El manejo del error ya se realizó en loadCredentials
}

// Validar credenciales
if (!validateCredentials($credentials)) {
    handleError(
        ErrorMessages::MISSING_CREDENTIALS->value,
        LOG_FILE,
        GeneralConfig::errorPageUrl->value
    );
    exit;
}

// Establecer conexión
$connection = establishConnection($credentials);
if ($connection === null) {
    exit; // El manejo del error ya se realizó en establishConnection
}

?>