<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_noticia'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_noticia'];
	
	// data e floats
	$data_noticia = data_sql ($_POST['data_noticia']);
	
	$texto1_noticia = limpa_html2($_POST['texto1_noticia']);
	
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem1['type'])){
			if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
			
				$imagem1_noticia = upload_imagem($imagem1, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../noticia-editar.php");
			}
		} else {
			$imagem1_noticia = "";
		}
	} else {
		$imagem1_noticia = $_POST['imagem1_antigo'];
	}

	$sql = sql ("
	UPDATE `noticia` SET
	`nome_noticia` = '$nome_noticia',
	`site_noticia` = '$site_noticia',
	`legenda_noticia` = '$legenda_noticia',
	`imagem1_noticia` = '$imagem1_noticia',
	`data_noticia` = '$data_noticia',
	`texto1_noticia` = '$texto1_noticia'
	WHERE `id_noticia` = '$id_noticia' LIMIT 1
	");
	
	if ($sql){
		volta ("ok", "Registro alterado com sucesso!", "../noticia-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../noticia-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../noticia-editar.php");
}
ob_end_flush();
?>