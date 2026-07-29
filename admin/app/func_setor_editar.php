<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_setor'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	



	$sql = sql ("
	UPDATE `setor` SET
	`nome_setor` = '$nome_setor',
	`unidade_setor` = '$unidade_setor',
	`lider_setor` = '$lider_setor'
	WHERE `id_setor` = '$id_setor' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../setor-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../setor-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../setor-editar.php");
}
ob_end_flush();
?>
