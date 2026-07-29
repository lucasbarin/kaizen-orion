<?php
include( "../app/conexao8.php" );
include( "../app/funcoes8.php" );

error_reporting(E_ALL);

$login = trata( $_POST[ 'usuario' ] );
$email = trata( $_POST[ 'email' ] );

if ( !empty( $_SESSION[ 'link' ] ) ) {
  $url = $_SESSION[ 'link' ];
} else {
  $url = 'index.php';
}


if ( !empty( $login ) && !empty( $email ) ) {

  $sql = mysqli_query($con,  "SELECT * FROM colaborador WHERE usuario_colaborador = '$login' AND email_colaborador = '$email' LIMIT 1" )or die( mysqli_error($con) );
	
	if(mysqli_num_rows($sql)){
		$dados = mysqli_fetch_array( $sql );
		$id_colaborador = $dados['id_colaborador'];
		
		// vamos resetar a senha
		 $novasenha = "temp".rand( 11111, 99999 );
		
		
		$sql = sql ("UPDATE `colaborador` SET `senha_colaborador` = '$novasenha', `alterarsenha_colaborador` = '1' WHERE `id_colaborador` = '$id_colaborador' LIMIT 1");
		
		
		
          $nome_colaborador = $dados[ 'nome_colaborador' ];
          $usuario_colaborador = $dados[ 'usuario_colaborador' ];
          $email_colaborador = $dados[ 'email_colaborador' ];
		
		$para = $email_colaborador;


          include( "mail_recuperar_senha.php" );


          $msg = "A nova senha foi enviada para o e-mail " . $email_cliente . ", verifique sua caixa de entrada.

			Este e-mail pode ser reconhecido como spam. Verifique também seu lixo eletrônico.";
		

          volta( "ok", $msg, "../index.php" );

         
		

	} else {
		
		  $msg = "O login e e-mail informados não conferem. Verifique as informações.";
  		  volta( "erro", $msg, "../esqueci_senha.php" );

	}
  


	
	


} else {
  $msg = "Informe seu login e e-mail corretamente!";
  volta( "erro", $msg, "../esqueci_senha.php" );
}
?>
