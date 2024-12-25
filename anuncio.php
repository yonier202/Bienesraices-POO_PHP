<?php
    $id=$_GET["id"];
    $id=filter_var($id, FILTER_VALIDATE_INT);

    if(!$id){
        header("Location: index.php");
        exit;
    }

    require './includes/config/database.php';
    $db = conectarDB();

    $consulta = "SELECT * FROM propiedades WHERE id=$id";
    $resultados = mysqli_query($db, $consulta);

    //validar que si exista una propiedad con ese id
    if(mysqli_num_rows($resultados) == 0){
        header("Location: index.php");
        exit;
    }

    $propiedad = mysqli_fetch_assoc($resultados);



    // include './includes/templates/header.php'
    include './includes/funciones.php';
    incluirTemplate('header');

?>

    <main class="contenedor seccion contenido-centrado">
        <h1><?php echo $propiedad['titulo'] ?></h1>
            <img loading="lazy" src="./imagenes/<?php echo $propiedad['imagen'] ?>" alt="imagen de la propiedad">

        <div class="resumen-propiedad">
            <p class="precio"><?php echo $propiedad['precio'] ?></p>
            <ul class="iconos-caracteristicas">
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_wc.svg" alt="icono wc">
                    <p><?php echo $propiedad['wc'] ?></p>
                </li>
                <li>
                    <img class="icono" loading="lazy" src="build/img/icono_estacionamiento.svg" alt="icono estacionamiento">
                    <p><?php echo $propiedad['estacionamiento'] ?></p>
                </li>
                <li>
                    <img class="icono"  loading="lazy" src="build/img/icono_dormitorio.svg" alt="icono habitaciones">
                    <p><?php echo $propiedad['habitaciones'] ?></p>
                </li>
            </ul>

            <p><?php echo $propiedad['descripcion'] ?></p>

        </div>
    </main>

<?php
    mysqli_close($db);
    incluirTemplate('footer');
?>