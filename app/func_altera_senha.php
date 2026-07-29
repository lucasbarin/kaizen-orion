<?php
include ("conexao8.php");
include ("funcoes8.php");
include ("sessao2.php");


if ( !empty($_POST['senha_atual']) && !empty($_POST['senha_nova']) && !empty($_POST['senha_confirma']) ) {


	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}
    
    
	$sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($_SESSION['usu'])." LIMIT 1", $con);
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usuário não foi encontrado!", "../altera-senha.php" );
	}
	
	$id_colaborador = $usuario['id_colaborador'];
	
	// Verifica se a senha atual está correta
	if($senha_atual != $usuario['senha_colaborador']){
		volta ("erro", "A senha atual informada está incorreta!", "../altera-senha.php");	
	}
	
	// Verifica se as novas senhas conferem
	if($senha_nova != $senha_confirma){
		volta ("erro", "As senhas inseridas não conferem. Tente novamente!", "../altera-senha.php");	
	}
	
	// Verifica se a nova senha é diferente da atual
	if($senha_nova == $usuario['senha_colaborador']){
		volta ("erro", "A nova senha não pode ser igual à senha atual. Por favor, insira outra senha!", "../altera-senha.php");	
	}
		

	$sql = sql ("UPDATE `colaborador` SET `senha_colaborador` = '$senha_nova', `alterarsenha_colaborador` = '0' WHERE `id_colaborador` = '$id_colaborador' LIMIT 1", $con);
	

	if ($sql){
		volta ("ok", "Senha alterada com sucesso!", "../home.php");
	} else {
		volta ("erro", "Erro ao alterar sua senha! Tente novamente mais tarde!", "../altera-senha.php");
	}

	
} else {
	volta ("erro", "Preencha todos os campos obrigatórios!", "../altera-senha.php");
}
?>