
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_colaborador']) && is_numeric($_GET['id_colaborador'])){
	
	$id_colaborador = $_GET['id_colaborador'];

	
	$sql_colaborador = sql ("SELECT * FROM colaborador WHERE id_colaborador = '$id_colaborador'");
	$dados_colaborador = mysqli_fetch_array($sql_colaborador);
	
	$sql = sql ("UPDATE `colaborador` SET `status_colaborador` = '2'  WHERE id_colaborador = '$id_colaborador' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_colaborador['imagem_colaborador']);
		@unlink ("../../imgs/".$dados_colaborador['pdf_colaborador']);
		
		volta ("ok", "Registro excluído com sucesso!", "../colaborador-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../colaborador-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_colaborador'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_colaborador) {
						
				$sql_colaborador = sql ("SELECT * FROM colaborador WHERE id_colaborador = '$id_colaborador'");
				$dados_colaborador = mysqli_fetch_array($sql_colaborador);
				
				$sql = sql ("UPDATE `colaborador` SET `status_colaborador` = '2' WHERE id_colaborador = '$id_colaborador' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_colaborador['imagem_colaborador']);
					@unlink ("../../imgs/".$dados_colaborador['pdf_colaborador']);
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
			
			volta ("ok", $count, "../colaborador-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../colaborador-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../colaborador-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../colaborador-admin.php");
}

?>
