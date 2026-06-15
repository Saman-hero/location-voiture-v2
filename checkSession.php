<?php
session_start();
require 'config.php';

if (!isset($_SESSION['user']))
    header('location:authForm.php?auth=nonAuth');

if (time() - $_SESSION['LAT'] > $ttl)
    header('location:disconnect.php');
else
    $_SESSION['LAT'] = time();
?>
