<?php
ob_start();
session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST['id_login']) && !empty ($_POST['nome_login']) && !empty ($_POST['senha_login'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}

	$sql = sql ("UPDATE login SET nome_login = '$nome_login', senha_login = '$senha_login' WHERE id_login = '$id_login'");
	
	if ($sql){
		volta ("ok", "Login/senha alterados com sucesso!", "../login-editar.php");
	} else {
		volta ("erro", "Erro ao alterar login/senha! Tente novamente mais tarde!", "../login-editar.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../login-editar.php");
}
ob_end_flush();
?>