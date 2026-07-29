<?php
$sufixo = "lidersetor"; // nome da tabela e sufixo de todos campos da tabela;
//$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

$ig_admin = true;
$ig_apagar_cod = true;	

$campo[ 0 ][ 'nome' ] = "nome";
$campo[ 0 ][ 'tipo' ] = 1; // input normal
$campo[ 0 ][ 'label' ] = "Nome do líder";
$campo[ 0 ][ 'tamanho' ] = 100;
$campo[ 0 ][ 'required' ] = true;

$campo[ 1 ][ 'nome' ] = "setores";
$campo[ 1 ][ 'tipo' ] = 6; // checkbox
$campo[ 1 ][ 'label' ] = "Setores";
$campo[ 1 ][ 'opcaonome' ] = "nome_setor"; // campo que trará o nome em echo
$campo[ 1 ][ 'opcaotabela' ] = "setor"; // nome da tabela que será a base das infos
$campo[ 1 ][ 'opcaoordem' ] = "nome_setor"; // campo responsavel pela ordenação
?>