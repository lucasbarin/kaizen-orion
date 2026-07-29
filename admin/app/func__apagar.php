
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_']) && is_numeric($_GET['id_'])){
	
	$id_ = $_GET['id_'];

	
	$sql_ = sql ("SELECT * FROM  WHERE id_ = '$id_'");
	$dados_ = mysqli_fetch_array($sql_);
	
	$sql = sql ("DELETE FROM  WHERE id_ = '$id_' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_['imagem_']);
		@unlink ("../../imgs/".$dados_['pdf_']);
		
		volta ("ok", "Registro excluído com sucesso!", "../-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_) {
						
				$sql_ = sql ("SELECT * FROM  WHERE id_ = '$id_'");
				$dados_ = mysqli_fetch_array($sql_);
				
				$sql = sql ("DELETE FROM  WHERE id_ = '$id_' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_['imagem_']);
					@unlink ("../../imgs/".$dados_['pdf_']);
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
			
			volta ("ok", $count, "../-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../-admin.php");
}

?>
