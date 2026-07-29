
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_lidersetor']) && is_numeric($_GET['id_lidersetor'])){
	
	$id_lidersetor = $_GET['id_lidersetor'];

	
	$sql_lidersetor = sql ("SELECT * FROM lidersetor WHERE id_lidersetor = '$id_lidersetor'");
	$dados_lidersetor = mysqli_fetch_array($sql_lidersetor);
	
	$sql = sql ("UPDATE `lidersetor` SET `status_lidersetor` = '2' id_lidersetor = '$id_lidersetor' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_lidersetor['imagem_lidersetor']);
		@unlink ("../../imgs/".$dados_lidersetor['pdf_lidersetor']);
		
		volta ("ok", "Registro excluído com sucesso!", "../lidersetor-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../lidersetor-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_lidersetor'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_lidersetor) {
						
				$sql_lidersetor = sql ("SELECT * FROM lidersetor WHERE id_lidersetor = '$id_lidersetor'");
				$dados_lidersetor = mysqli_fetch_array($sql_lidersetor);
				
				$sql = sql ("UPDATE `lidersetor` SET `status_lidersetor` = '2' id_lidersetor = '$id_lidersetor' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_lidersetor['imagem_lidersetor']);
					@unlink ("../../imgs/".$dados_lidersetor['pdf_lidersetor']);
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
			
			volta ("ok", $count, "../lidersetor-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../lidersetor-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../lidersetor-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../lidersetor-admin.php");
}

?>
