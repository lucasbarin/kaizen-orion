<meta charset="utf-8"/>
<?
$sufixo = "colaborador";
$ordenador = "numero";

// tipos de input
$tipo[ 1 ] = "text";
$tipo[ 2 ] = "select";
$tipo[ 3 ] = "textarea";
$tipo[ 4 ] = "file";
$tipo[ 5 ] = "radio";
$tipo[ 6 ] = "checkbox";
$tipo[ 7 ] = "data";



/*
required: 0 = não / 1 = sim
tamanho caracteres / padrão = 100
mascara js: numeros data cpf cnpj money , padrão : nada
editor "wysihtml5" ou "ckeditor" ou ""


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
*/


include_once("includes/".$_GET['config'].".php");



$num_campos = sizeof( $campo );

$form_editar = '
<form action="app/func_'.$sufixo.'_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  <input name="id_'.$sufixo.'" type="hidden" id="id_teste" value="<? echo $dados[\'id_'.$sufixo.'\']  ?>">
  <div class="form-body">
';
$form_inserir = '
<form action="app/func_'.$sufixo.'_inserir.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  
  <div class="form-body">
';

for ( $i = 0; $i <= $num_campos; $i++ ) {
	
	$label = $campo[ $i ][ 'label' ];
	$nome = $campo[ $i ][ 'nome' ];
	if ( $campo[ $i ][ 'required' ] == true ) {
		$required = "required";
		$requiredmark = '<span class="required"> * </span>';
	}
	
	if ( $campo[ $i ][ 'mascara' ] <> '' ) {
		$mascara = $campo[ $i ][ 'mascara' ];
	}
	
	if ( $campo[ $i ][ 'editor' ] <> '' ) {
		$editor = $campo[ $i ][ 'editor' ];
	}
	
	if ( $campo[ $i ][ 'arquivo' ] <> '' ) {
		if ( $campo[ $i ][ 'arquivo' ] == 'imagem' ) {
			$arquivo = 'imagem';
		} else {
			$arquivo = 'file';
		}
		
	} else {
		$arquivo = 'file';
	}
	
	if ( $campo[ $i ][ 'dica' ] <> '' ) {
		$dica = '<span class="help-block"> '.$campo[ $i ][ 'dica' ].' </span>';
	}
	
	if ( $campo[ $i ][ 'tamanho' ] > 0 ) {
		$tamanho = $campo[ $i ][ 'tamanho' ];
	} else {
		$tamanho = $campo[ $i ][ 'tamanho' ];
	}
	
	
	if ( $campo[ $i ][ 'tipo' ] == 1 ) {
		// input
		$bloco = '
	<div class="form-group">
		<label class="control-label col-md-3">' . $label . $requiredmark . '</label>
		<div class="col-md-4">
			<input name="'.$nome.'_' . $sufixo . '" type="text" ' . $required . ' class="form-control" id="'.$nome.'_' . $sufixo . '" value="<? echo $dados[\''.$nome.'_' . $sufixo . '\']  ?>" maxlength="' . $tamanho . '"/>
		'.$dica.'
		</div>
	</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
	} // fim campo tipo 1
	
	
	
	
	
	if ( $campo[ $i ][ 'tipo' ] == 2 ) {
		// select
		$bloco = '
	<div class="form-group">
	  <label class="control-label col-md-3"> '.$label. $requiredmark .' </label>
	  <div class="col-md-4">
		<select class="form-control select2me '.$mascara.'" name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" required>
		  <option <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == "") { echo \'selected="selected"\'; } ?> value="">Selecione</option>
		</select>
		'.$dica.'
	  </div>
	</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		$codigo_editar='';
		$codigo_inserir='';
		
	} // fim campo tipo 2
	
	
	if ( $campo[ $i ][ 'tipo' ] == 3 ) {
		// textarea
		$bloco = '
		<div class="form-group">
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-9">
			<textarea class="form-control '.$editor.'" rows="6" name="'.$nome.'_'.$sufixo.'" data-error-container="#'.$nome.'_'.$sufixo.'_error" id="'.$nome.'_'.$sufixo.'"><? echo $dados[\''.$nome.'_'.$sufixo.'\'] ?></textarea>
			<div id="'.$nome.'_'.$sufixo.'_error"> </div>
			'.$dica.'
		  </div>
		</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		$codigo_editar.='$'.$nome.'_'.$sufixo.' = limpa_html2($_POST[\''.$nome.'_'.$sufixo.'\']);
		';
		$codigo_inserir.='$'.$nome.'_'.$sufixo.' = limpa_html2($_POST[\''.$nome.'_'.$sufixo.'\']);
		';
		
		
	} // fim campo tipo 3
	
		if ( $campo[ $i ][ 'tipo' ] == 4 ) {
		// file/arquivos
		$bloco = '
		<span id="arquivo'.$i.'">
		<input name="'.$nome.'_antigo" type="hidden" id="'.$nome.'_antigo" value="<? echo $dados[\''.$nome.'_'.$sufixo.'\']  ?>">
			<input name="altera_'.$nome.'" type="hidden" id="altera_'.$nome.'" value="1">
			<div class="form-group imgmanter">
			  <div class="col-md-3 align-dir">
				<label class="control-label"> '.$label.'</label>
				<br>
				<button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
			  </div>
			  <div class="col-md-4"><br>
				<br>
				<? if (empty($dados[\''.$nome.'_'.$sufixo.'\']) or !file_exists("../imgs/".$dados[\''.$nome.'_'.$sufixo.'\'])){
				  echo \'<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>\';
			  } else {
				   echo \'<p>\'.$dados[\''.$nome.'_'.$sufixo.'\'].\' <a href="../imgs/\'.$dados[\''.$nome.'_'.$sufixo.'\'].\'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>\';
			  }
			   ?>

			  </div>
			</div>
			<div class="form-group imgtrocar">
			  <label class="control-label col-md-3">'.$label. $requiredmark.'  </label>
			  <div class="col-md-4">
				<input type="file" name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" class="form-control"  >
				<button type="button" class="btn blue btmanter">Manter arquivo atual</button>
				'.$dica.'
			  </div>
			</div>
		</span>
		';
		$form_editar.= $bloco;
		
			
		$bloco2 = '
		<div class="form-group">
			<label class="control-label col-md-3">'.$label. $requiredmark.'</label>
			<div class="col-md-4">
			  <input type="file" name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" class="form-control" >
			  '.$dica.'
			</div>
		  </div>
		';	
		$form_inserir.= $bloco2;
	} // fim campo tipo 4
	
	
	if ( $campo[ $i ][ 'tipo' ] == 5 ) {
		// radio
		$bloco = '	
		<div class="form-group">
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-4">
			<div class="radio-list" data-error-container="#'.$nome.'_'.$sufixo.'_error">
			  <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="1" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] <> 1){ echo \'checked="checked"\'; } ?> />
				Sim </label>
			  <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="2" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == 1){ echo \'checked="checked"\'; } ?> />
				Não </label>
			</div>
			<div id="'.$nome.'_'.$sufixo.'_error"> </div>
		  </div>
		</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
	} // fim campo tipo 5
	
	
	if ( $campo[ $i ][ 'tipo' ] == 6 ) {
		// checkbox
		$bloco = '
		<div class="form-group">
		<?
		$c = 1;
		$v = ereg_replace (", ", ",", $dados[\''.$nome.'_'.$sufixo.'\']); $var = explode (",", $v);
		?>
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-4">
			<div class="checkbox-list" data-error-container="#'.$nome.'_'.$sufixo.'_error">
			  <label>
				<input type="checkbox" value="1" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'1" <? $atual = 1; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 1 </label>
			  <label>
				<input type="checkbox" value="2" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'2" <? $atual = 2; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 2 </label>
			  <label>
				<input type="checkbox" value="3" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'3" <? $atual = 3; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 3 </label>
			</div>
			<span class="help-block"> (Selecione pelo menos um) </span>
			<div id="'.$nome.'_'.$sufixo.'_error"> </div>
		  </div>
		</div>
		

		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
	} // fim campo tipo 6
	
		
	if ( $campo[ $i ][ 'tipo' ] == 7 ) {
		// textarea
		$bloco = '
		
	<div class="form-group">
	  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
	  <div class="col-md-4">
		<div class="input-group '.$mascara.' date-picker" data-date-format="dd/mm/yyyy">
		  <input type="text" class="form-control" readonly name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" required value="<? if ($dados[\''.$nome.'_'.$sufixo.'\'] != \'0000-00-00\'){ echo date("d/m/Y", strtotime ($dados[\''.$nome.'_'.$sufixo.'\'])); }  ?>"  >
		  <span class="input-group-btn">
		  <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
		  </span></div>
		<!-- /input-group --> 
		'.$dica.'</div>
	</div>';
		
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
	} // fim campo tipo 3
		unset( $label, $required, $tamanho, $requiredmark, $mascara, $editor, $dica);
} // fim for


$bloco= '
</div>
  <div class="form-actions">
	<div class="row">
	  <div class="col-md-offset-3 col-md-9">
		<button id="btsubmit" type="submit" class="btn green">Enviar</button>
		<img src="assets/global/img/loading-spinner-grey.gif" alt="" width="22" height="22" id="loader"/></div>
	</div>
  </div>
</form>';
$form_editar.= $bloco;
$form_inserir.= $bloco;

?>
<pre>
FORM EDITAR:
<textarea name="textarea" id="textarea" cols="45" rows="5" style="width: 100%; height: 500px;">
<? echo $form_editar; ?>
</textarea>
<br><br><br>
FORM INSERIR:<br>
<textarea name="textarea2" id="textarea2" cols="45" rows="5" style="width: 100%; height: 500px;">
<? echo $form_inserir; ?>
</textarea>
</pre>
<script src="../assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script> 
<script src="../assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script> 
<script>
	$(document).ready(function(){
		
		$(function() {
		 $('textarea').click(function() {
		 $(this).focus();
		 $(this).select();
		 document.execCommand('copy');
		 $(this).after();
		   alert("Copied to clipboard");
		 });
		});
		
	});

</script>
