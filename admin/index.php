<?php
include '../includes/app.php';
estaAutenticado();

use App\Propiedad;

//Implemenetar metodo para obteren todas las propiedades
$propiedades = Propiedad::all();

// Validar la URL 
$mensaje = $_GET['mensaje'] ?? null;

// Importar el Template
incluirTemplate('header');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    

    // Sanitizar número entero
    $id = $_POST['id_eliminar'];
    $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);

    if ($id) {
        // Consultar la base de datos
        $propiedad = Propiedad::find($id);
        $propiedad->eliminar();
    }

    // Eliminar... 

    $query = "DELETE FROM propiedades WHERE id = '$id'";
    $resultado = mysqli_query($db, $query) or die(mysqli_error($db));
    // var_dump($resultado);
    // printf("Nuevo registro con el id %d.\n", mysqli_insert_id($db));

    if ($resultado) {
        header('location: /admin');
    }

}
?>

<h1 class="fw-300 centrar-texto">Administración</h1>

<main class="contenedor seccion contenido-centrado">


    <?php
        if ($mensaje == 1) {
            echo '<p class="alerta exito">Anuncio Creado Correctamente</p>';
        } else if ($mensaje == 2) {
        echo '<p class="alerta exito">Anuncio Actualizado Correctamente</p>';
        }
    ?>

    <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>


    <table class="propiedades">
        <thead>
            <tr>
                <th>ID</th>
                <th>Titulo</th>
                <th>Imagen</th>
                <th>Precio</th>
                <th>Acciones</th>
            </tr>
        </thead>

        <tbody>
            <?php foreach($propiedades as $propiedad): ?>
            <tr>
                <td><?php echo $propiedad->id; ?></td>
                <td><?php echo $propiedad->titulo; ?></td>
                <td>
                    <img src="/imagenes/<?php echo $propiedad->imagen; ?>"" width="100" class="imagen-tabla">
                </td>
                <td>$ <?php echo $propiedad->precio; ?></td>
                <td>
                <form method="POST">
                    <input type="hidden" name="id_eliminar" value="<?php echo $propiedad->id; ?>">
                    <input type="submit" href="/admin/propiedades/borrar.php" class="boton boton-rojo" value="Borrar">
                </form>
                    
                    <a href="/admin/propiedades/actualizar.php?id=<?php echo $propiedad->id; ?>" class="boton boton-verde">Actualizar</a>
                </td>
            </tr>

            <?php endforeach; ?>
        </tbody>
    </table>
</main>

<?php 
    incluirTemplate('footer');
?>