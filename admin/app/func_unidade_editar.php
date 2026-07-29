<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_unidade'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	



	$sql = sql ("
	UPDATE `unidade` SET
	`nome_unidade` = '$nome_unidade'
	WHERE `id_unidade` = '$id_unidade' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../unidade-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../unidade-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../unidade-editar.php");
}
ob_end_flush();
?>
