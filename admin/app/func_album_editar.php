<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_album'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_album'];
	
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem1['type'])){
			if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
			
				$imagem1_album = upload_imagem($imagem1, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../album-editar.php");
			}
		} else {
			$imagem1_album = "";
		}
	} else {
		$imagem1_album = $_POST['imagem1_antigo'];
	}


	$sql = sql ("
	UPDATE `album` SET
	`nome_album` = '$nome_album',
	`numero_album` = '$numero_album',
	`imagem1_album` = '$imagem1_album'
	WHERE `id_album` = '$id_album' LIMIT 1
	");
	
	if ($sql){
		volta ("ok", "Registro alterado com sucesso!", "../album-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../album-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../album-editar.php");
}
ob_end_flush();
?>