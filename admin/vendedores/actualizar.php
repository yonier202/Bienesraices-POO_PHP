<?php
    include '../../includes/app.php';
    use App\Vendedor;
    estaAutenticado();

    // Validar que sea un Id valido 
    $id = $_GET['id'];
    $id = filter_var($id, FILTER_VALIDATE_INT);

    if (!$id) {
        header('Location: /admin');
    }
    // obtener el vendedor
    $vendedor = Vendedor::find($id);


    $errores = Vendedor::getErrores();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        //asignar los valores
        $args = $_POST['vendedor'];

        $vendedor->sincronizar($args);

        $errores = $vendedor->validar();

        if (empty($errores)) {
            $vendedor->guardar();
        }

    }

    incluirTemplate('header');
?>

<main class="contenedor seccion contenido-centrado">
    <h1>Actualizar Vendedor</h1>
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST">
        <?php include '../../includes/templates/formulario_vendedores.php' ;?>
        <input type="submit" value="Guardar cambios" class="boton boton-verde">

    </form>

</main>


<?php

incluirTemplate('footer');