<?php
$sufixo = "colaborador"; // nome da tabela e sufixo de todos campos da tabela;
//$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

$ig_admin = true;
$ig_apagar_cod = true;	

$campo[ 0 ][ 'nome' ] = "nome";
$campo[ 0 ][ 'tipo' ] = 1; // input normal
$campo[ 0 ][ 'label' ] = "Nome do colaborador";
$campo[ 0 ][ 'tamanho' ] = 100;
$campo[ 0 ][ 'required' ] = true;

$campo[ 1 ][ 'nome' ] = "email";
$campo[ 1 ][ 'tipo' ] = 1; // input normal
$campo[ 1 ][ 'label' ] = "E-mail do colaborador";
$campo[ 1 ][ 'tamanho' ] = 100;
$campo[ 1 ][ 'mascara' ] = "amigavel";

$campo[ 2 ][ 'nome' ] = "usuario";
$campo[ 2 ][ 'tipo' ] = 1; // input normal
$campo[ 2 ][ 'label' ] = "Usuário";
$campo[ 2 ][ 'dica' ] = "(utilize apenas letras, números, _ e .)";
$campo[ 2 ][ 'tamanho' ] = 20;
$campo[ 2 ][ 'required' ] = true;
$campo[ 2 ][ 'mascara' ] = "amigavel";

$campo[ 3 ][ 'nome' ] = "senha";
$campo[ 3 ][ 'tipo' ] = 1; // input normal
$campo[ 3 ][ 'label' ] = "Senha ";
$campo[ 3 ][ 'dica' ] = "(utilize apenas letras, números, _ e .)";
$campo[ 3 ][ 'tamanho' ] = 20;
$campo[ 3 ][ 'required' ] = true;
$campo[ 3 ][ 'mascara' ] = "amigavel";

$campo[ 4 ][ 'nome' ] = "unidade";
$campo[ 4 ][ 'tipo' ] = 2; // select
$campo[ 4 ][ 'label' ] = "Unidade";
$campo[ 4 ][ 'tamanho' ] = 11;
$campo[ 4 ][ 'opcaonome' ] = "nome_unidade"; // campo que trará o nome em echo
$campo[ 4 ][ 'opcaotabela' ] = "unidade"; // nome da tabela que será a base das infos
$campo[ 4 ][ 'opcaoordem' ] = "nome_unidade"; // campo responsavel pela ordenação
$campo[ 4 ][ 'required' ] = true;

$campo[ 5 ][ 'nome' ] = "setor";
$campo[ 5 ][ 'tipo' ] = 2; // select
$campo[ 5 ][ 'label' ] = "Setor";
$campo[ 5 ][ 'tamanho' ] = 11;
$campo[ 5 ][ 'required' ] = true;
$campo[ 5 ][ 'opcaonome' ] = "nome_setor"; // campo que trará o nome em echo
$campo[ 5 ][ 'opcaotabela' ] = "setor"; // nome da tabela que será a base das infos
$campo[ 5 ][ 'opcaoordem' ] = "nome_setor"; // campo responsavel pela ordenação

$campo[ 6 ][ 'nome' ] = "lider";
$campo[ 6 ][ 'tipo' ] = 2; // select
$campo[ 6 ][ 'label' ] = "Líder Kaizen?";
$campo[ 6 ][ 'tamanho' ] = 11;
$campo[ 6 ][ 'opcaonome' ] = "nome_opcao"; // campo que trará o nome em echo
$campo[ 6 ][ 'opcaotabela' ] = "opcao"; // nome da tabela que será a base das infos
$campo[ 6 ][ 'opcaoordem' ] = "nome_opcao"; // campo responsavel pela ordenação
$campo[ 6 ][ 'required' ] = true;

$codigo_adicional_inserir = '
	   $sql = sql ("SELECT * FROM colaborador WHERE usuario_colaborador = \'$usuario_colaborador\' LIMIT 1", $con);
		if (mysqli_num_rows($sql) > 0){
			volta ("erro", "O nome do usuario já está em uso. Escolha outro!", "../colaborador-inserir.php");
		} 
';
	
$codigo_adicional_editar = '
	   $sql = sql ("SELECT * FROM colaborador WHERE usuario_colaborador = \'$usuario_colaborador\' AND id_colaborador <> \'$id_colaborador\' LIMIT 1", $con);
		if (mysqli_num_rows($sql) > 0){
			volta ("erro", "O nome do usuario já está em uso por outro usuário. Escolha outro!", "../colaborador-inserir.php");
		} 
';	
	
;
?>