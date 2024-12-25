<?php
    // include './includes/templates/header.php'
    include './includes/funciones.php';
    incluirTemplate('header');

?>

    <main class="contenedor seccion">

        <h2>Casas y Depas en Venta</h2>
        <?php
            $limite=6;
            include './includes/templates/anuncios.php';
        ?>
    </main>

<?php
    incluirTemplate('footer');
?>