<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'trace.php';

$id = (int)$_GET['id'];

// Vérifier si des voitures utilisent cette catégorie
$check = mysqli_fetch_array(mysqli_query($connection, "select count(*) as n from voitures where idCat=$id"));
if ($check['n'] > 0) {
    header('location:allCategories.php?msg=err');
    exit;
}

$query = "delete from categories where id=$id";
mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allCategories.php?msg=ok');
?>
