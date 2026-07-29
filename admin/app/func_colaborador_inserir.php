<?php
ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}



	   $sql = sql ("SELECT * FROM colaborador WHERE usuario_colaborador = '$usuario_colaborador' LIMIT 1");
		if (mysqli_num_rows($sql) > 0){
			volta ("erro", "O nome do usuario já está em uso. Escolha outro!", "../colaborador-inserir.php");
		} 
	
	$sqlSetor = sql("SELECT * FROM setor WHERE id_setor = ".$setor_colaborador);
	if (mysqli_num_rows($sqlSetor)){
		$set = mysqli_fetch_array($sqlSetor);
		$unidade_colaborador = $set['unidade_setor'];
		
	}



	$sql = sql ("
	INSERT INTO `colaborador` (`nome_colaborador`,`email_colaborador`,`usuario_colaborador`,`senha_colaborador`,`unidade_colaborador`,`setor_colaborador`,`lider_colaborador`,`gestor_colaborador`, `alterarsenha_colaborador`)
	VALUES ('$nome_colaborador','$email_colaborador','$usuario_colaborador','$senha_colaborador','$unidade_colaborador','$setor_colaborador','$lider_colaborador','$gestor_colaborador', 1)
	");
	

if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../colaborador-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../colaborador-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../colaborador-inserir.php");
}
ob_end_flush();

?>
