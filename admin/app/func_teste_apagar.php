<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_teste']) && is_numeric($_GET['id_teste'])){
	
	$id_teste = $_GET['id_teste'];

	
	$sql_teste = sql ("SELECT * FROM teste WHERE id_teste = '$id_teste'");
	$dados_teste = mysqli_fetch_array($sql_teste);
	
	$sql = sql ("DELETE FROM teste WHERE id_teste = '$id_teste' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_teste['imagem_teste']);
		@unlink ("../../imgs/".$dados_teste['pdf_teste']);
		AtualizaOrdem("teste", "id_teste", "numero_teste");
		volta ("ok", "Registro excluído com sucesso!", "../teste-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../teste-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_teste'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_teste) {
						
				$sql_teste = sql ("SELECT * FROM teste WHERE id_teste = '$id_teste'");
				$dados_teste = mysqli_fetch_array($sql_teste);
				
				$sql = sql ("DELETE FROM teste WHERE id_teste = '$id_teste' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_teste['imagem_teste']);
					@unlink ("../../imgs/".$dados_teste['pdf_teste']);
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
			AtualizaOrdem("teste", "id_teste", "numero_teste");
			volta ("ok", $count, "../teste-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../teste-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../teste-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../teste-admin.php");
}

?>