<!DOCTYPE html>
<html>
<head>
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/css/materialize.min.css">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col s12 m6 offset-m3">
                <div class="card">
                    <div class="card-content">
                        <span class="card-title">Iniciar Sesión</span>
                        <form method="post" action="login.php">
                            <div class="input-field">
                                <input type="text" id="nombre_usuario" name="nombre_usuario" class="validate">
                                <label for="nombre_usuario">Nombre de Usuario</label>
                            </div>
                            <div class="input-field">
                                <input type="password" id="contrasena" name="contrasena" class="validate">
                                <label for="contrasena">Contraseña</label>
                            </div>
                            <button class="btn waves-effect waves-light" type="submit" name="action">Iniciar Sesión</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/1.0.0/js/materialize.min.js"></script>
</body>
</html>
