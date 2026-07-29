<?php
ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}

$imagem1 = $_FILES['imagem1_tabela'];
			
				if (!empty ($imagem1['type'])){
					if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){

						$imagem1_tabela = upload_imagem($imagem1, 2500, "../../imgs/");

					} else {
						volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../imagem1-inserir.php");
					}
				} else {
					$imagem1_tabela = "";
				}
			
			$pdf1 = $_FILES['pdf1_tabela'];
			
			
				if (!empty ($pdf1['type'])){
					
					$pdf1_tabela = upload_arquivo($pdf1, "../../imgs/");
	
				} else {
					$pdf1_tabela = "";
				}
			
			$texto1_tabela = limpa_html($_POST['texto1_tabela']);
		


	$sql = sql ("
	INSERT INTO `tabela` (`nome_tabela`,`email_tabela`,`imagem1_tabela`,`pdf1_tabela`,`texto1_tabela`,`opcao_tabela`)
	VALUES ('$nome_tabela','$email_tabela','$imagem1_tabela','$pdf1_tabela','$texto1_tabela','$opcao_tabela')
	");
	

if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../tabela-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../tabela-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../tabela-inserir.php");
}
ob_end_flush();

?>
