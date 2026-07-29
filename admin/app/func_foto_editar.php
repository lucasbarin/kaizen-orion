<?php
ob_start();
session_start();

ini_set('memory_limit', '2048M');

include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_foto'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	// imagens e arquivos
	$imagem1 = $_FILES['imagem1_foto'];
	
	// textos completos
	$texto1_foto = limpa_html($_POST['texto1_foto']);
	
	
	// upload de imagem1
	if ($_POST['altera1_img'] == 2){
		if (!empty ($imagem1['type'])){
			if ($imagem1['type'] == "image/jpeg" or $imagem1['type'] == "image/jpg" or $imagem1['type'] == "image/pjpeg" or $imagem1['type'] == "image/gif" or $imagem1['type'] == "image/png"){
			
				$imagem1_foto = upload_imagem($imagem1, 800, "../../imgs/");
		
			} else {
				volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../foto-editar.php");
			}
		} else {
			$imagem1_foto = "";
		}
	} else {
		$imagem1_foto = $_POST['imagem1_antigo'];
	}
	
	$sql = sql ("UPDATE foto SET numero_foto = (numero_foto + 1) WHERE numero_foto >= '$numero_foto' AND id_album = '$id_album'");
	
	$sql = sql ("
	UPDATE `foto` SET
	`nome_foto` = '$nome_foto',
	`numero_foto` = '$numero_foto',
	`imagem1_foto` = '$imagem1_foto',
	`texto1_foto` = '$texto1_foto',
	`id_album` = '$id_album'
	WHERE `id_foto` = '$id_foto' LIMIT 1
	");
	
	if ($sql){
		$sql = sql ("SELECT * FROM foto WHERE id_album = ".$id_album." ORDER BY numero_foto ASC");
		$c = 1;
		while ($dados = mysqli_fetch_assoc($sql)){
			sql ("UPDATE foto SET numero_foto = $c WHERE id_foto = '".$dados['id_foto']."'");
			$c++;
		}
		volta ("ok", "Registro alterado com sucesso!", "../foto-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../foto-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../foto-editar.php");
}
ob_end_flush();
?>