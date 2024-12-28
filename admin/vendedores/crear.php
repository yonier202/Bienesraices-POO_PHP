<?php
    include '../../includes/app.php';

    use App\Vendedor;

    // Proteger esta ruta.
    estaAutenticado();

    $vendedor = new Vendedor();

    $errores = Vendedor::getErrores();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $vendedor = new Vendedor($_POST['vendedor']);

        $errores = $vendedor->validar();

        if (empty($errores)) {
            $vendedor->guardar();
        }
    }

    incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Registrar Vendedor</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST">
        <?php include '../../includes/templates/formulario_vendedores.php' ;?>
        <input type="submit" value="Registrar vendedor" class="boton boton-verde">

    </form>

</main>


<?php

incluirTemplate('footer');