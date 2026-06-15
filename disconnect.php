<?php
session_start();
session_destroy();
header('location:authForm.php?auth=again');
?>
