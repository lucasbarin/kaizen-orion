<?php

error_reporting(E_ALL);

// Verificação de data de lançamento (remover após 02/02/2026)
$ipUsuario = $_SERVER['REMOTE_ADDR'] ?? '';
$ipLiberado = ($ipUsuario === '152.245.55.14');

if (!$ipLiberado && strtotime('today') < strtotime('2026-02-02')) {
	die('<div style="font-family: Arial, sans-serif; text-align: center; padding: 50px; background: #f5f5f5; min-height: 100vh;">
		<h1 style="color: #009ee3;">🚀 Sistema em atualização</h1>
		<p style="color: #666; font-size: 18px;">Estamos preparando novidades para você.<br>Retorne em <strong>02/02/2026</strong>.</p>
	</div>');
}

// Detectar ambiente automaticamente baseado no host
$host = $_SERVER['HTTP_HOST'] ?? $_SERVER['SERVER_NAME'] ?? 'localhost';
$ambiente = (strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false) ? 'local' : 'producao';


if ($ambiente === 'local') {
	$servidor = 'localhost';
	$usuario = 'root';
	$senha = '';  // Senha vazia (padrão WAMP)
	$banco = 'orionv2_frontend';  // ou 'orionv2_frontend'
	$urlSite = 'http://localhost/orion-v2/';

} else {
	// PRODUÇÃO
	/*
	$servidor = 'orionv2.mysql.dbaas.com.br';
	$usuario = 'orionv2';
	$senha = 'Catenacom2025@';
	//orionv226 frn6XjyY#zCdMV
	$banco = 'orionv2';*/

	$servidor = 'orionv226.mysql.dbaas.com.br';
	$usuario = 'orionv226';
	$senha = 'frn6XjyY#zCdMV';
	$banco = 'orionv226';

	$urlSite = 'https://orion2026proviso1.websiteseguro.com/';
}




// Conecta ao MySQL
if (!$con = mysqli_connect($servidor, $usuario, $senha, $banco)) {
	echo "Erro na conexão ao banco de dados";
	exit();
}

# Aqui está o segredo
mysqli_query($con, "SET NAMES 'utf8mb4'");
mysqli_query($con, 'SET character_set_connection=utf8mb4');
mysqli_query($con, 'SET character_set_client=utf8mb4');
mysqli_query($con, 'SET character_set_results=utf8mb4');

?>