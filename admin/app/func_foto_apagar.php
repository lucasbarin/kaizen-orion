<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_foto']) && is_numeric($_GET['id_foto'])){
	
	$id_foto = $_GET['id_foto'];

	
	$sql_foto = sql ("SELECT * FROM foto WHERE id_foto = '$id_foto'");
	$dados_foto = mysqli_fetch_array($sql_foto);
	
	$sql = sql ("DELETE FROM foto WHERE id_foto = '$id_foto' LIMIT 1");
	
	if ($sql){
		// @unlink ("../../imgs/".$dados_foto['imagem_foto']);
		// @unlink ("../../imgs/".$dados_foto['pdf_foto']);
		volta ("ok", "Registro excluído com sucesso!", "../foto-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../foto-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_foto'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_foto) {
						
				$sql_foto = sql ("SELECT * FROM foto WHERE id_foto = '$id_foto'");
				$dados_foto = mysqli_fetch_array($sql_foto);
				
				$sql = sql ("DELETE FROM foto WHERE id_foto = '$id_foto' LIMIT 1");
				
				if ($sql){
					$c++;
					// @unlink ("../../imgs/".$dados_foto['imagem_foto']);
					// @unlink ("../../imgs/".$dados_foto['pdf_foto']);
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
			volta ("ok", $count, "../foto-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../foto-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../foto-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../foto-admin.php");
}

?>