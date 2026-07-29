<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_lidersetor'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	

		// checkbox
		$check = "";
		$num = sizeof ($_POST['setores_lidersetor']);
		for ($z = 0; $z < $num; $z++) {
		  $check.= $_POST['setores_lidersetor'][$z] . ", ";
		}
		$setores_lidersetor = $check;
		if (!empty($setores_lidersetor)){  $setores_lidersetor = substr($setores_lidersetor, 0, -2); }
		


	$sql = sql ("
	UPDATE `lidersetor` SET
	`nome_lidersetor` = '$nome_lidersetor',`setores_lidersetor` = '$setores_lidersetor'
	WHERE `id_lidersetor` = '$id_lidersetor' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../lidersetor-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../lidersetor-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../lidersetor-editar.php");
}
ob_end_flush();
?>
