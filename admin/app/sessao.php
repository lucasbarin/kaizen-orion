<?php
ob_start();
session_start ();
if (!isset($_SESSION['administrator']) || $_SESSION['administrator'] != "Qy2X5IVDEJ9lPlI3SMl1"){
	header ("Location: index.php");
	exit();
}
ob_end_flush();
?>