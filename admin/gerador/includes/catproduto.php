<?php
$sufixo = "catproduto"; // nome da tabela e sufixo de todos campos da tabela;
$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

// $ig_admin = true;
// $ig_apagar_cod = true;	
$n = 0;
$campo[ $n ][ 'nome' ] = "nome";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Nome da categoria";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;

$n = 1;
$campo[ $n ][ 'nome' ] = "numero";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Ordem que aparece";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'mascara' ] = "numeros";


?>