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
	$imagem = $_FILES['imagem_img'];
	
	// upload de imagem
	if (!empty ($imagem['type'])){
		if ($imagem['type'] == "image/jpeg" or $imagem['type'] == "image/jpg" or $imagem['type'] == "image/pjpeg" or $imagem['type'] == "image/gif" or $imagem['type'] == "image/png"){
			if ($tamanho_img == 1){
				$t = 300;
			} elseif($tamanho_img == 2) {
				$t = 500;
			} else {
				$t = 800;
			}
			$img = upload_img($imagem, $t, "../../imgs/");
			$imagem_img = $img['nome'];
			$w = $img['w'];
			$h = $img['h'];
	
		} else {
			volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../img-admin.php");
		}
	} else {
		volta ("erro", "Selecione uma imagem no formato JPG, PNG ou GIF!", "../img-admin.php");
	}
		
	$sql = sql ("INSERT INTO img (imagem_img, w, h)
	VALUES ('$imagem_img','$w', '$h')");
	
	if ($sql){
		volta ("ok", "Imagem inserida com sucesso!", "../img-admin.php");
	} else {
		volta ("erro", "Erro ao inserir imagem! Tente novamente mais tarde!", "../img-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../img-admin.php");
}
ob_end_flush();
?>