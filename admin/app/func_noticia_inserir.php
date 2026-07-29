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
	$imagem1 = $_FILES['imagem1_noticia'];
	
	// data e floats
	$data_noticia = data_sql ($_POST['data_noticia']);

	// textos completos
	$texto1_noticia = limpa_html2($_POST['texto1_noticia']);
	
	 
	 
	// upload de imagem1
	if (!empty ($imagem1['type'])){
		if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
		
			$imagem1_noticia = upload_imagem($imagem1, 800, "../../imgs/");
	
		} else {
			volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../noticia-inserir.php");
		}
	} else {
		$imagem1_noticia = "";
	}
	

	$sql = sql ("
	INSERT INTO `noticia` (`nome_noticia`, `site_noticia`, `legenda_noticia`, `imagem1_noticia`, `data_noticia`, `texto1_noticia`)
	VALUES ('$nome_noticia', '$site_noticia', 'legenda_noticia', '$imagem1_noticia', '$data_noticia', '$texto1_noticia')
	");
	
	if ($sql){
		volta ("ok", "Novo registro inserido com sucesso!", "../noticia-admin.php");
	} else {
		volta ("erro", "Erro ao inserir novo registro! Tente novamente mais tarde!", "../noticia-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../noticia-inserir.php");
}
ob_end_flush();
?>