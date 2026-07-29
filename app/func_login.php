<?php
@session_start();
include ("../app/conexao8.php");
include ("../app/funcoes8.php");
$login = trata($_POST['usuario']);
$senha = trata($_POST['pswd']);

if (!empty($_SESSION['link'])) {
	$url = $_SESSION['link'];
}  else {
    $url = 'home.php';
}
if (!empty($_GET['login']) && !empty ($_GET['senha'])){
	$login = trata ($_GET['usuario']);
	$senha = trata ($_GET['pswd']);
}
if (!empty($login) && !empty($senha)){
	
	$sql = mysqli_query($con, "SELECT * FROM colaborador WHERE usuario_colaborador = '$login' LIMIT 1") or die (mysqli_error($con));
	
	if (mysqli_num_rows($sql) > 0) {
		$dados = mysqli_fetch_array($sql);
		
		if ($senha == $dados['senha_colaborador']){
			unset ($_SESSION['log'], $_SESSION['usu']);
			$id =  $dados['id_colaborador'];
			$_SESSION['log'] = 'fgnx45#Hdf2_fg3';
			$_SESSION['usu'] = $id;
			
			// Processar "Lembrar-me"
			if (isset($_POST['remember']) && $_POST['remember'] == '1') {
				// Salvar cookies por 30 dias
				setcookie('orion_user', $login, time() + (30 * 24 * 60 * 60), '/', '', false, true);
				setcookie('orion_pass', base64_encode($senha), time() + (30 * 24 * 60 * 60), '/', '', false, true);
			} else {
				// Remover cookies se desmarcado
				if (isset($_COOKIE['orion_user'])) {
					setcookie('orion_user', '', time() - 3600, '/', '', false, true);
				}
				if (isset($_COOKIE['orion_pass'])) {
					setcookie('orion_pass', '', time() - 3600, '/', '', false, true);
				}
			}
			
			unset($_SESSION['link']);
			header("Location: ../".$url);
			die;	
		
		} else {
			$msg = "Login e/ou senha incorreto(s)!";
			volta ("erro", $msg, "../index.php");
		}
	} else {
		$msg = "Usuário não encontrado!";
		volta ("erro", $msg, "../index.php");
	}
	
	
} else {
	$msg = "Informe seu login e senha!";
	volta ("erro", $msg, "../index.php");
}
?>