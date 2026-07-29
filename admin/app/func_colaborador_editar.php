<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_colaborador'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	


	   $sql = sql ("SELECT * FROM colaborador WHERE usuario_colaborador = '$usuario_colaborador' AND id_colaborador <> '$id_colaborador' LIMIT 1");
		if (mysqli_num_rows($sql) > 0){
			volta ("erro", "O nome do usuario já está em uso por outro usuário. Escolha outro!", "../colaborador-inserir.php");
		} 
	
	


	if ($setor_colaborador){
		$sqlSetor = sql("SELECT * FROM setor WHERE id_setor = ".$setor_colaborador);
	if (mysqli_num_rows($sqlSetor)){
		$set = mysqli_fetch_array($sqlSetor);
		$unidade_colaborador = $set['unidade_setor'];
		
	}
		}

	$sql = sql ("SELECT * FROM colaborador WHERE `id_colaborador` = '$id_colaborador' LIMIT 1");
	$dados = mysqli_fetch_array($sql);
	if ($senha_colaborador <> $dados['senha_colaborador']){
		$add = ", `alterarsenha_colaborador` = '1'";
	} else {
		$add = "";
	}



	$sql = sql ("
	UPDATE `colaborador` SET
	`nome_colaborador` = '$nome_colaborador',`email_colaborador` = '$email_colaborador',`usuario_colaborador` = '$usuario_colaborador',`senha_colaborador` = '$senha_colaborador',`unidade_colaborador` = '$unidade_colaborador',`setor_colaborador` = '$setor_colaborador',`lider_colaborador` = '$lider_colaborador',`gestor_colaborador` = '$gestor_colaborador' $add
	WHERE `id_colaborador` = '$id_colaborador' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../colaborador-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../colaborador-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../colaborador-editar.php");
}
ob_end_flush();
?>
