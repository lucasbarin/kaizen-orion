<meta charset="utf-8"/>
<?
$sufixo;
$ordenador;

// tipos de input
$tipo[ 1 ] = "text";
$tipo[ 2 ] = "select";
$tipo[ 3 ] = "textarea";
$tipo[ 4 ] = "file";
$tipo[ 5 ] = "radio";
$tipo[ 6 ] = "checkbox";
$tipo[ 7 ] = "data";
$tipo[ 8 ] = "money";
$tipo[ 9 ] = "color";



/*

// ignorar escrita de determinado arquivo.
$ig_inserir = false;
$ig_editar = false;
$ig_admin = false;
$ig_inserir_cod = false;
$ig_editar_cod = false;
$ig_apagar_cod = false;



required: 0 = não / 1 = sim
tamanho caracteres / padrão = 100
mascara js: numeros data cpf cnpj money , padrão : nada
editor "wysihtml5" ou "ckeditor" ou ""

$codigo_adicional_inserir;
$codigo_adicional_editar; 
// são trechos de codigos personalizados a serem implantandos antes do insert, para filtros como exemplo de usuarios ja existente s no bancod e dados.

EXEMPLO COMPLETO
$campo[ 1 ][ 'nome' ] = "nome";
$campo[ 1 ][ 'tipo' ] = 1; // input normal
$campo[ 1 ][ 'label' ] = "E-mail do colaborador";
$campo[ 1 ][ 'tamanho' ] = 100;
$campo[ 1 ][ 'required' ] = true;
$campo[ 1 ][ 'mascara' ] = "data";
$campo[ 1 ][ 'editor' ] = "wysihtml5"; ou "ckeditor" ou ""
$campo[ 1 ][ 'dica' ] = "Insira um URL Válido como http://www.catenacom.com"; 

INFOS APENAS PARA ARQUIVOS
$campo[ 1 ][ 'arquivo' ] = "imagem ou livre - vazio";
$campo[ 1 ][ 'comprimento' ] = 2000 "comprimento máximo das imagens compressão";

INFOS APENAS PARA OPTION CHECKBOX E RADIOS COM CONTENT DINAMICO
$campo[ 1 ][ 'opcaonome' ] = "nome_tabela"; // campo que trará o nome em echo
$campo[ 1 ][ 'opcaotabela' ] = "tabela"; // nome da tabela que será a base das infos
$campo[ 1 ][ 'opcaoordem' ] = "numero_tabela" // campo responsavel pela ordenação

*/

if (empty($_GET['config'])){
    die("Configure ? config");
}

include_once("includes/".$_GET['config'].".php");



$num_campos = sizeof( $campo );

$sqlcode = "
CREATE TABLE IF NOT EXISTS `".$sufixo."` (
  `id_".$sufixo."` int(11) NOT NULL AUTO_INCREMENT,
  ";




for ( $i = 0; $i <= $num_campos; $i++ ) {
    $label = $campo[ $i ][ 'label' ];
	$nome = $campo[ $i ][ 'nome' ];
    $mascara = $campo[ $i ][ 'mascara' ];
    
 
    
    
    if (($campo[ $i ][ 'tipo' ] == 1 or $campo[ $i ][ 'tipo' ] == 4 or $campo[ $i ][ 'tipo' ] == 9) && $mascara != "numeros"){
       // campo tipo varchar: 1 4 9
        if ( $campo[ $i ][ 'tamanho' ] > 0 ) {
            $tamanho = $campo[ $i ][ 'tamanho' ];
        } else {
            $tamanho = 200;
        }
        
        $sqlcode.= "`".$nome."_".$sufixo."` varchar(".$tamanho.") NOT NULL,

  "; 
        
    }
    
    if ($campo[ $i ][ 'tipo' ] == 8){
       // campo tipo Float 8
        $sqlcode.= "`".$nome."_".$sufixo."` float NOT NULL,
  ";        
        
    }
        
    
    if ($campo[ $i ][ 'tipo' ] == 2 or $campo[ $i ][ 'tipo' ] == 5 or $mascara == "numeros"){
       // campo tipo INT 2 5
    if ( $campo[ $i ][ 'tamanho' ] > 0 && $mascara <> "numeros") {
            $tamanho = $campo[ $i ][ 'tamanho' ];
        } else {
            $tamanho = 11;
        }
        $sqlcode.= "`".$nome."_".$sufixo."` int(".$tamanho.") NOT NULL,
  ";           
        
    }        
    
    if ($campo[ $i ][ 'tipo' ] == 3 or $campo[ $i ][ 'tipo' ] == 6){
       // campo tipo Text 3 6
        $sqlcode.= "`".$nome."_".$sufixo."` text NOT NULL,
  ";           
    } 
    
    if ($campo[ $i ][ 'tipo' ] == 7){
       // campo tipo date 7
       $sqlcode.= "`".$nome."_".$sufixo."` date NOT NULL,
  ";    
        
    }     
    
    
	
	
	
	
	unset( $label, $required, $tamanho, $requiredmark, $mascara, $editor, $dica, $comprimento, $arquivo, $z, $check, $opcaonome, $opcaotabela, $opcaoordem, $opcaodinamica, $opt);
} // fim for



$sqlcode.= "
  PRIMARY KEY (`id_".$sufixo."`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
";
?>



<pre>

<?




?>


<strong>RESULTADO:</strong><br>
<?
$name = '../bd-'.$sufixo.'.sql';
$text = $sqlcode;
$file = fopen($name, 'w');
$escreve = fwrite($file, $text);
fclose($file);
if ($escreve){
    echo 'Esquema de BD criado com sucesso - '.$name.'<br>';
} else {
    echo 'Erro ao criar esquema de BD <br>';
}
echo   '<br><br><strong><a href="index.php">VOLTAR PARA LISTA</a></strong>';
 // fim empty prefixo
?>
