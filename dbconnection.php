<?php
define('HOST', 'localhost');
define('USER', 'root');
define('PASS', '');
define('PORT', 3306);
define('DB', 'locationvoiture2');

$connection = mysqli_connect(HOST, USER, PASS, DB);
if ($connection == false) { echo "Erreur de connexion à la base de données"; exit(1); }
?>
