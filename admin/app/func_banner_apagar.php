<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_banner']) && is_numeric($_GET['id_banner'])){
	
	$id_banner = $_GET['id_banner'];

	
	$sql_banner = sql ("SELECT * FROM banner WHERE id_banner = '$id_banner'");
	$dados_banner = mysqli_fetch_array($sql_banner);
	
	$sql = sql ("DELETE FROM banner WHERE id_banner = '$id_banner' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_banner['imagem_banner']);
		@unlink ("../../imgs/".$dados_banner['pdf_banner']);
		volta ("ok", "Registro excluído com sucesso!", "../banner-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../banner-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_banner'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_banner) {
						
				$sql_banner = sql ("SELECT * FROM banner WHERE id_banner = '$id_banner'");
				$dados_banner = mysqli_fetch_array($sql_banner);
				
				$sql = sql ("DELETE FROM banner WHERE id_banner = '$id_banner' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_banner['imagem_banner']);
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
			volta ("ok", $count, "../banner-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../banner-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../banner-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../banner-admin.php");
}

?>