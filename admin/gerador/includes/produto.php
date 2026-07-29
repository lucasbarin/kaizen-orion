<?php
$sufixo = "produto"; // nome da tabela e sufixo de todos campos da tabela;
$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

$ig_admin = true;
// $ig_apagar_cod = true;	
$n = 0;
$campo[ $n ][ 'nome' ] = "nome";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Nome do produto";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;

$n = 1;
$campo[ $n ][ 'nome' ] = "catproduto";
$campo[ $n ][ 'tipo' ] = 2; // select
$campo[ $n ][ 'label' ] = "Categoria";
$campo[ $n ][ 'tamanho' ] = 11;
$campo[ $n ][ 'opcaonome' ] = "nome_catproduto"; // campo que trará o nome em echo
$campo[ $n ][ 'opcaotabela' ] = "catproduto"; // nome da tabela que será a base das infos
$campo[ $n ][ 'opcaoordem' ] = "nome_catproduto"; // campo responsavel pela ordenação
$campo[ $n ][ 'required' ] = true;

$n = 2;
$campo[ $n ][ 'nome' ] = "numero";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Ordem que aparece";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'mascara' ] = "numeros";

$n = 3;
$campo[ $n ][ 'nome' ] = "imagem1";
$campo[ $n ][ 'tipo' ] = 4; // input normal
$campo[ $n ][ 'label' ] = "Imagem ilustrativa";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'dica' ] = "Imagens JPG com máximo 1000px"; 
$campo[ $n ][ 'arquivo' ] = "imagem";
$campo[ $n ][ 'comprimento' ] = 1000;

$n = 4;
$campo[ $n ][ 'nome' ] = "pontos";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Pontos (custo)";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'mascara' ] = "numeros";


$n = 5;
$campo[ $n ][ 'nome' ] = "texto1";
$campo[ $n ][ 'tipo' ] = 3; 
$campo[ $n ][ 'label' ] = "Descrição";
$campo[ $n ][ 'required' ] = false;



$n = 6;
$campo[ $n ][ 'nome' ] = "site";
$campo[ $n ][ 'tipo' ] = 1;
$campo[ $n ][ 'tamanho' ] = 300;
$campo[ $n ][ 'label' ] = "URL da Loja (apenas admin)";
$campo[ $n ][ 'required' ] = false;

?>