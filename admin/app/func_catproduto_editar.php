<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_catproduto'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	



	if (empty($numero_catproduto) or $numero_catproduto < 1){
		$numero_catproduto = 99999999;
	}
	
	if (!empty($numero_catproduto) && $numero_catproduto != 99999999){
		$sql = sql ("UPDATE `catproduto` SET numero_catproduto = (numero_catproduto + 1) WHERE numero_catproduto >= '$numero_catproduto'");
	}
	

	$sql = sql ("
	UPDATE `catproduto` SET
	`nome_catproduto` = '$nome_catproduto',`numero_catproduto` = '$numero_catproduto'
	WHERE `id_catproduto` = '$id_catproduto' LIMIT 1
	");
	
if ($sql){
		
	AtualizaOrdem("catproduto", "id_catproduto", "numero_catproduto");
	
		volta ("ok", "Registro alterado com sucesso!", "../catproduto-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../catproduto-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../catproduto-editar.php");
}
ob_end_flush();
?>
