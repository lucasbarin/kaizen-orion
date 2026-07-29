<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_album']) && is_numeric($_GET['id_album'])){
	
	$id_album = $_GET['id_album'];

	
	$sql_album = sql ("SELECT * FROM album WHERE id_album = '$id_album'");
	$dados_album = mysqli_fetch_array($sql_album);
	
	$sql = sql ("DELETE FROM album WHERE id_album = '$id_album' LIMIT 1");
	
	if ($sql){
		// @unlink ("../../imgs/".$dados_album['imagem_album']);
		// @unlink ("../../imgs/".$dados_album['pdf_album']);
		volta ("ok", "Registro excluído com sucesso!", "../album-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../album-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_album'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_album) {
						
				$sql_album = sql ("SELECT * FROM album WHERE id_album = '$id_album'");
				$dados_album = mysqli_fetch_array($sql_album);
				
				$sql = sql ("DELETE FROM album WHERE id_album = '$id_album' LIMIT 1");
				
				if ($sql){
					$c++;
					// @unlink ("../../imgs/".$dados_album['imagem_album']);
					// @unlink ("../../imgs/".$dados_album['pdf_album']);
				}
						
		}
		
		if ($c > 0){
			if ($c > 1){
				$count = $c." Registros excluídos com sucesso!";
			} else {
				$count = "1 Registro excluído  com sucesso";
			}
		} else {
			$count = "0 Registros excluídos.";
		}
		
		if ($sql){
			volta ("ok", $count, "../album-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../album-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../album-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../album-admin.php");
}

?>