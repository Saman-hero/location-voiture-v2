<?php
require 'dbconnection.php';

$prenom = $_POST['prenom'];
$nom    = $_POST['nom'];
$email  = $_POST['email'];
$pass   = $_POST['pass'];
$pass2  = $_POST['pass2'];

if ($pass !== $pass2) {
    header('location:authForm.php?tab=register&auth=passmatch');
    exit;
}

$passHash = md5($pass);

$check = mysqli_query($connection, "select id from users where email = '$email'");
if (mysqli_num_rows($check) > 0) {
    header('location:authForm.php?tab=register&auth=exists');
    exit;
}

$query = "insert into users (nom, prenom, email, pass, role) values ('$nom', '$prenom', '$email', '$passHash', 'CL')";
mysqli_query($connection, $query);
mysqli_close($connection);

header('location:authForm.php?tab=login&msg=registered');
?>
