<?php
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");


$volta = "pagina=1";

if(!empty($_GET['kaizen']) && is_numeric($_GET['kaizen'])){

	foreach( $_GET as $k => $v ){
		$$k = trata( $v );
	}
	
	$id_kaizen = $_GET['kaizen'];

	
	$sql_kaizen = sql ("SELECT * FROM kaizen WHERE id_kaizen = '$id_kaizen'");
	$dados_kaizen = mysqli_fetch_array($sql_kaizen);
	
	$sql = sql ("DELETE FROM kaizen WHERE id_kaizen = '$id_kaizen' LIMIT 1");
	
	if ($sql){
		
		volta ("ok", "Registro excluído com sucesso!", "../kaizen-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../kaizen-admin.php");
	}
	
if ($sql){
		
		volta ("ok", "Registro alterado com sucesso!", "../kaizen-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../kaizen-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../kaizen-indicar.php");
}
ob_end_flush();
?>

