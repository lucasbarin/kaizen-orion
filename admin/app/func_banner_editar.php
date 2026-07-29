<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_banner'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_banner'];
	
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem1['type'])){
			if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
			
				$imagem1_banner = upload_imagem($imagem1, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../banner-editar.php");
			}
		} else {
			$imagem1_banner = "";
		}
	} else {
		$imagem1_banner = $_POST['imagem1_antigo'];
	}
	

	$sql = sql ("
	UPDATE `banner` SET
	`site_banner` = '$site_banner',
	`numero_banner` = '$numero_banner',
	`imagem1_banner` = '$imagem1_banner'
	WHERE `id_banner` = '$id_banner' LIMIT 1
	");
	
	if ($sql){
		volta ("ok", "Registro alterado com sucesso!", "../banner-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../banner-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../banner-editar.php");
}
ob_end_flush();
?>