
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_setor']) && is_numeric($_GET['id_setor'])){
	
	$id_setor = $_GET['id_setor'];

	
	$sql_setor = sql ("SELECT * FROM setor WHERE id_setor = '$id_setor'");
	$dados_setor = mysqli_fetch_array($sql_setor);
	
	$sql = sql ("UPDATE `setor` SET `status_setor` = '2' id_setor = '$id_setor' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_setor['imagem_setor']);
		@unlink ("../../imgs/".$dados_setor['pdf_setor']);
		
		volta ("ok", "Registro excluído com sucesso!", "../setor-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../setor-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_setor'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_setor) {
						
				$sql_setor = sql ("SELECT * FROM setor WHERE id_setor = '$id_setor'");
				$dados_setor = mysqli_fetch_array($sql_setor);
				
				$sql = sql ("UPDATE `setor` SET `status_setor` = '2' id_setor = '$id_setor' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_setor['imagem_setor']);
					@unlink ("../../imgs/".$dados_setor['pdf_setor']);
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
			
			volta ("ok", $count, "../setor-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../setor-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../setor-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../setor-admin.php");
}

?>
