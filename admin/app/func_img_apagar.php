<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_img']) && is_numeric($_GET['id_img'])){
	
	$id_img = $_GET['id_img'];

	
	$sql_img = sql ("SELECT * FROM img WHERE id_img = '$id_img'");
	$dados_img = mysqli_fetch_array($sql_img);
	
	$sql = sql ("DELETE FROM img WHERE id_img = '$id_img' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_img['imagem_img']);
		volta ("ok", "Imagem excluída com sucesso!", "../img-admin.php");
	} else {
		volta ("erro", "Erro ao excluir imagem! Tente novamente mais tarde!", "../img-admin.php");
	}

}  else {
	volta ("erro", "Selecione a imagem a ser excluída!", "../img-admin.php");
}

?>