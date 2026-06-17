<?php
require 'checkSession.php';
require 'dbconnection.php';
require 'myfunctions.php';
require 'trace.php';

if (empty($_SESSION['reservation'])) {
    header('location:allVoitures.php');
    exit;
}

$date_creation = date('Y-m-d H:i:s');
$client        = $_SESSION['user'];

$query = "insert into reservations (date_creation, email_client, statut) values ('$date_creation', '$client', 'confirmee')";
mysqli_query($connection, $query);
trace($query);

$query  = "select max(id) from reservations";
$result = mysqli_query($connection, $query);
$data   = mysqli_fetch_array($result);
$idReservation = $data[0];

foreach ($_SESSION['reservation'] as $item) {
    $idVoiture  = (int)$item['idVoiture'];
    $date_debut = $item['date_debut'];
    $date_fin   = $item['date_fin'];
    $nb_jours   = calculerNbJours($date_debut, $date_fin);

    $query = "insert into details_reservation values ($idReservation, $idVoiture, '$date_debut', '$date_fin', $nb_jours)";
    mysqli_query($connection, $query);
    trace($query);

    $query = "update voitures set disponible = 0 where id = $idVoiture";
    mysqli_query($connection, $query);
    trace($query);
}

$_SESSION['reservation'] = [];
mysqli_close($connection);
header('location:allReservations.php?msg=confirmed');
?>
