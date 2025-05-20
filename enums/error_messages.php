<?php
    enum ErrorMessages: string
    {
        case DATABASE_CONNECTION_FAILED = "No se pudo establecer conexión con la base de datos";
        case INVALID_USER_CREDENTIALS = "Credenciales de usuario inválidas";
        case FILE_NOT_FOUND = "El archivo no fue encontrado";
        case INVALID_JSON = "El archivo no es un JSON válido";
        case MISSING_CREDENTIALS = "El archivo de credenciales no contiene todas las claves requeridas.";
        case INVALID_CREDENTIALS = "Contraseña incorrecta. Inténtalo de nuevo.";
        case USER_NOT_FOUND = "Usuario no encontrado. Verifica tu nombre de usuario.";

    }
?>