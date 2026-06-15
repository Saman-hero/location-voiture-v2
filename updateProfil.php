<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'trace.php';

$email  = mysqli_real_escape_string($connection, $_SESSION['user']);
$prenom = mysqli_real_escape_string($connection, $_POST['prenom']);
$nom    = mysqli_real_escape_string($connection, $_POST['nom']);

// Mise à jour du mot de passe si demandé
if (!empty($_POST['pass_actuel']) && !empty($_POST['pass_new'])) {
    $passActuel = md5($_POST['pass_actuel']);
    $passNew    = $_POST['pass_new'];
    $passNew2   = $_POST['pass_new2'];

    $check = mysqli_fetch_array(mysqli_query($connection,
        "select id from users where email='$email' and pass='$passActuel'"));

    if (!$check) {
        header('location:profil.php?msg=badpass');
        exit;
    }

    if ($passNew !== $passNew2) {
        header('location:profil.php?msg=badpass');
        exit;
    }

    $passHash = md5($passNew);
    $query = "update users set nom='$nom', prenom='$prenom', pass='$passHash' where email='$email'";
} else {
    $query = "update users set nom='$nom', prenom='$prenom' where email='$email'";
}

mysqli_query($connection, $query);
trace($query);

// Mettre à jour la session
$_SESSION['nom']    = $nom;
$_SESSION['prenom'] = $prenom;

mysqli_close($connection);
header('location:profil.php?msg=ok');
?>
