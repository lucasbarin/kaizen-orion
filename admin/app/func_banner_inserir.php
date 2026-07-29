<?php

ob_start();
session_start();

include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if($_POST){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_banner'];
	 
	 
	// upload de imagem1
	if (!empty ($imagem1['type'])){
		if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
		
			$imagem1_banner = upload_imagem($imagem1, 800, "../../imgs/");
	
		} else {
			volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../banner-inserir.php");
		}
	} else {
		$imagem1_banner = "";
	}
		
	$sql = sql ("
	INSERT INTO `banner` (`site_banner`, `numero_banner`, `imagem1_banner`)
	VALUES ('$site_banner', '$numero_banner', '$imagem1_banner')
	");
	
	if ($sql){
		volta ("ok", "Novo registro inserido com sucesso!", "../banner-admin.php");
	} else {
		volta ("erro", "Erro ao inserir novo registro! Tente novamente mais tarde!", "../banner-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../banner-inserir.php");
}
ob_end_flush();
?>