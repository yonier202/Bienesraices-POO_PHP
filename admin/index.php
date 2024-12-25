<?php

    include '../includes/funciones.php';
    $auth = estadoAutenticado();

    if (!$auth) {
        header('Location: /');
    }

    //importar conexion
    include '../includes/config/database.php';
    $db = conectarDB();
    //escribir el Query
    $query = "SELECT * FROM propiedades";

    //consultar la bd
    $resultadoConsulta = mysqli_query($db, $query);


    // include './includes/templates/header.php'
    $resultado = $_GET['resultado'] ?? null; //mostrar mensaje adicional

    //incluye el header
    incluirTemplate('header');

    //AL DAR CLICK EN ELIMINAR
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $idPropiedad = $_POST['id'];
        $idPropiedad = filter_var($idPropiedad, FILTER_VALIDATE_INT);

        if ($idPropiedad) {
            //eliminar archivo
            $query="SELECT imagen FROM propiedades WHERE id = $idPropiedad";
            $resultado_imagen = mysqli_query($db, $query);

            $imagen = mysqli_fetch_assoc($resultado_imagen);

            //borrar imagen del directorio
            if ($imagen['imagen']!= '') {
                unlink('../imagenes/'. $imagen['imagen']);
            }

            //eliminar la propiedad
            $query = "DELETE FROM propiedades WHERE id=$idPropiedad";
            $resultado = mysqli_query($db, $query);
            
            if ($resultado) {
                header('Location: /admin?resultado=3');
            }
        }
    }

?>

    <main class="contenedor seccion">
        <h1>Administrador de Bienes Raices</h1>
        <?php if ($resultado === "1") {?>
            <div class="alerta exito">
                <p>Anuncio creado correctamente</p>
            </div>
        <?php }elseif(($resultado === "2")) {?>
            <div class="alerta exito">
                <p>Anuncio Actualizado correctamente</p>
            </div>
        <?php }elseif(($resultado === "2")) {?>
            <div class="alerta exito">
                <p>Anuncio Eliminado correctamente</p>
            </div>
        <?php } ?>

        <a href="/admin/propiedades/crear.php" class="boton boton-verde">Nueva Propiedad</a>
        
        <table class="propiedades">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Título</th>
                    <th>Imagen</th>
                    <th>precio</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php while($propiedad = mysqli_fetch_assoc($resultadoConsulta)) { ?>
                <tr>
                    <td><?php echo $propiedad['id']; ?></td>
                    <td><?php echo $propiedad['titulo']; ?></td>
                    <td><img src="/imagenes/<?php echo $propiedad['imagen']; ?>" class="imagen-tabla"></td>
                    <td>$<?php echo $propiedad['precio']; ?></td>
                    <td>
                        <form method="post" class="w-100 ">
                            <input type="hidden" name="id" value="<?php echo $propiedad['id'];?>">
                            <input type="submit" class="boton-rojo-block" value="eliminar">
                        </form>
                        
                        <a href="./propiedades/actualizar.php?id=<?php echo $propiedad['id']; ?>" class="boton-verde-block">Actualizar</a>
                    </td>
                </tr>
                <?php }?>
            </tbody>
        </table>
    </main>

<?php
    //cerrar la conexion
    mysqli_close($db);
    // include './includes/templates/footer.php'  //mostrar footer en el final del documento  //se puede dejar en el footer para que se muestre en todas las páginas que incluyan este archivo  //este archivo está en la carpeta includes, dentro de templates  //se incluye en el index.php y contacto.php, pero no en anuncios.php y crear_propiedad.php  //se incluye en el footer para que se muestre en todas las páginas que incluyan este archivo  //se puede dejar en el footer para que se muestre en todas las páginas que incluyan este archivo  //este archivo está en la carpeta includes, dentro de templates  //se incluye en el index.php y contacto.php, pero no en anuncios.php y crear_
    incluirTemplate('footer');
?>