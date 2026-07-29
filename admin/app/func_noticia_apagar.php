<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_noticia']) && is_numeric($_GET['id_noticia'])){
	
	$id_noticia = $_GET['id_noticia'];

	
	$sql_noticia = sql ("SELECT * FROM noticia WHERE id_noticia = '$id_noticia'");
	$dados_noticia = mysqli_fetch_array($sql_noticia);
	
	$sql = sql ("DELETE FROM noticia WHERE id_noticia = '$id_noticia' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_noticia['imagem_noticia']);
		volta ("ok", "Registro excluído com sucesso!", "../noticia-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../noticia-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_noticia'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_noticia) {
						
				$sql_noticia = sql ("SELECT * FROM noticia WHERE id_noticia = '$id_noticia'");
				$dados_noticia = mysqli_fetch_array($sql_noticia);
				
				$sql = sql ("DELETE FROM noticia WHERE id_noticia = '$id_noticia' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_noticia['imagem_noticia']);
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
			volta ("ok", $count, "../noticia-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../noticia-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../noticia-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../noticia-admin.php");
}

?>