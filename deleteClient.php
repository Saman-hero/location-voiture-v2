<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'trace.php';

$id = (int)$_GET['id'];
$query = "delete from users where id=$id and role='CL'";
mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allClients.php');
?>
