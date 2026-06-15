<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'trace.php';

$num = (int)$_GET['num'];
$query = "delete from voitures where id = $num";
mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allVoitures.php');
?>
