<?php
$login = $_POST['login'];
$pass  = md5($_POST['pass']);

require 'dbconnection.php';
require 'config.php';

$query  = "select count(*) as number from users where email = '$login' and pass = '$pass'";
$result = mysqli_query($connection, $query);
$data   = mysqli_fetch_array($result);

if ($data['number'] == 1) {
    $queryUser = "select role, nom, prenom from users where email = '$login'";
    $resultUser = mysqli_query($connection, $queryUser);
    $user = mysqli_fetch_array($resultUser);

    session_start();
    $_SESSION['user']        = $login;
    $_SESSION['role']        = $user['role'];
    $_SESSION['nom']         = $user['nom'];
    $_SESSION['prenom']      = $user['prenom'];
    $_SESSION['LAT']         = time();
    $_SESSION['reservation'] = [];

    if ($user['role'] == 'AD')
        header('location:dashboard.php');
    else
        header('location:allVoitures.php');
} else {
    header('location:authForm.php?auth=false');
}
?>
