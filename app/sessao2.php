<?php

@ob_start();

@session_start ();

if (!isset($_SESSION['log']) || $_SESSION['log'] != 'fgnx45#Hdf2_fg3' || !isset($_SESSION['usu']) || !is_numeric($_SESSION['usu']) ){

	$msg = "Faça o login para continuar!";

	volta ("alerta", $msg, "../index.php");

	exit();

}
$sqlusu = sql("SELECT * FROM colaborador WHERE id_colaborador = ".trata($_SESSION['usu'])." LIMIT 1", $con);
if (mysqli_num_rows($sqlusu)){
	$usuario = mysqli_fetch_array($sqlusu);
}

@ob_end_flush();

?>