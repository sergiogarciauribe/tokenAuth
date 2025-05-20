<?php

enum GeneralConfig: string {
    case host = 'localhost';
    case username = 'root';
    case password = 'password';
    case database = 'database';
    case dateConfig = 'Y-m-d H:i:s';
    case getSqlCommandQuery = 'SELECT password, salt FROM clientes WHERE email = ?';
    case encryptAlgorith = 'sha256';
    case encryptTokenAlgorith = 'HS256';
    case tokenKey = 'esta_es_mi_clave';
    case errorPageUrl = 'Location: server_error_500.html';
    case welcomePageUrl = 'Location: bienvenido.php';
    case loginPageUrl = 'Location: index.php';
    case tokenCookieName = 'jwt';
    case characterGame = 'UTF-8';
    case logFile = 'error.log';
    case logFileWarnings = 'warnings.log';
    case credentialFile = 'database.json';

}
