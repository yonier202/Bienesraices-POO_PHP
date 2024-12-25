<?php
require 'includes/config/database.php';
$db=conectarDB();

//Crear un Email y password

$email='yonier202@gmail.com';
$password = password_hash('3125052551', PASSWORD_DEFAULT);

$query = "INSERT INTO usuarios (email,password) VALUES ('$email', '$password');";

mysqli_query($db,$query);