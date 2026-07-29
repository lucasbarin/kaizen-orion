
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET['id_produto']) && is_numeric($_GET['id_produto'])){
	
	$id_produto = $_GET['id_produto'];

	
	$sql_produto = sql ("SELECT * FROM produto WHERE id_produto = '$id_produto'");
	$dados_produto = mysqli_fetch_array($sql_produto);
	
	$sql = sql ("DELETE FROM produto WHERE id_produto = '$id_produto' LIMIT 1");
	
	if ($sql){
		@unlink ("../../imgs/".$dados_produto['imagem_produto']);
		@unlink ("../../imgs/".$dados_produto['pdf_produto']);
		
	   AtualizaOrdem("produto", "id_produto", "numero_produto", "catproduto_produto", $dados_produto['catproduto_produto']);
	
		volta ("ok", "Registro excluído com sucesso!", "../produto-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../produto-admin.php");
	}

} elseif($_POST['select_del'] == 1){
	$ids = $_POST['ids_produto'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_produto) {
						
				$sql_produto = sql ("SELECT * FROM produto WHERE id_produto = '$id_produto'");
				$dados_produto = mysqli_fetch_array($sql_produto);
				
				$sql = sql ("DELETE FROM produto WHERE id_produto = '$id_produto' LIMIT 1");
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_produto['imagem_produto']);
					@unlink ("../../imgs/".$dados_produto['pdf_produto']);
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
			
	AtualizaOrdem("produto", "id_produto", "numero_produto", "catproduto_produto", $dados_produto['catproduto_produto']);
	
			volta ("ok", $count, "../produto-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../produto-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../produto-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../produto-admin.php");
}

?>
