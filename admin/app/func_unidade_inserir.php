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
	INSERT INTO `unidade` (`nome_unidade`)
	VALUES ('$nome_unidade')
	");
	

if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../unidade-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../unidade-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../unidade-inserir.php");
}
ob_end_flush();

?>
