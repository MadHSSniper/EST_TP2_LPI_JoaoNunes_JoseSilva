<?php
ob_start();

session_start();
$_SESSION["numC"] = null;
unset($_SESSION["numC"]);
session_destroy();
echo("You have logged out");
header('Refresh: 0; ../index.php');
?>