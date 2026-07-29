<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_teste'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}

	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_teste'];
	$imagem2 = $_FILES['imagem2_teste'];
	$imagem3 = $_FILES['imagem3_teste'];
	$pdf1 = $_FILES['pdf1_teste'];
	$pdf2 = $_FILES['pdf2_teste'];
	$pdf3 = $_FILES['pdf3_teste'];
	
	if (empty($numero_teste) or $numero_teste < 1){
		$numero_teste = 99999999;
	}
	
	// data e floats
	$data_teste = data_sql ($_POST['data_teste']);
	$preco_teste = float_sql ($_POST['preco_teste']);
	
	// textos completos
	$texto1_teste = limpa_html($_POST['texto1_teste']);
	$texto2_teste = limpa_html2($_POST['texto2_teste']);
	
	// checkbox
	$check = "";
	$num = sizeof ($_POST['check_teste']);
	for ($i = 0; $i < $num; $i++) {
	  $check.= $_POST['check_teste'][$i] . ", ";
	}
	$check_teste = $check;
	if (!empty($check_teste)){  $check_teste = substr($check_teste, 0, -2); }
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem1['type'])){
			if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
			
				$imagem1_teste = upload_imagem($imagem1, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../teste-editar.php");
			}
		} else {
			$imagem1_teste = "";
		}
	} else {
		$imagem1_teste = $_POST['imagem1_antigo'];
	}
	
	
	// upload de imagem2
	if ($_POST['altera2_img'] == 2){
		if (!empty ($imagem2['type'])){
			if ($imagem2['type'] == "image/jpeg" or $imagem2['type'] == "image/jpg" or $imagem2['type'] == "image/pjpeg" or $imagem2['type'] == "image/gif" or $imagem2['type'] == "image/png"){
			
				$imagem2_teste = upload_imagem($imagem2, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../teste-editar.php");
			}
		} else {
			$imagem2_teste = "";
		}
	} else {
		$imagem2_teste = $_POST['imagem2_antigo'];
	}
	
	
	// upload de imagem3
	if ($_POST['altera3_img'] == 2){
		if (!empty ($imagem3['type'])){
			if ($imagem3['type'] == "image/jpeg" or $imagem3['type'] == "image/jpg" or $imagem3['type'] == "image/pjpeg" or $imagem3['type'] == "image/gif" or $imagem3['type'] == "image/png"){
			
				$imagem3_teste = upload_imagem($imagem3, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../teste-editar.php");
			}
		} else {
			$imagem3_teste = "";
		}
	} else {
		$imagem3_teste = $_POST['imagem3_antigo'];
	}
	
	
	// upload de pdf1
	if ($_POST['altera1_pdf'] == 2){
		if (!empty ($pdf1['type'])){
			if ($pdf1['type'] == "application/pdf"){
				
				$pdf1_teste = upload_arquivo($pdf1, "../../imgs/");
		
			} else {
				volta ("erro", "Somente arquivos PDF são aceitos!", "../teste-editar.php");
			}
		} else {
			$pdf1_teste = "";
		}
	} else {
		$pdf1_teste = $_POST['pdf1_antigo'];
	}
	
	// upload de pdf2
	if ($_POST['altera1_pdf'] == 2){
		if (!empty ($pdf2['type'])){
			if ($pdf2['type'] == "application/pdf"){
				
				$pdf2_teste = upload_arquivo($pdf2, "../../imgs/");
		
			} else {
				volta ("erro", "Somente arquivos PDF são aceitos!", "../teste-editar.php");
			}
		} else {
			$pdf2_teste = "";
		}
	} else {
		$pdf2_teste = $_POST['pdf2_antigo'];
	}
	
	
	// upload de pdf3
	if ($_POST['altera1_pdf'] == 2){
		if (!empty ($pdf3['type'])){
			if ($pdf3['type'] == "application/pdf"){
				
				$pdf3_teste = upload_arquivo($pdf3, "../../imgs/");
		
			} else {
				volta ("erro", "Somente arquivos PDF são aceitos!", "../teste-editar.php");
			}
		} else {
			$pdf3_teste = "";
		}
	} else {
		$pdf3_teste = $_POST['pdf3_antigo'];
	}
	
	if (!empty($numero_teste) && $numero_teste != 99999999){
		$sql = sql ("UPDATE `teste` SET numero_teste = (numero_teste + 1) WHERE numero_teste >= '$numero_teste'");
	}
	
	$sql = sql ("
	UPDATE `teste` SET
	`nome_teste` = '$nome_teste',
	`email_teste` = '$email_teste',
	`site_teste` = '$site_teste',
	`input_teste` = '$input_teste',
	`numero_teste` = '$numero_teste',
	`pdf1_teste` = '$pdf1_teste',
	`pdf2_teste` = '$pdf2_teste',
	`pdf3_teste` = '$pdf3_teste',
	`imagem1_teste` = '$imagem1_teste',
	`imagem2_teste` = '$imagem2_teste',
	`imagem3_teste` = '$imagem3_teste',
	`radio_teste` = '$radio_teste',
	`check_teste` = '$check_teste',
	`preco_teste` = '$preco_teste',
	`data_teste` = '$data_teste',
	`opcao_teste` = '$opcao_teste',
	`texto1_teste` = '$texto1_teste',
	`texto2_teste` = '$texto2_teste'
	WHERE `id_teste` = '$id_teste' LIMIT 1
	");
	
	if ($sql){
		AtualizaOrdem("teste", "id_teste", "numero_teste");
		volta ("ok", "Registro alterado com sucesso!", "../teste-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../teste-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../teste-editar.php");
}
ob_end_flush();
?>