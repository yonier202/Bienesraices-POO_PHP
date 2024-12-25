<?php
    require 'includes/config/database.php';
    $db = conectarDB();

    $errores = [];

    // Autenticar el usuario
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        echo "<pre>";
        var_dump($_POST);
        echo "</pre>";

        $email = mysqli_real_escape_string($db, filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
        $password = mysqli_real_escape_string($db, $_POST['password']);

        if (!$email) {
            $errores[] = "El email es obligatorio o no es válido";
        }

        if (!$password) {
            $errores[] = "El password es obligatorio";
        }

        // Si no hay errores
        if (empty($errores)) {
            // Revisar si el usuario existe
            $sql = "SELECT * FROM usuarios WHERE email = '$email'";
            $result = mysqli_query($db, $sql);

            if ($result->num_rows) { // Si hay un resultado
                // Revisar si el password es correcto
                $usuario = mysqli_fetch_assoc($result);

                // Comprobar si la contraseña coincide con la encriptada en la base de datos
                $auth = password_verify($password, $usuario['password']);

                if ($auth) {
                    // Iniciar sesión
                    session_start();
                    $_SESSION['usuario'] = $usuario['email'];
                    $_SESSION['login'] = true;

                    header('Location: /admin');
                } else {
                    $errores[] = "Contraseña incorrecta";
                }
            } else {
                // Si el usuario no existe
                $errores[] = "El usuario no existe";
            }
        }
    }

    // Incluir el template de header
    include './includes/funciones.php';
    incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Iniciar Sesión</h1>

    <?php foreach($errores as $error) { ?>
        <div class="alerta error">
            <p><?php echo $error; ?></p>
        </div>
    <?php } ?>

    <form method="post" class="formulario">
        <fieldset>
            <legend>Email y Password</legend>

            <label for="email">E-mail</label>
            <input type="email" placeholder="Tu Email" id="email" name="email">

            <label for="password">Password</label>
            <input type="password" placeholder="Tu Password" id="password" name="password">
        </fieldset>

        <input type="submit" value="Iniciar Sesión" class="boton boton-verde">
    </form>
</main>

<?php
    incluirTemplate('footer');
?>
