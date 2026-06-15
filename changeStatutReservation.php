<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'trace.php';

$id     = (int)$_GET['id'];
$statut = $_GET['statut'];

$allowed = ['confirmee', 'annulee', 'terminee', 'en_attente'];
if (!in_array($statut, $allowed)) {
    header('location:allReservations.php');
    exit;
}

// Client ne peut qu'annuler ses propres réservations
if ($_SESSION['role'] != 'AD') {
    $check = mysqli_fetch_array(mysqli_query($connection, "select email_client from reservations where id=$id"));
    if (!$check || $check['email_client'] != $_SESSION['user'] || $statut != 'annulee') {
        header('location:allReservations.php');
        exit;
    }
}

$query = "update reservations set statut='$statut' where id=$id";
mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allReservations.php');
?>
