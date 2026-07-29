
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_tabela']) && is_numeric($_GET['id_tabela'])){
	
	$id_tabela = $_GET['id_tabela'];

	
	$sql_tabela = sql ("SELECT * FROM tabela WHERE id_tabela = '$id_tabela'");
	$dados_tabela = mysqli_fetch_array($sql_tabela);
	
	$sql = sql ("DELETE FROM tabela WHERE id_tabela = '$id_tabela' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_tabela['imagem_tabela']);
		@unlink ("../../imgs/".$dados_tabela['pdf_tabela']);
		
		volta ("ok", "Registro excluído com sucesso!", "../tabela-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../tabela-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_tabela'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_tabela) {
						
				$sql_tabela = sql ("SELECT * FROM tabela WHERE id_tabela = '$id_tabela'");
				$dados_tabela = mysqli_fetch_array($sql_tabela);
				
				$sql = sql ("DELETE FROM tabela WHERE id_tabela = '$id_tabela' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_tabela['imagem_tabela']);
					@unlink ("../../imgs/".$dados_tabela['pdf_tabela']);
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
			
			volta ("ok", $count, "../tabela-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../tabela-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../tabela-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../tabela-admin.php");
}

?>
