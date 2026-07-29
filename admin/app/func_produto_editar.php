<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_produto'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	
$imagem1 = $_FILES['imagem1_produto'];
			
			if ($_POST['altera_imagem1'] == 2){
				if (!empty ($imagem1['type'])){
					if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){

						$imagem1_produto = upload_imagem($imagem1, 1000, "../../imgs/");

					} else {
						volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../imagem1-editar.php");
					}
				} else {
					$imagem1_produto = "";
				}
			} else {
				$imagem1_produto = $_POST['imagem1_antigo'];
			}
			$texto1_produto = limpa_html($_POST['texto1_produto']);
		


	if (empty($numero_produto) or $numero_produto < 1){
		$numero_produto = 99999999;
	}
	
	if (!empty($numero_produto) && $numero_produto != 99999999){
		$sql = sql ("UPDATE `produto` SET numero_produto = (numero_produto + 1) WHERE numero_produto >= '$numero_produto' AND catproduto_produto = ".$catproduto_produto);
	}
	

	$sql = sql ("
	UPDATE `produto` SET
	`nome_produto` = '$nome_produto',`catproduto_produto` = '$catproduto_produto',`numero_produto` = '$numero_produto',`imagem1_produto` = '$imagem1_produto',`pontos_produto` = '$pontos_produto',`texto1_produto` = '$texto1_produto',`site_produto` = '$site_produto'
	WHERE `id_produto` = '$id_produto' LIMIT 1
	");
	
if ($sql){
		
	AtualizaOrdem("produto", "id_produto", "numero_produto", "catproduto_produto", $catproduto_produto);
	
		volta ("ok", "Registro alterado com sucesso!", "../produto-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../produto-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../produto-editar.php");
}
ob_end_flush();
?>
