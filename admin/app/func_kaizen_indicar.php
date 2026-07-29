<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_kaizen'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	



	$sql = sql ("
	UPDATE `kaizen` SET
	`tipo_kaizen` = '$tipo_kaizen',
	`colaborador_kaizen` = '$colaborador_kaizen',
	`colaborador1_kaizen` = '$colaborador1_kaizen',
	`colaborador2_kaizen` = '$colaborador2_kaizen'
	WHERE `id_kaizen` = '$id_kaizen' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../kaizen-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../kaizen-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../kaizen-indicar.php");
}
ob_end_flush();
?>
