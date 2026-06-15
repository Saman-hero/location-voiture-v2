<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');

require 'config.php';
require 'dbconnection.php';
require 'trace.php';

$id = (int)$_POST['id'];

// Garder l'ancienne photo par défaut
$currentResult = mysqli_query($connection, "select path from voitures where id = $id");
$current = mysqli_fetch_array($currentResult);
$path = $current['path'];

if (!empty($_FILES['photo']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['photo']['type'], $allowedTypes))
        header('location:editVoiture.php?num=' . $id . '&error=typephoto');

    if ($_FILES['photo']['size'] > $maxsizefile)
        header('location:editVoiture.php?num=' . $id . '&error=sizefile');

    $ext  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $path = 'photos/' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/' . $path);
}

$marque       = mysqli_real_escape_string($connection, $_POST['marque']);
$modele       = mysqli_real_escape_string($connection, $_POST['modele']);
$annee        = (int)$_POST['annee'];
$prix_jour    = (float)$_POST['prix_jour'];
$carburant    = $_POST['carburant'];
$transmission = $_POST['transmission'];
$places       = (int)$_POST['places'];
$description  = mysqli_real_escape_string($connection, $_POST['description']);
$categoryID   = (int)$_POST['category'];
$disponible   = (int)$_POST['disponible'];

$query = "update voitures set
    marque='$marque', modele='$modele', annee=$annee, prix_jour=$prix_jour,
    carburant='$carburant', transmission='$transmission', places=$places,
    description='$description', disponible=$disponible, path='$path', idCat=$categoryID
    where id=$id";

mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allVoitures.php');
?>
