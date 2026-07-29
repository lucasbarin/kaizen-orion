<?php
ob_start();
session_start ();
unset ($_SESSION['administrator']);
header ("Location: ../index.php");
exit();
ob_end_flush();
?>