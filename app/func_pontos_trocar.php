<?php
include ("conexao8.php");
include ("funcoes8.php");
include ("sessao2.php");



if ( $_GET['produto'] && $_GET['produto'] ) {

	foreach ( $_GET as $k => $v ) {
		$$k = trata( $v );
	}
	
	
	$sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($_SESSION['usu'])." LIMIT 1");
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usuсrio nуo foi encontrado!", "../categorias.php" );
	}
	
	$sql_produto = sql ("SELECT * FROM produto WHERE id_produto =  ".trata($produto)." LIMIT 1");
	if (mysqli_num_rows($sql_produto)){
		$produto = mysqli_fetch_array($sql_produto);
	} else {
		volta( "erro", "O produto nуo foi encontrado!", "../categorias.php" );
	}
	
	
	if ($produto['pontos_produto'] > $usuario['ponto_colaborador']){
		volta ("erro", "Vocъ nуo possui pontos suficientes para realizar essa troca!", "../categorias.php");	
	}
	
	$hoje = date("Y-m-d H:i:s");
	
	$pontos = $produto['pontos_produto'];
	
	$sql = sql ("INSERT INTO `logtroca` (`modificacao_logtroca`, `id_colaborador`, `id_produto`, `nome_logtroca`, `ponto_logtroca`, `data_logtroca`, `status_logtroca`) VALUES (CURRENT_TIMESTAMP, '".$usuario['id_colaborador']."', '".$produto['id_produto']."', '".$produto['nome_produto']."', '".$produto['pontos_produto']."', '".$hoje."', '1')");
	
	
	if ($sql){
		
		$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador - $pontos) WHERE id_colaborador = ".$usuario['id_colaborador']." LIMIT 1");
        
        if($sqlcol){
            $lg = registraLog($usuario['id_colaborador'], -abs($pontos), $usuario['ponto_colaborador'] , "Troca por produto: ".$produto['nome_produto']." ", $pathConexao = "../app/");
        }
        
		
		
		volta ("ok", "Solicitaчуo de troca enviada com sucesso!", "../categorias.php");
		
		
	} else {
		volta ("erro", "Erro enviar sua solicitaчуo! Tente novamente mais tarde!", "../categorias.php");
	}
	
	
} else {
	volta ("erro", "Preencha todos os campos!", "../analisar-kaizen.php");
}
?>