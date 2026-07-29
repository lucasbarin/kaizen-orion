
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_catproduto']) && is_numeric($_GET['id_catproduto'])){
	
	$id_catproduto = $_GET['id_catproduto'];

	
	$sql_catproduto = sql ("SELECT * FROM catproduto WHERE id_catproduto = '$id_catproduto'");
	$dados_catproduto = mysqli_fetch_array($sql_catproduto);
	
	$sql = sql ("DELETE FROM catproduto WHERE id_catproduto = '$id_catproduto' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_catproduto['imagem_catproduto']);
		@unlink ("../../imgs/".$dados_catproduto['pdf_catproduto']);
		
	AtualizaOrdem("catproduto", "id_catproduto", "numero_catproduto");
	
		volta ("ok", "Registro excluído com sucesso!", "../catproduto-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../catproduto-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_catproduto'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_catproduto) {
						
				$sql_catproduto = sql ("SELECT * FROM catproduto WHERE id_catproduto = '$id_catproduto'");
				$dados_catproduto = mysqli_fetch_array($sql_catproduto);
				
				$sql = sql ("DELETE FROM catproduto WHERE id_catproduto = '$id_catproduto' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_catproduto['imagem_catproduto']);
					@unlink ("../../imgs/".$dados_catproduto['pdf_catproduto']);
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
			
	AtualizaOrdem("catproduto", "id_catproduto", "numero_catproduto");
	
			volta ("ok", $count, "../catproduto-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../catproduto-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../catproduto-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../catproduto-admin.php");
}

?>
