<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	




	$sql = sql ("
	UPDATE `` SET
	
	WHERE `id_` = '$id_' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../-editar.php");
}
ob_end_flush();
?>
