<?php


function getRoot(){
	$root = $_SERVER['DOCUMENT_ROOT']."/".

	$diretorio = "rastreabilidade/";


	return strval ($root.$diretorio);


}


function labelAdmin ($string) {
	
	$string = preg_replace ('/i1/', '<img src="assets/admin/layout/img/flags/br.png" width="16" height="11" alt="BR"/>', $string);
	$string = preg_replace ('/i2/', '<img src="assets/admin/layout/img/flags/us.png" width="16" height="11" alt="US"/>', $string);
	$string = preg_replace ('/i3/', '<img src="assets/admin/layout/img/flags/es.png" width="16" height="11" alt="ES"/>', $string);
	echo $string;
	
}

function is_email($email){
	if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		return false;
	} else {
		return true;
	}
}

function is_url($url){
	if (filter_var($url, FILTER_VALIDATE_URL)) {
		return true;
	} else {
		return false;
	}
}

function verimg($img, $path = "imgs/"){
	if (!empty($img) && file_exists($path.$img)){ return true; } else { return false; }
}

function data_sql ($data){
	$dt = explode ("/", $data);
	$data = $dt[2]."-".$dt[1]."-".$dt[0];

	return $data;
}

function data_print ($data){
	$dt = date ("d/m/Y", strtotime ($data));
	echo $dt;
}

function hora_print ($data){
	$hr = date ("H:i", strtotime ($data));
	echo $hr;
}
function float_sql ($numero) {
	$numero = preg_replace ("/,/", ".", $numero);
	$numero = preg_replace ("/ /", "", $numero);
	return $numero;
}
function money_sql ($numero) {
	$numero = preg_replace ("/./", ",", $numero);
	$numero = preg_replace ("/,/", ".", $numero);
	$numero = preg_replace ("/ /", "", $numero);
	$numero = preg_replace ("/R$/", "", $numero);
	return $numero;
}

function money_print ($numero){
	$numero = number_format ($numero, 2, ",", ".");
	echo $numero;
}


function trata($valor){
	$valor = trim($valor);
	$valor = strip_tags($valor);
	$valor = addslashes($valor);
	return $valor;
}

function nl2p( $str ) {
	$str = str_replace( array("\r\n", "\r"), "\n", $str );
	return "<p>\n" . str_replace( "\n", "\n</p>\n<p>\n", $str ) . "\n</p>";
}

function nl2li( $str ) {
	$str = str_replace( array("\r\n", "\r"), "\n", $str );
	return "<li>\n" . str_replace( "\n", "\n</li>\n<li>\n", $str ) . "\n</li>";
}

function texto_link($string)
{
	$string = preg_replace ("/'/", "", $string);

	$string = preg_replace ('/"/', '', $string);

	$string = trata ($string);


	$string = preg_replace ('/ /', '-', $string);

	return $string;
}


function sql ($query, $conexao = ''){
	
	// Se não recebeu conexão, usa a global
	if(!$conexao){
		global $con;
		if(!$con){
			// Fallback: incluir conexão se global também não existe
			include(dirname(__FILE__)."/conexao8.php");
		}
		$conexao = $con;
	}

	$sql = mysqli_query ($conexao, $query) or die (mysqli_error($conexao));

	return $sql;
}

// função para fazer o upload da imagem e gravá-lo no banco de dados
function upload_imagem($imagem, $tamanho, $pasta, $bg = ''){

	$extensao = strtolower(end(explode('.', $imagem['name'])));

	if ($extensao == 'jpg' or $extensao == 'jpeg'){
		$img = imagecreatefromjpeg ($imagem['tmp_name']);
	} elseif ($extensao == 'png'){
		$img = imagecreatefrompng ($imagem['tmp_name']);
	} elseif ($extensao == 'gif'){
		$img = imagecreatefromgif($imagem['tmp_name']);
	}



	$x = imagesx ($img);
	$y = imagesy($img);



	// se a imagem é formato vertical
	if ($x <= $y){

		if ($y > $tamanho){
			$largura = ($tamanho * $x)/$y;
			$altura = $tamanho;

		} else {
			$largura = $x;
			$altura = $y;
		}

	} else {
	// se é formato horizontal
		if ($x > $tamanho){
			$altura = ($tamanho * $y)/$x;
			$largura = $tamanho;

		} else {
			$largura = $x;
			$altura = $y;
		}
	}



	$nova = imagecreatetruecolor ($largura, $altura);

	if (!empty($bg)){
		$cor = hex2rgb($bg);
		// deixar na cor padrão
		$backgroundColor = imagecolorallocate($nova, $cor[0], $cor[1], $cor[2]);
	} else {
		// deixar fundo branco
		$backgroundColor = imagecolorallocate($nova, 255, 255, 255);
	}

	imagefill($nova, 0, 0, $backgroundColor);


	$nome_temp = substr ($imagem['name'], 0, -4);
	if (strlen ($nome_temp) > 50){
		$nome_temp = substr ($nome_temp, 0, 50);
	}

	$nome = txt_web ($nome_temp);


	if(file_exists("$pasta"."$nome".".jpg")) {

		$nome = txt_web ($nome).substr(md5(uniqid()), 0, 6)."_".date("ymdhis");
	}

	$nome = $nome.".jpg";

	imagecopyresampled ($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	imagejpeg($nova, "$pasta"."$nome", 95);
	chmod($pasta.$nome, 0644);

	imagedestroy($img);
	imagedestroy($nova);

	return $nome;
}

// função para fazer o upload da imagem que retorna o tamanho final da imagem
function upload_img($imagem, $tamanho, $pasta){

	$extensao = strtolower(end(explode('.', $imagem['name'])));



	if ($extensao == 'jpg' or $extensao == 'jpeg'){
		$img = imagecreatefromjpeg ($imagem['tmp_name']);
	} elseif ($extensao == 'png'){
		$img = imagecreatefrompng ($imagem['tmp_name']);
	} elseif ($extensao == 'gif'){
		$img = imagecreatefromgif($imagem['tmp_name']);
	}



	$x = imagesx ($img);
	$y = imagesy($img);



	// se a imagem é formato vertical
	if ($x <= $y){

		if ($y > $tamanho){
			$largura = ($tamanho * $x)/$y;
			$altura = $tamanho;

		} else {
			$largura = $x;
			$altura = $y;
		}

	} else {
	// se é formato horizontal
		if ($x > $tamanho){
			$altura = ($tamanho * $y)/$x;
			$largura = $tamanho;

		} else {
			$largura = $x;
			$altura = $y;
		}
	}



	$nova = imagecreatetruecolor ($largura, $altura);

	// deixar fundo branco
	$backgroundColor = imagecolorallocate($nova, 255, 255, 255);
	imagefill($nova, 0, 0, $backgroundColor);


	$nome_temp = substr ($imagem['name'], 0, -4);
	if (strlen ($nome_temp) > 50){
		$nome_temp = substr ($nome_temp, 0, 50);
	}

	$nome = txt_web ($nome_temp);


	if(file_exists("$pasta"."$nome".".jpg")) {
		$nome = txt_web ($nome_temp)."_".date("ymdhis");
	}

	$nome = $nome.".jpg";

	imagecopyresampled ($nova, $img, 0, 0, 0, 0, $largura, $altura, $x, $y);

	imagejpeg($nova, "$pasta"."$nome", 95);


	imagedestroy($img);
	imagedestroy($nova);

	$img_final['nome'] = $nome;
	$img_final['w'] = $largura;
	$img_final['h'] = $altura;

	chmod($pasta.$nome, 0644);
	return $img_final;
}


function upload_arquivo($arquivo, $pasta){

	$extensao = strtolower(end(explode('.', $arquivo['name'])));

	$nome_temp = substr ($arquivo['name'], 0, -4);
	if (strlen ($nome_temp) > 50){
		$nome_temp = substr ($nome_temp, 0, 50);
	}

	$nome = txt_web ($nome_temp);

	if(file_exists("$pasta/$nome".".".$extensao)) {
		$nome = txt_web ($nome).substr(md5(uniqid()), 0, 6)."_".date("ymdhis");
	}

	$nome = $nome.".".$extensao;

	move_uploaded_file ($arquivo['tmp_name'], $pasta.$nome);

	chmod($pasta.$nome, 0644);

	return $nome;

}


function redimensionar ($max_altura, $max_largura, $local_img) {
	// redimensionar foto
	$source = $local_img;

	$imnfo = getimagesize($source);
	$img_w = $imnfo[0];       // largura
	$img_h = $imnfo[1];       // altura
	$img_f = $imnfo[2];       // extensão
	$img_m = $imnfo['mime']; // mime-type

	$altura = $max_altura;
	$largura = $max_largura;
	$x = $img_w;
	$y = $img_h;

	// se a imagem é formato vertical
	if ($x <= $y){

		if ($y > $altura){
			$largura = ($altura * $x)/$y;

		} else {
			$largura = $x;
			$altura = $y;
		}

	} else {
	// se é formato horizontal

		if ($x > $largura){
			$altura = ($largura * $y)/$x;

		} else {
			$largura = $x;
			$altura = $y;
		}
	}
	$imagem['altura'] = round ($altura);
	$imagem['largura'] = round ($largura);
	return $imagem;
}


function tamanho_imagem ($local_img) {
	// pegar loca foto
	$source = $local_img;

	$imnfo = @getimagesize($source);
	$imagem['largura'] = $imnfo[0];       // largura
	$imagem['altura'] = $imnfo[1];       // altura
	$imagem['extensao'] = $imnfo[2];       // extensão
	$imagem['mime'] = $imnfo['mime']; // mime-type

	return $imagem;
}


function diffDate($d1, $d2, $type='', $sep='-'){
	$d1 = explode($sep, $d1);
	$d2 = explode($sep, $d2);

	switch ($type){
		case 'A':
		$X = 31536000;
		break;

		case 'M':
		$X = 2592000;
		break;

		case 'D':
		$X = 86400;
		break;

		case 'H':
		$X = 3600;
		break;

		case 'MI':
		$X = 60;
		break;

		default:
		$X = 1;
		}
		$dt2 = mktime(0, 0, 0, $d2[1], $d2[2], $d2[0]);
		$dt1 = mktime(0, 0, 0, $d1[1], $d1[2], $d1[0]);
		$dif = $dt2 - $dt1;
		return $dif/$X;
}


function resumir ($txt, $char){
	$tamanho = strlen ($txt);
	if ($tamanho > $char){
		$txt = substr ($txt, 0, $char)." [...]";
	}

	return ($txt);
}

function resumir2 ($txt, $char){
	$tamanho = strlen ($txt);
	if ($tamanho > $char){

		$txt_s = substr ($txt, $char, 10);

		$loc = strpos ($txt_s, " ", 0);


		$txt = substr ($txt, 0, $char+$loc)." [...]";
	}

	return ($txt);
}

function resumir3 ($txt, $char){
	$txt = trata($txt);
	$tamanho = strlen ($txt);
	if ($tamanho > $char){

		$txt_s = substr ($txt, $char, 10);

		$loc = strpos ($txt_s, " ", 0);


		$txt = substr ($txt, 0, $char+$loc)." [...]";
	}

	return ($txt);
}



function limpa_string($string)
{
    $string = get_magic_quotes_gpc() ? stripslashes($string) : $string;

    $string = function_exists("mysql_real_escape_string") ? mysql_real_escape_string($string) : mysql_escape_string($string);

    return $string;
}

// mais simples
function limpa_html($txt){
	$text = preg_replace('/\sclass=[\'|"][^\'"]+[\'|"]/', '', $txt);
	$text2 = preg_replace('/\sstyle=[\'|"][^\'"]+[\'|"]/', '', $text);
	$text3 = str_replace("<div>","",$text2);
	$text4 = str_replace("</div>","<br>",$text3);
	$text5 = strip_tags($text4, '<p><a><br><b><strong><i><em><u><img><iframe>');
	//return addslashes ($text5);
	return $text5;
}

// completo
function limpa_html2($txt){
	$text = preg_replace('/\sclass=[\'|"][^\'"]+[\'|"]/', '', $txt);
	$text2 = preg_replace('/\sstyle=[\'|"][^\'"]+[\'|"]/', '', $text);
	/*$text3 = ereg_replace("<div>","",$text2);
	$text4 = ereg_replace("</div>","<br>",$text3);*/
	$text5 = strip_tags($text2, '<p><a><br><b><strong><i><em><table><tr><td><th><thead><tbody><hr><u><img><s><caption>');
	//return addslashes ($text5);
	return $text5;
}

function txt_web($string) {
	$frase = RemoveSpecialChar($string);
	$frase = strtolower ($frase);
	return $frase;
};

function todas_maiusculas($term) {
    $palavra = strtr(strtoupper($term),"àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ","ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß");
    return $palavra;
}

function todas_minusculas($term) {
    $palavra = strtr(strtolower($term),"ÀÁÂÃÄÅÆÇÈÉÊËÌÍÎÏÐÑÒÓÔÕÖ×ØÙÜÚÞß","àáâãäåæçèéêëìíîïðñòóôõö÷øùüúþÿ");
    return $palavra;
}

function RemoveSpecialChar($str)
{
    $res = preg_replace('/[0-9\@\.\;\" "]+/', '', $str);
    return $res;
}

function remove_acentos($string){
	// Variavel recebendo a string a ser tratada
	$var = $string;
	// Variavel recebendo a string que não será tratada para futura comparação
	$ant = $var;
	// Variavel recebendo a string já fazendo as substituições
	$var = RemoveSpecialChar($var);

	return $var;
}


function backvars(){
	$haystack = $_SERVER['HTTP_REFERER'];
	$offset = 0;
	$needle = ".php";
    $length = strlen($haystack);
    $offset = ($offset > 0)?($length - $offset):abs($offset);
    $pos = strpos(strrev($haystack), strrev($needle), $offset);
	$neg = $pos*-1;
	if ($pos === 0){
		return "";
	} elseif (substr($haystack, $neg, 1) != "?" ){
		return "";
	} else {

		$resp = strrpos($haystack, "resp=");
		if ($resp === false) {
			 // not found...
			 return substr($haystack, $neg+1);
		} else {
			$ini = strrpos($haystack, "resp=");
			$fim = strrpos($haystack, "=ok");
			$plus = 3;
			if ($fim === false){
				$fim = strrpos($haystack, "=erro");
				$plus = 5;
			}
			$remo = substr ($haystack, $ini, ($fim-$ini+$plus));
			$comercial = '&';
			$link = str_replace ($remo, "", $haystack);
			$pos = strpos ($link, 'php?');
			$veri = substr ($link, $pos+4, 1);

			if (ctype_alnum($veri)){

				$length = strlen($link);
				$offset = ($offset > 0)?($length - $offset):abs($offset);
				$pos = strpos(strrev($link), strrev($needle), $offset);
				$neg = $pos*-1;
				if ($pos === 0){
					return "";
				} elseif (substr($link, $neg, 1) != "?" ){
					return "";
				} else {
					 return substr($link, $neg+1);
				}

			} else {
				$total = strlen ($link);
				$comeco = substr ($link, 0, $pos+4);
				$final = substr ($link, $pos+5, $total);
				$link = $comeco.$final;

				$length = strlen($link);
				$offset = ($offset > 0)?($length - $offset):abs($offset);
				$pos = strpos(strrev($link), strrev($needle), $offset);
				$neg = $pos*-1;
				if ($pos === 0){
					return "";
				} elseif (substr($link, $neg, 1) != "?" ){
					return "";
				} else {
					 return substr($link, $neg+1);
				}
			}
		}
	}
}


function volta ($tipo, $msg, $url){

	$opt = backvars();

	$m = base64_encode($msg);

	if (!empty($opt)){
		$o = "&".$opt;
	}


	$r = "resp=1&msg=".$m."&tipo=".$tipo.$o;

	if (@!header ("Location: ".$url."?".$r)){
		echo '<meta http-equiv="refresh" content="0;URL='.$url.'?'.$r.'" /> ';
	}

	exit();

}


function resposta ($resp, $msg, $tipo){
	if ($resp == 1){
		$m = base64_decode($msg);
		if ($tipo == "erro"){
			echo '
			<div class="alert alert-danger"> <strong>Erro!</strong> '.$m.' </div>';
		} else {
			echo '
			<div class="alert alert-success"> <strong></strong> '.$m.' </div>';
		}
	} else {
		return false;
	}
}

function resp ($resp = null, $msg = null, $tipo = null){
	// Verificar se os parâmetros foram fornecidos
	if (empty($resp) || empty($msg) || empty($tipo)){
		return false;
	}
	
	if ($resp == 1){
		$m = base64_decode($msg);
		if ($tipo == "erro"){
			echo '
			<div class="alert alert-danger"> <i class="fas fa-times-circle"></i> <strong>Erro!</strong> '.$m.' </div>';
		} elseif($tipo == "alerta"){
			echo '
			<div class="alert alert-warning"> <i class="fas fa-exclamation-circle"></i> <strong>Atenção!</strong> '.$m.' </div>';
		} else {
			echo '
			<div class="alert alert-success"> <i class="fas fa-check-circle"></i> <strong>Sucesso!</strong> '.$m.' </div>';
		}
	} else {
		return false;
	}
}

// Função que valida o CPF
function validaCPF($cpf){

	// Verifiva se o número digitado contém todos os digitos
    $cpf = str_pad(preg_replace('/[^0-9]/', '', $cpf), 11, '0', STR_PAD_LEFT);

	// Verifica se nenhuma das sequências abaixo foi digitada, caso seja, retorna falso
    if (strlen($cpf) != 11 || $cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' || $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999')
	{
	return false;
    }
	else
	{   // Calcula os números para verificar se o CPF é verdadeiro
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }

            $d = ((10 * $d) % 11) % 10;

            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }
}

function Randomizar($iv_len)
{
    $iv = '';
    while ($iv_len-- > 0) {
        $iv .= chr(mt_rand() & 0xff);
    }
    return $iv;
}

function encriptar($texto, $iv_len = 16)
{
	$senha = '<P)*v_mm';
    $texto .= "\x13";
    $n = strlen($texto);
    if ($n % 16) $texto .= str_repeat("\0", 16 - ($n % 16));
    $i = 0;
    $Enc_Texto = Randomizar($iv_len);
    $iv = substr($senha ^ $Enc_Texto, 0, 512);
    while ($i < $n) {
        $Bloco = substr($texto, $i, 16) ^ pack('H*', md5($iv));
        $Enc_Texto .= $Bloco;
        $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
        $i += 16;
    }
    return base64_encode($Enc_Texto);
}

function desencriptar($Enc_Texto, $iv_len = 16)
{
	$senha = '<P)*v_mm';
    $Enc_Texto = base64_decode($Enc_Texto);
    $n = strlen($Enc_Texto);
    $i = $iv_len;
    $texto = '';
    $iv = substr($senha ^ substr($Enc_Texto, 0, $iv_len), 0, 512);
    while ($i < $n) {
        $Bloco = substr($Enc_Texto, $i, 16);
        $texto .= $Bloco ^ pack('H*', md5($iv));
        $iv = substr($Bloco . $iv, 0, 512) ^ $senha;
        $i += 16;
    }
    return preg_replace('/\\x13\\x00*$/', '', $texto);
}

function redirecionar($url, $tempo)
{
    $url = str_replace('&amp;', '&', $url);

    if($tempo > 0)
    {
        header("Refresh: $tempo; URL=$url");
    }
    else
    {
        @ob_flush();
        @ob_end_clean();
        header("Location: $url");
        exit;
    }
}

// busca endereço por cep e retorna um array
function busca_cep($cep){
	$resultado = @file_get_contents('http://republicavirtual.com.br/web_cep.php?cep='.urlencode($cep).'&formato=query_string');
	if(!$resultado){
		$resultado = "&resultado=0&resultado_txt=erro+ao+buscar+cep";
	}
	parse_str($resultado, $retorno);
	return $retorno;
}

function servidor_atual(){
	// pegano url do servidor
	$protocolo    = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
	$host         = $_SERVER['HTTP_HOST'];
	$UrlAtual     = $protocolo . '://' . $host;
	return $UrlAtual;
}

function url_atual(){
	// pegano url do servidor
	$protocolo    = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
	$host         = $_SERVER['HTTP_HOST'];
	$script       = $_SERVER['SCRIPT_NAME'];
	$parametros   = $_SERVER['QUERY_STRING'];
	$UrlAtual     = $protocolo . '://' . $host . $script;
	return $UrlAtual;
}

function url_atual2(){
	// pegano url do servidor
	$protocolo    = (strpos(strtolower($_SERVER['SERVER_PROTOCOL']),'https') === false) ? 'http' : 'https';
	$host         = $_SERVER['HTTP_HOST'];
	$script       = $_SERVER['SCRIPT_NAME'];
	$parametros   = $_SERVER['QUERY_STRING'];
	$UrlAtual     = $protocolo . '://' . $host . $script."?".$parametros;
	return $UrlAtual;
}
/*####### FUNCOES YOUTUBE */

function valida_youtube($link){
	if(preg_match('/youtube.com\\/watch\\?.*v=.*$/',$link)){
		preg_match('/(?<=\\?v=)[^&]*/',$link,$matches,PREG_OFFSET_CAPTURE);
		$ch = curl_init("http://youtube.com/v/{$matches[0][0]}"); curl_exec($ch);
		$i = curl_getinfo($ch);
		return ($i["http_code"] != "404");
	}
	return false;
}

function id_youtube ($endereco){

	if (valida_youtube($endereco)){

		$ini = strpos ($endereco, 'v=');
		$v = substr ($endereco, ($ini+2) , 11);
		return ($v);

	} else {
		return ("Endereço Youtube inválido!");
	}

}

function exibe_youtube($url, $w, $h){

	$v = id_youtube ($url);
	$l = "http://youtube.com/v/".$v;

	echo '
	<!--[if !IE]> -->
	<object type="application/x-shockwave-flash" data="'.$l.'" width="'.$w.'" height="'.$h.'">
	<!-- <![endif]--><!--[if IE]>
	<object classid="clsid:D27CDB6E-AE6D-11cf-96B8-444553540000" codebase="http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,0,0" width="'.$w.'" height="'.$h.'">
	<param name="movie" value="'.$l.'" />
	<!--><!--dgx--><param name="loop" value="true" /><param name="menu" value="false" />
	<p>Seu navegador não suporta o vídeo.</p></object>
	<!-- <![endif]-->
	';
}
function img_g_youtube($url){
	$v = id_youtube ($url);
	return 'http://i1.ytimg.com/vi/'.$v.'/hqdefault.jpg';

}
function img_m_youtube($url){
	$v = id_youtube ($url);
	return 'http://i1.ytimg.com/vi/'.$v.'/mqdefault.jpg';

}
function img_p_youtube($url){
	$v = id_youtube ($url);
	return 'http://i1.ytimg.com/vi/'.$v.'/default.jpg';

}

/*####### FIM FUNCOES YOUTUBE */

function tim($url_img, $w, $h, $path_tim = ''){
	if ($url_img != "imgs/" && file_exists($url_img)){
		echo '<img src="'.$path_tim.'app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'"  width="'.$w.'" height="'.$h.'" />';
	}
}

function timsub($url_img, $w, $h, $subs){
	if ($url_img != "imgs/" && file_exists($url_img)){
		echo '<img src="app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'"  width="'.$w.'" height="'.$h.'" />';
	} else {
		echo '<img src="'.$subs.'" />';
	}
}

// mini de loojar virtuais
function tim3($url_img, $w, $h){
	if ($url_img != "imgs/" && file_exists($url_img)){
		echo '<img src="app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'&amp;zc=2&amp;q=90" width="'.$w.'" height="'.$h.'"  />';
	}
}

function tim4($url_img, $w, $h){
	if ($url_img != "imgs/" && file_exists($url_img)){
		echo '<img src="app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'&amp;zc=3&amp;q=90" width="'.$w.'" height="'.$h.'"  />';
	}

}

function tim_banner($url_img, $w, $h, $link){
	if ($url_img != "imgs/" && file_exists($url_img)){
		if (!empty($link)){ echo '<a href="'.$link.'">'; }
		echo '<img src="app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'"  width="'.$w.'" height="'.$h.'" '.$link.' />';
		if (!empty($link)){ echo '</a>'; }
	}

}

function tim_w($url_img, $w, $path_tim){
	if ($url_img != "imgs/" && file_exists($url_img)){
		$tam = tamanho_imagem ($url_img);
		 if ($tam['largura'] > $w){
			 echo '<img src="'.$path_tim.'app/timthumb.php?src='.$url_img.'&amp;w='.$w.'" />';

		 } else {
			 echo '<img src="'.$url_img.'" />';

		 }
	}
}

function tim2($url_img, $w, $h, $path_tim, $h_box){

	$tam = tamanho_imagem ($url_img);
	 if ($tam['altura'] >= $tam['largura']){
		 // eh vertical
		 $var_tt = 'h='.$h;
		 $padding = 0;

	 } else {
		 $var_tt = 'w='.$w;
		 $alt = round(($tam['altura']*$w)/$tam['largura']);
		 $padding = floor(($h_box-$alt)/2);

	 }
	echo '<img src="'.$path_tim.'app/timthumb.php?src='.$url_img.'&amp;'.$var_tt.'" alt="'.$url_img.'" style="padding: '.$padding.'px 0px;" />';
}

function getIp(){

    if (!empty($_SERVER['HTTP_CLIENT_IP'])) {

        $ip = $_SERVER['HTTP_CLIENT_IP'];

    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {

        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];

    } else {

        $ip = $_SERVER['REMOTE_ADDR'];

    }

    return $ip;

}

function img($url_img, $w, $h = '', $path_tim = ''){
	if ($url_img != "imgs/" && file_exists($url_img)){

		if (!empty($h) && is_numeric($h)){
			echo '<img src="'.$path_tim.'app/timthumb.php?src='.$url_img.'&amp;w='.$w.'&amp;h='.$h.'" />';
		} else {
			$tam = tamanho_imagem ($url_img);
			 if ($tam['largura'] > $w){
				 echo '<img src="'.$path_tim.'app/timthumb.php?src='.$url_img.'&amp;w='.$w.'" />';
			 } else {
				 echo '<img src="'.$url_img.'" />';
			 }
		}



	} else {
		$l= 64; // largura da imagem indisponivel
		$a = 23; // altura da imagem indisponivel
		$borderH = floor(($w - $l)/2);
		$borderV = floor(($h - $a)/2);
		echo '<img src="imgs/indisponivel.gif" width="'.$l.'" height="'.$a.'" style="border: solid #fff; border-width: '.$borderV.'px '.$borderH.'px;" />';
	}
}


function libera_ip($ip_liberado){
	$ipCliente = getIp();

	if ($ip_liberado <> $ipCliente){
		die("SEU IP NÃO ESTÁ LIBERADO PARA ACESSAR ESTE SITE");
	}
}


function AtualizaOrdem($tabela, $colIdTabela, $colOrdem, $optColuna = '', $optValor = 0, $conexao = ''){
	/*
	$tabela - tabela do BD a ser atualizada;
	$colIdTabela - nome da coluna principal ID KEY AUTO INCREMENT da $tabela
	$colOrdem - nome da coluna responsavel pela ordenacao . ex. numero_tabela
	$optColuna - nome da coluna para restringir a ordenacao - exemplo: id_album
	$optValor - valor para busca e restrição no $optColuna
	*/

	if(!$conexao){
		include(getRoot()."app/conexao8.php");
		$conexao = $con;
	}


	if (!empty($optColuna) && !empty($optValor) && is_numeric($optValor) && $optValor > 0){
		$opt = "WHERE ".$optColuna." = ".$optValor;
	}

	$sql = sql ("SELECT * FROM ".$tabela."  ".$opt." ORDER BY ".$colOrdem." ASC, ".$colIdTabela." DESC", $conexao);
	$c = 1;
	while ($dados = mysqli_fetch_assoc($sql)){
		sql ("UPDATE ".$tabela." SET ".$colOrdem." = $c WHERE ".$colIdTabela." = '".$dados["$colIdTabela"]."'", $conexao);
		$c++;
	}


}

function hex2rgb($hex) {
   $hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);
   //return implode(",", $rgb); // returns the rgb values separated by commas
   return $rgb; // returns an array with the rgb values
}

function PretoBranco($hex){
	$hex = str_replace("#", "", $hex);

   if(strlen($hex) == 3) {
      $r = hexdec(substr($hex,0,1).substr($hex,0,1));
      $g = hexdec(substr($hex,1,1).substr($hex,1,1));
      $b = hexdec(substr($hex,2,1).substr($hex,2,1));
   } else {
      $r = hexdec(substr($hex,0,2));
      $g = hexdec(substr($hex,2,2));
      $b = hexdec(substr($hex,4,2));
   }
   $rgb = array($r, $g, $b);

   /*
   if (obscuridade<(255+255+255)/2)
   colortexto="#ffffff"
else
   colortexto="#000000"
   */

   $contraste = $r + $g + $b;

   if ($contraste < ((255+255+255)/2)){
	   $cortexto="#ffffff";
   } else {
	   $cortexto="#000000";
   }
   echo '<div style="width: 200px; height: 200px; border: 1px solid #ccc; background-color: '.$hex.';">
<p style="text-align: center; margin-top: 20px; color: '.$cortexto.';">Fonte</p>';

}


/* creates a compressed zip file */
function create_zip($files = array(),$destination = '',$overwrite = false) {
	//if the zip file already exists and overwrite is false, return false
	if(file_exists($destination) && !$overwrite) { return false; }
	//vars
	$valid_files = array();
	//if files were passed in...
	if(is_array($files)) {
		//cycle through each file
		foreach($files as $file) {
			//make sure the file exists
			if(file_exists($file)) {
				$valid_files[] = $file;
			}
		}
	}
	//if we have good files...
	if(count($valid_files)) {
		//create the archive
		$zip = new ZipArchive();
		if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) !== true) {
			return false;
		}
		//add the files
		foreach($valid_files as $file) {
			$zip->addFile($file,$file);
		}
		//debug
		//echo 'The zip archive contains ',$zip->numFiles,' files with a status of ',$zip->status;

		//close the zip -- done!
		$zip->close();

		//check to make sure the file exists
		return file_exists($destination);
	}
	else
	{
		return false;
	}

/***** Example Usage
$files=array('file1.jpg', 'file2.jpg', 'file3.gif');
create_zip($files, 'myzipfile.zip', true);
***/
}

function verimg2($img, $path = "../imgs/"){
	if (!empty($img) && file_exists($path.$img)){ return true; } else { return false; }
}

function data2_print ($data){
	$dt = date ("m.d.Y", strtotime ($data));	
	echo $dt;
}

function br2p($data) {
	$data = preg_replace('/<br\s*\/?>/i', '<br/>', $data);
    $data = preg_replace('#(?:<br\s*/?>\s*?){2,}#', '</p><p>', $data);
    return "<p>$data</p>";
}

function listaVantagens ($texto) {
	$texto = preg_replace('/<p>/i', '', $texto);
	$texto = preg_replace('/<\/p>/i', "/n", $texto);
	$texto = preg_replace('/<br\s*\/?>/i', "/n", $texto);
	$texto = preg_replace('/•/', '', $texto);
	$texto = preg_replace('/\/n/', '</p><p>', $texto);
	return "<p>".$texto."</p>";
}

function registraLog($id_usu, $ponto, $saldo, $descricao, $pathConexao = "../../app/"){
    global $con;
    
    if(!$con){
        include_once($pathConexao."conexao8.php");
    }
	
    if ($ponto > 0 or $ponto < 0){
        $sql = sql("INSERT INTO `log` (`usu_log`, `ponto_log`, `saldo_log`, `data_log`, `descricao_log`)
        VALUES ('$id_usu', '$ponto', '$saldo', CURRENT_TIMESTAMP, '$descricao');", $con);

        if($sql){
            return true;
        } else {
            return false;
        }        
    }
}

/**
 * Calcula o valor do complemento em R$ baseado no tipo de kaizen
 * 
 * @deprecated v2.1 - Esta função não é mais usada. Valores são informados diretamente pelo usuário.
 *             Mantida apenas para compatibilidade com kaizens antigos.
 * 
 * @param int $tipo_id - ID do tipo de kaizen
 * @param float $valor - Valor informado pelo usuário (horas, kg ou reais)
 * @param mysqli $con - Conexão com banco de dados
 * @return float - Valor calculado em R$ (sempre retorna 0 na v2.1)
 */
function calcularComplemento($tipo_id, $valor, $con = null){
    // NOTA: Função deprecada - valores agora são informados diretamente
    // Retorna 0 para forçar uso de valor_original_kaizen
    return 0;
    
    /* CÓDIGO ORIGINAL COMENTADO - NÃO DELETAR (histórico)
    if(!$con){
        global $con;
    }
    
    if(empty($valor) || $valor <= 0){
        return 0;
    }
    
    // Buscar tipo e complemento
    $sqlTipo = sql("SELECT complemento_tipo FROM tipo WHERE id_tipo = '".$tipo_id."' LIMIT 1", $con);
    if(mysqli_num_rows($sqlTipo) == 0){
        return 0;
    }
    $tipo = mysqli_fetch_array($sqlTipo);
    $complemento_id = $tipo['complemento_tipo'];
    
    // Se não tem complemento (Segurança), retorna 0
    if($complemento_id == 0){
        return 0;
    }
    
    // Buscar configuração do complemento
    $sqlComp = sql("SELECT calculo_comp FROM comp WHERE id_comp = '".$complemento_id."' LIMIT 1", $con);
    if(mysqli_num_rows($sqlComp) == 0){
        return 0;
    }
    $comp = mysqli_fetch_array($sqlComp);
    $calculo = $comp['calculo_comp'];
    
    // Se não precisa calcular, retorna valor direto
    if($calculo == 0){
        return floatval($valor);
    }
    
    // Buscar variáveis de configuração (pg id=101)
    $sqlPg = sql("SELECT nome4_pg, nome5_pg, nome6_pg FROM pg WHERE id_pg = 101 LIMIT 1", $con);
    if(mysqli_num_rows($sqlPg) == 0){
        return 0;
    }
    $config = mysqli_fetch_array($sqlPg);
    
    // Cálculo 1: kg carbono × (Produtividade USD × Câmbio USD)
    if($calculo == 1){
        $produtividade = floatval($config['nome5_pg']); // USD por kg
        $cambio = floatval($config['nome6_pg']); // Câmbio
        return floatval($valor) * ($produtividade * $cambio);
    }
    
    // Cálculo 2: horas × Custo Linha parada
    if($calculo == 2){
        $custo_linha = floatval($config['nome4_pg']); // R$ por hora
        return floatval($valor) * $custo_linha;
    }
    
    return 0;
    FIM DO CÓDIGO ORIGINAL COMENTADO */
}

/**
 * Retorna a unidade de medida baseada no tipo de complemento
 * @param int $comp_id - ID do complemento (1, 2 ou 3)
 * @return string - 'R$', 'horas' ou ''
 */
function getUnidadeComplemento($comp_id) {
    switch($comp_id) {
        case 1:
        case 2:
            return 'R$';
        case 3:
            return 'horas';
        default:
            return '';
    }
}

/**
 * Formata valor para exibição baseado no tipo de complemento
 * @param float $valor - Valor a ser formatado
 * @param int $comp_id - ID do complemento (1, 2 ou 3)
 * @return string - Valor formatado com unidade
 */
function formatarValorComplemento($valor, $comp_id) {
    if(empty($valor) || $valor <= 0) {
        return '-';
    }
    
    if($comp_id == 1 || $comp_id == 2) {
        // Reais: formato 1.234,56
        return 'R$ ' . number_format($valor, 2, ',', '.');
    } elseif($comp_id == 3) {
        // Horas: formato 1.234 (sem decimais)
        return number_format($valor, 0, ',', '.') . ' horas';
    }
    
    return '-';
}

/**
 * Faz upload de múltiplos anexos para um kaizen
 * @param array $files - Array $_FILES['anexos']
 * @param int $id_kaizen - ID do kaizen
 * @param mysqli $con - Conexão com banco de dados
 * @return array - ['success' => bool, 'message' => string, 'count' => int]
 */
function uploadAnexos($files, $id_kaizen, $con = null){
    if(!$con){
        global $con;
    }
    
    $result = ['success' => true, 'message' => '', 'count' => 0];
    
    // Verificar se há arquivos
    if(empty($files['name'][0])){
        return $result;
    }
    
    // Extensões permitidas
    $extensoes_permitidas = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'zip', 'rar'];
    
    // Tamanho máximo por arquivo: 10MB
    $tamanho_max = 10 * 1024 * 1024;
    
    // Loop nos arquivos
    $total_files = count($files['name']);
    for($i = 0; $i < $total_files; $i++){
        
        // Verificar se arquivo foi enviado
        if(empty($files['name'][$i]) || $files['error'][$i] != 0){
            continue;
        }
        
        $nome_original = $files['name'][$i];
        $tamanho = $files['size'][$i];
        $tmp_name = $files['tmp_name'][$i];
        
        // Verificar tamanho
        if($tamanho > $tamanho_max){
            $result['success'] = false;
            $result['message'] .= "Arquivo '{$nome_original}' muito grande (máx 10MB). ";
            continue;
        }
        
        // Extrair extensão
        $ext = strtolower(pathinfo($nome_original, PATHINFO_EXTENSION));
        
        // Validar extensão
        if(!in_array($ext, $extensoes_permitidas)){
            $result['success'] = false;
            $result['message'] .= "Extensão .{$ext} não permitida. ";
            continue;
        }
        
        // Gerar nome único
        $nome_arquivo = time() . "_" . $i . "_" . uniqid() . "." . $ext;
        $destino = "../imgs/kaizen/" . $nome_arquivo;
        
        // Fazer upload
        if(move_uploaded_file($tmp_name, $destino)){
            
            // Inserir no banco
            $sql = sql("INSERT INTO kaizen_anexos 
                (id_kaizen, nome_arquivo, nome_original, tipo_arquivo, tamanho_arquivo, data_upload) 
                VALUES 
                ('".$id_kaizen."', '".$nome_arquivo."', '".mysqli_real_escape_string($con, $nome_original)."', '".$ext."', '".$tamanho."', NOW())", $con);
            
            if($sql){
                $result['count']++;
            } else {
                $result['success'] = false;
                $result['message'] .= "Erro ao registrar '{$nome_original}'. ";
            }
            
        } else {
            $result['success'] = false;
            $result['message'] .= "Erro ao fazer upload de '{$nome_original}'. ";
        }
    }
    
    if($result['count'] > 0){
        $result['message'] = $result['count'] . " arquivo(s) enviado(s) com sucesso. " . $result['message'];
    }
    
    return $result;
}

/**
 * Lista todos os anexos de um kaizen
 * @param int $id_kaizen - ID do kaizen
 * @param mysqli $con - Conexão com banco de dados
 * @return array - Array de anexos
 */
function listarAnexos($id_kaizen, $con = null){
    if(!$con){
        global $con;
    }
    
    $anexos = [];
    
    // Verifica se a tabela kaizen_anexos existe
    $table_check = mysqli_query($con, "SHOW TABLES LIKE 'kaizen_anexos'");
    
    if (mysqli_num_rows($table_check) > 0) {
        $sql = sql("SELECT * FROM kaizen_anexos WHERE id_kaizen = '".$id_kaizen."' ORDER BY data_upload ASC", $con);
        
        while($anexo = mysqli_fetch_assoc($sql)){
            $anexos[] = $anexo;
        }
    }
    
    return $anexos;
}

?>

