<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');

require 'config.php';
require 'trace.php';

$path = 'photos/nopicture.jpeg';

if (!empty($_FILES['photo']['name'])) {
    $allowedTypes = ['image/jpeg', 'image/jpg', 'image/png', 'image/gif'];
    if (!in_array($_FILES['photo']['type'], $allowedTypes))
        header('location:VoitureForm.php?error=typephoto');

    if ($_FILES['photo']['size'] > $maxsizefile)
        header('location:VoitureForm.php?error=sizefile');

    $ext  = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $path = 'photos/' . uniqid() . '.' . $ext;
    move_uploaded_file($_FILES['photo']['tmp_name'], __DIR__ . '/' . $path);
}

require 'dbconnection.php';

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

$query = "insert into voitures values (null, '$marque', '$modele', $annee, $prix_jour, '$carburant', '$transmission', $places, '$description', $disponible, '$path', $categoryID)";
mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allVoitures.php');
?>
