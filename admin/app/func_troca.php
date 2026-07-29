<?php

ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );


if ( $_GET['id_logtroca'] && $_GET['acao'] ) {

	foreach ( $_GET as $k => $v ) {
		$$k = trata( $v );
	}
	
	// dados do usu admin
	$sql_logtroca = sql("SELECT * FROM logtroca WHERE id_logtroca =  $id_logtroca LIMIT 1", $con);
	if (mysqli_num_rows($sql_logtroca)){
		$logtroca = mysqli_fetch_array($sql_logtroca);
	} else {
		volta( "erro", "Troca não encontrada!", "../troca-admin.php" );
	}
	
	if ($logtroca['status_logtroca'] == 2 or $logtroca['status_logtroca'] == 3){
		volta( "erro", "Essa troca já foi processada!", "../troca-admin.php" );
	}
	
	if ($acao <> 2 && $acao <> 3){
		volta( "erro", "Clique na ação desejada para esta troca!", "../troca-admin.php" );
	}
	

		
	$pontosOld = $logtroca['ponto_logtroca'];
	$statusOld = $logtroca['status_logtroca'];
	
	
	
	$data = date("Y-m-d");
	 
	// echo "status $statusOld | analise $analise"; die;
	$sql = sql ("
	UPDATE `logtroca` SET `status_logtroca` = '$acao' WHERE `logtroca`.`id_logtroca` = $id_logtroca;
	");
	

if ($sql){
	
		if ($acao == 3){
			$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontosOld) WHERE id_colaborador = ".$logtroca['id_colaborador']." LIMIT 1");
            
            $sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador =  ".$logtroca['id_colaborador']." LIMIT 1");
            if (mysqli_num_rows($sql_usu)){
                $usuario = mysqli_fetch_array($sql_usu);
            }
            
            $produto = $logtroca['id_produto'];
            $sql_produto = sql ("SELECT * FROM produto WHERE id_produto =  ".trata($produto)." LIMIT 1");
            if (mysqli_num_rows($sql_produto)){
                $produto = mysqli_fetch_array($sql_produto);
            } else {
                volta( "erro", "O produto não foi encontrado!", "../categorias.php" );
            }

            if($sqlcol){
                $lg = registraLog($usuario['id_colaborador'], $pontosOld, $usuario['ponto_colaborador'] - $pontosOld , "Cancelamento da troca: ".$produto['nome_produto']." ", $pathConexao = "../app/");
            }
            
            
		}
	
		volta ("ok", "Troca processada com sucesso!", "../troca-admin.php");
	
			
	} else {
		volta ("erro", "Erro enviar sua solicitação! Tente novamente mais tarde!", "../troca-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../troca-admin.php");
}
ob_end_flush();
?>