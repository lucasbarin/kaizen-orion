<?php

@ob_start();

@session_start ();
/*echo "<pre>";
var_dump($_SESSION); die;*/


if (!isset($_SESSION['log']) || $_SESSION['log'] != 'fgnx45#Hdf2_fg3' || !isset($_SESSION['usu']) || !is_numeric($_SESSION['usu']) ){

	$msg = "Faça o login para continuar!";

	volta ("alerta", $msg, "index.php");

	exit();

}

$sqlusu = sql("SELECT * FROM colaborador
LEFT JOIN setor ON setor.lider_setor = colaborador.id_colaborador
WHERE id_colaborador = ".trata($_SESSION['usu'])." LIMIT 1", $con);
if (mysqli_num_rows($sqlusu)){
	$usuario = mysqli_fetch_array($sqlusu);
}

if (isset($usuario['alterarsenha_colaborador']) && $usuario['alterarsenha_colaborador'] == 1 && (!isset($pgAltera) || $pgAltera != 1)){
	header("Location: altera-senha.php"); die;
}

@ob_end_flush();

?>