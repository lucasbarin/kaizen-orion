<?php
$sufixo = "teste"; // nome da tabela e sufixo de todos campos da tabela;
$ordenador = "numero"; // campo que ordenará a exibição (deixar em branco caso não possua);

// $ig_admin = true; // nao atualiza a página -admin
// $orddata = 1; // prioriza ordenação por data no -admin
// $adicional_atualiza_campo = "subcategoria"; // ordenação de numero atrelado a BD adicional


// $ig_apagar_cod = true;	
$n = 0;
$campo[ $n ][ 'nome' ] = "nome";
$campo[ $n ][ 'tipo' ] = 1; // input normal
$campo[ $n ][ 'label' ] = "Nome";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;


$n++;
$campo[ $n ][ 'nome' ] = "opcao";
$campo[ $n ][ 'tipo' ] = 2; // select 
$campo[ $n ][ 'label' ] = "Opção";
$campo[ $n ][ 'tamanho' ] = 11;
$campo[ $n ][ 'opcaonome' ] = "nome_opcao"; // campo que trará o nome em echo
$campo[ $n ][ 'opcaotabela' ] = "opcao"; // nome da tabela que será a base das infos
$campo[ $n ][ 'opcaoordem' ] = "nome_opcao"; // campo responsavel pela ordenação
$campo[ $n ][ 'required' ] = true;

$n++;
$campo[ $n ][ 'nome' ] = "numero";
$campo[ $n ][ 'tipo' ] = 1; // input money
$campo[ $n ][ 'label' ] = "Ordem que aparece";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'mascara' ] = "numeros";

$n++;
$campo[ $n ][ 'nome' ] = "preco";
$campo[ $n ][ 'tipo' ] = 8; // input normal
$campo[ $n ][ 'label' ] = "Preço";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;

$n++;
$campo[ $n ][ 'nome' ] = "cor";
$campo[ $n ][ 'tipo' ] = 9; // input cor
$campo[ $n ][ 'label' ] = "Cor";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;

$n++;
$campo[ $n ][ 'nome' ] = "imagem1";
$campo[ $n ][ 'tipo' ] = 4; // input imagem
$campo[ $n ][ 'label' ] = "Imagem ilustrativa (principal)";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = true;
$campo[ $n ][ 'dica' ] = "Imagens JPG com máximo 1000px"; 
$campo[ $n ][ 'arquivo' ] = "imagem";
$campo[ $n ][ 'comprimento' ] = 1000;

$n++;
$campo[ $n ][ 'nome' ] = "pdf1";
$campo[ $n ][ 'tipo' ] = 4; // input arquivo
$campo[ $n ][ 'label' ] = "Catálogo PDF (Download)";
$campo[ $n ][ 'tamanho' ] = 100;
$campo[ $n ][ 'required' ] = false;
$campo[ $n ][ 'dica' ] = "Arquivo PDF (máximo 3MB)"; 

$n++;
$campo[ $n ][ 'nome' ] = "texto1";
$campo[ $n ][ 'tipo' ] = 3; 
$campo[ $n ][ 'label' ] = "Descrição";
$campo[ $n ][ 'required' ] = false;
$campo[ $n ][ 'editor' ] = "ckeditor";



?>