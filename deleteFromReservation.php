<?php
require 'checkSession.php';

$id = (int)$_GET['id'];
foreach ($_SESSION['reservation'] as $key => $item)
    if ($item['idVoiture'] == $id)
        unset($_SESSION['reservation'][$key]);

$_SESSION['reservation'] = array_values($_SESSION['reservation']);
header('location:showReservation.php');
?>
