<?php
ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}




	if (empty($numero_catproduto) or $numero_catproduto < 1){
		$numero_catproduto = 99999999;
	}
	
	if (!empty($numero_catproduto) && $numero_catproduto != 99999999){
		$sql = sql ("UPDATE `catproduto` SET numero_catproduto = (numero_catproduto + 1) WHERE numero_catproduto >= '$numero_catproduto'");
	}
	

	$sql = sql ("
	INSERT INTO `catproduto` (`nome_catproduto`,`numero_catproduto`)
	VALUES ('$nome_catproduto','$numero_catproduto')
	");
	

if ($sql){
		
	AtualizaOrdem("catproduto", "id_catproduto", "numero_catproduto");
	
		volta ("ok", "Registro alterado com sucesso!", "../catproduto-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../catproduto-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../catproduto-inserir.php");
}
ob_end_flush();

?>
