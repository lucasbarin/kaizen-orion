<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_tabela'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	
$imagem1 = $_FILES['imagem1_tabela'];
			
			if ($_POST['altera_imagem1'] == 2){
				if (!empty ($imagem1['type'])){
					if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){

						$imagem1_tabela = upload_imagem($imagem1, 2500, "../../imgs/");

					} else {
						volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../imagem1-editar.php");
					}
				} else {
					$imagem1_tabela = "";
				}
			} else {
				$imagem1_tabela = $_POST['imagem1_antigo'];
			}
			$pdf1 = $_FILES['pdf1_tabela'];
			
			if ($_POST['altera_pdf1'] == 2){
				if (!empty ($pdf1['type'])){
					
					$pdf1_tabela = upload_arquivo($pdf1, "../../imgs/");
	
				} else {
					$pdf1_tabela = "";
				}
			} else {
				$pdf1_tabela = $_POST['pdf1_antigo'];
			}
			$texto1_tabela = limpa_html($_POST['texto1_tabela']);
		


	$sql = sql ("
	UPDATE `tabela` SET
	`nome_tabela` = '$nome_tabela',`email_tabela` = '$email_tabela',`imagem1_tabela` = '$imagem1_tabela',`pdf1_tabela` = '$pdf1_tabela',`texto1_tabela` = '$texto1_tabela',`opcao_tabela` = '$opcao_tabela'
	WHERE `id_tabela` = '$id_tabela' LIMIT 1
	");
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../tabela-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../tabela-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../tabela-editar.php");
}
ob_end_flush();
?>
