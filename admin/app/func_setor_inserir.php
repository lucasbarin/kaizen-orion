<?php
ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}




	$sql = sql ("
	INSERT INTO `setor` (`nome_setor`,`lider_setor`,`unidade_setor`)
	VALUES ('$nome_setor', '$lider_setor', '$unidade_setor')
	");
	

if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../setor-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../setor-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../setor-inserir.php");
}
ob_end_flush();

?>
