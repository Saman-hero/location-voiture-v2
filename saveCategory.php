<?php
require 'checkSession.php';
if ($_SESSION['role'] != 'AD') header('location:authForm.php?auth=role');
require 'dbconnection.php';
require 'trace.php';

$name = mysqli_real_escape_string($connection, $_POST['name']);

if (isset($_POST['id']) && (int)$_POST['id'] > 0) {
    $id    = (int)$_POST['id'];
    $query = "update categories set name='$name' where id=$id";
} else {
    $query = "insert into categories (name) values ('$name')";
}

mysqli_query($connection, $query);
trace($query);
mysqli_close($connection);
header('location:allCategories.php?msg=ok');
?>
