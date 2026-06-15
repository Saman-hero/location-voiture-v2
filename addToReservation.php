<?php
require 'checkSession.php';
if ($_SESSION['role'] == 'AD') header('location:allVoitures.php');

$idVoiture  = (int)$_GET['idVoiture'];
$date_debut = $_GET['date_debut'];
$date_fin   = $_GET['date_fin'];

if ($date_debut >= $date_fin || $date_debut < date('Y-m-d')) {
    header('location:allVoitures.php?msg=dates');
    exit;
}

// Vérifie doublon dans la session
foreach ($_SESSION['reservation'] as $item)
    if ($item['idVoiture'] == $idVoiture) {
        header('location:allVoitures.php?msg=already');
        exit;
    }

$_SESSION['reservation'][] = [
    'idVoiture'  => $idVoiture,
    'date_debut' => $date_debut,
    'date_fin'   => $date_fin,
];

header('location:allVoitures.php?msg=added');
?>
