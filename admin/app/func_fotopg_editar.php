<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_foto'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem = $_FILES['imagem_foto'];
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem['type'])){
			if ($imagem['type'] == "image/jpeg" or $imagem['type'] == "image/jpg" or $imagem['type'] == "image/pjpeg" or $imagem['type'] == "image/gif" or $imagem['type'] == "image/png"){
			
				$imagem_foto = upload_imagem($imagem, 1000, "../../imgs/", $bg_foto);
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../pg-editar.php");
			}
		} else {
			$imagem_foto = "";
		}
	} else {
		$imagem_foto = $_POST['imagem_antigo'];
	}
	
	
	
	$sql = sql ("
	UPDATE `fotopg` SET
	`imagem_foto` = '$imagem_foto',
	`legenda_foto` = '$legenda_foto'
	WHERE `id_foto` = '$id_foto' LIMIT 1
	");
	
	if ($sql){
		volta ("ok", "Registro alterado com sucesso!", "../pg-editar.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../pg-editar.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../pg-editar.php");
}
ob_end_flush();
?>