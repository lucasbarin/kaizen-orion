
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_unidade']) && is_numeric($_GET['id_unidade'])){
	
	$id_unidade = $_GET['id_unidade'];

	
	$sql_unidade = sql ("SELECT * FROM unidade WHERE id_unidade = '$id_unidade'");
	$dados_unidade = mysqli_fetch_array($sql_unidade);
	
	$sql = sql ("UPDATE `unidade` SET `status_unidade` = '2' id_unidade = '$id_unidade' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_unidade['imagem_unidade']);
		@unlink ("../../imgs/".$dados_unidade['pdf_unidade']);
		
		volta ("ok", "Registro excluído com sucesso!", "../unidade-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../unidade-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_unidade'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_unidade) {
						
				$sql_unidade = sql ("SELECT * FROM unidade WHERE id_unidade = '$id_unidade'");
				$dados_unidade = mysqli_fetch_array($sql_unidade);
				
				$sql = sql ("UPDATE `unidade` SET `status_unidade` = '2' id_unidade = '$id_unidade' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_unidade['imagem_unidade']);
					@unlink ("../../imgs/".$dados_unidade['pdf_unidade']);
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
			
			volta ("ok", $count, "../unidade-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../unidade-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../unidade-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../unidade-admin.php");
}

?>
