<?php
$sufixo = "tipo"; // nome da tabela e sufixo de todos campos da tabela;
$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

// $ig_admin = true; // nao atualiza a página -admin
// $orddata = 1; // prioriza ordenação por data no -admin
 //$adicional_atualiza_campo = "subcategoria"; // ordenação de numero atrelado a BD adicional


// $ig_apagar_cod = true;	
$n = 0;
$campo[ $n ][ 'nome' ] = "nome";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Nome";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;


$n++;$campo[ $n ][ 'nome' ] = "numero";
$campo[ $n ][ 'tipo' ] = 1; // input money
$campo[ $n ][ 'label' ] = "Ordem que aparece";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'mascara' ] = "numeros";



$n++;
$campo[ $n ][ 'nome' ] = "complemento";
$campo[ $n ][ 'tipo' ] = 2; // select 
$campo[ $n ][ 'label' ] = "Complemento de informação (opcional)";
$campo[ $n ][ 'tamanho' ] = 11;
$campo[ $n ][ 'opcaonome' ] = "nome_complemento"; // campo que trará o nome em echo
$campo[ $n ][ 'opcaotabela' ] = "complemento"; // nome da tabela que será a base das infos
$campo[ $n ][ 'opcaoordem' ] = "nome_complemento"; // campo responsavel pela ordenação
$campo[ $n ][ 'required' ] = false;




?>