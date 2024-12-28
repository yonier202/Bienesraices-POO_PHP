<?php

include '../../includes/app.php';
use App\Vendedor;
use App\Propiedad;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

// Proteger esta ruta.
estaAutenticado();

// Verificar el id
$id =  $_GET['id'];
$id = filter_var($id, FILTER_VALIDATE_INT);
if(!$id) {
    header('Location: /admin');
}

// Obtener la propiedad
$propiedad = Propiedad::find($id);
$vendedores = Vendedor::all();


$errores = Propiedad::getErrores();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    
    $args = [];
    $args = $_POST['propiedad'];

    $propiedad->sincronizar($args);

    $errores = $propiedad->validar();

    $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

    if ($_FILES['propiedad']['tmp_name']['imagen']) {
        $manager = new ImageManager(Driver::class);
        $imagen = $manager->read($_FILES['propiedad']['tmp_name']['imagen'])->cover(800, 600);
        $propiedad->setImagen($nombreImagen);
    }

    if (empty($errores)) {
        if ($_FILES['propiedad']['tmp_name']['imagen']) {
            //almacenar la imagen
            $imagen->save(CARPETA_IMAGENES . $nombreImagen);
        }
        $propiedad->guardar();
    }
}

?>

<?php
$nombrePagina = 'Crear Propiedad';
incluirTemplate('header');
?>

<h1 class="fw-300 centrar-texto">Administración - Editar Propiedad</h1>

<main class="contenedor seccion contenido-centrado">
    <a href="/admin" class="boton boton-verde">Volver</a>

    <?php foreach ($errores as $error) : ?>
        <div class="alerta error">
            <?php echo $error; ?>
        </div>
    <?php endforeach; ?>

    <form class="formulario" method="POST" enctype="multipart/form-data">
        <?php include '../../includes/templates/formulario_propiedades.php' ;?>
        <input type="submit" value="Actualizar Propiedad" class="boton boton-verde">

    </form>

</main>


<?php


incluirTemplate('footer');

mysqli_close($db); ?>

</html>