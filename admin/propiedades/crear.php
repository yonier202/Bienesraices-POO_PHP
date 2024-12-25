<?php

    include '../../includes/funciones.php';
    $auth = estadoAutenticado();

    if (!$auth) {
        header('Location: /');
    }

    //base de datos
    require '../../includes/config/database.php';
    $db = conectarDB();

    //consulta para obtener los vendedores
    $consulta = "Select * from vendedores";
    $resultados = mysqli_query($db, $consulta);

    // var_dump($db);
    $errores = [];

    $titulo = "";
    $precio = "";
    $descripcion = "";
    $habitaciones = "";
    $wc = "";
    $estacionamiento = "";
    $vendedorId = "";
    $creado = date('Y-m-d H:i:s');
    $imagen="";

    //ejecutar el codigo despues de que el usuario envia el formulario
    if ($_REQUEST) {

        // $numero = "1Hola";
        // $numero = 1;

        // //sanitizar
        // $resultado = filter_var($numero, FILTER_SANITIZE_NUMBER_INT); //devuelver 1 (borra el resto)
        
        // //validar
        // $resultado = filter_var($resultado, FILTER_VALIDATE_INT); //si no pasa la validacion devuelve false
        echo "<pre>";
        var_dump($_REQUEST);
        echo "</pre>";

        //evitar inyeccion sql
        $titulo = mysqli_real_escape_string($db, $_REQUEST['titulo']);
        $precio = mysqli_real_escape_string($db, $_REQUEST['precio']);
        $descripcion = mysqli_real_escape_string($db, $_REQUEST['descripcion']);
        $habitaciones = mysqli_real_escape_string($db, $_REQUEST['habitaciones']);
        $wc = mysqli_real_escape_string($db, $_REQUEST['wc']);
        $estacionamiento = mysqli_real_escape_string($db, $_REQUEST['estacionamiento']);
        $vendedorId = mysqli_real_escape_string($db, $_REQUEST['vendedor']);

        //asignar file hacia una variable
        $imagen=$_FILES['imagen'];

        // var_dump($imagen);
        // var_dump($imagen['name']);

        if (!$titulo || !$precio || !$habitaciones || !$wc || !$estacionamiento || !$vendedorId) {
            $errores[]= "dedes añadir una opcion";
        }

        if (strlen($descripcion)<10) {
            $errores[]= "La descripción debe tener al menos 10 caracteres";
        }

        if (!$imagen['name'] || $imagen['error']) {
            $errores[]= "Debes seleccionar una imagen";
        }
        //validar por tamaño
        if ($imagen['size'] > 1000000) {
            $errores[]= "La imagen es demasiado grande. Máximo 1MB";
        }


        //si no tenemos errores
        if (empty($errores)) {

            //subir imagen
            $carpetaImagenes = '../../imagenes/';
            if (!is_dir($carpetaImagenes)) {
                mkdir($carpetaImagenes);
            }

            //generar nombre unico para la imagen
            $nombreImagen = md5(uniqid(rand(), true)) . ".jpg";

            //subir la imagen
            move_uploaded_file($imagen['tmp_name'], $carpetaImagenes . $nombreImagen);

            //insertarr en la base de datos
            $query = "INSERT INTO propiedades (titulo, precio, imagen, descripcion, habitaciones, wc, estacionamiento, creado, vendedores_id) VALUES (?,?,?,?,?,?,?,?,?)";
            $stmt = $db->prepare($query);
            $stmt->bind_param("sdssiiisi", $titulo, $precio, $nombreImagen, $descripcion, $habitaciones, $wc, $estacionamiento, $creado, $vendedorId);
            $stmt->execute();

            if ($stmt->affected_rows > 0) {
                header('Location: /admin?resultado=1');
            } else {
                echo "No se insertaron filas. Número de filas afectadas: " . $stmt->affected_rows;
            }

            $stmt->close();
            $db->close();
        } 
    }

    // include './includes/templates/header.php'
    incluirTemplate('header');

?>

    <main class="contenedor seccion">
        <h1>Crear</h1>

        <?php foreach ($errores as $error) { ?>
            <div class="alerta error">
                <p><?php echo $error;?></p>
            </div>
        <?php } ?>
        <a href="/admin/index.php" class="boton boton-verde">Volver</a>

        <form action="/admin/propiedades/crear.php" class="formulario" method="POST" enctype="multipart/form-data">
            <fieldset>
                <legend>Información General</legend>

                <label for="titulo">Titulo:</label>
                <input type="text" id="titulo" name="titulo" placeholder="titulo propiedad" value="<?php echo $titulo ?>">

                <label for="precio">Precio:</label>
                <input type="number" id="precio" name="precio" placeholder="Precio propiedad" value="<?php echo $precio ?>">

                <label for="imagen">Imagen:</label>
                <input type="file" id="imagen" name="imagen" accept="image/jpeg, image/png">

                <label for="descripcion">Descripcion:</label>
                <textarea name="descripcion" id="descripcion"><?php echo $descripcion ?></textarea>
            </fieldset>

            <fieldset>
                <legend>Información de la propiedad</legend>

                <label for="habitaciones">Habitaciones:</label>
                <input type="number" id="habitaciones"  name="habitaciones" placeholder="Ej. 2" min="1" max="9" value="<?php echo $habitaciones ?>">

                <label for="wc">Baños:</label>
                <input type="number" id="wc" name="wc" placeholder="Ej. 2" min="1" max="9" value="<?php echo $wc ?>">

                <label for="estacionamiento">Estacionamiento:</label>
                <input type="number" id="estacionamiento" name="estacionamiento" placeholder="Ej. 2" min="1" max="9" value="<?php echo $estacionamiento ?>">
            </fieldset>

            <fieldset>
                <legend>Vendedor</legend>

                <select name="vendedor" id="">
                    <?php while ($vendedor = mysqli_fetch_assoc($resultados)) {?>
                        <option <?php $vendedorId === $vendedor['id'] ? 'selected' : '' ?> value="<?php echo $vendedor['id']?>"><?php echo $vendedor['nombre']?></option>
                    <?php }?>

                    <!-- <option value="1">Juan</option> -->

                </select>
            </fieldset>

            <input type="submit" value="Crear propiedad" class="boton boton-verde">
        </form>
    </main>

<?php
    incluirTemplate('footer');
?>