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

$form_editar = '
<form action="app/func_'.$sufixo.'_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  <input name="id_'.$sufixo.'" type="hidden" id="id_'.$sufixo.'" value="<? echo $dados[\'id_'.$sufixo.'\']  ?>">
  <div class="form-body">
';
$form_inserir = '
<form action="app/func_'.$sufixo.'_inserir.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  
  <div class="form-body">
';

$trata_editar = "";
$codigo_editar_value = "";

$trata_inserir = "";

$codigo_inserir_into = "";
$codigo_inserir_value = "";

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
	
	if ($campo[ $i ][ 'arquivo' ] == "imagem"){
		$arquivo = "imagem" ;
		if (is_numeric ($campo[ $i ][ 'comprimento' ]) && $campo[ $i ][ 'comprimento' ] > 0){
			$comprimento = $campo[ $i ][ 'comprimento' ];
		} else {
			$comprimento = 2000;
		}
	} else { 
		$arquivo = "" ;
	}
	
	if (!empty($campo[ $i ][ 'opcaonome' ]) && !empty($campo[ $i ][ 'opcaotabela' ]) && !empty($campo[ $i ][ 'opcaoordem' ])){
		$opcaonome = $campo[ $i ][ 'opcaonome' ];
		$opcaotabela = $campo[ $i ][ 'opcaotabela' ];
		$opcaoordem = $campo[ $i ][ 'opcaoordem' ];
		$opcaodinamica = true;
	}
	
	
	
	
	if ( $campo[ $i ][ 'tipo' ] == 1 ) {
		// input
		$bloco = '
	<div class="form-group">
		<label class="control-label col-md-3">' . $label . $requiredmark . '</label>
		<div class="col-md-4">
			<input name="'.$nome.'_' . $sufixo . '" type="text" ' . $required . ' class="form-control '.$mascara.'" id="'.$nome.'_' . $sufixo . '" value="<? echo $dados[\''.$nome.'_' . $sufixo . '\']  ?>" maxlength="' . $tamanho . '"/>
		'.$dica.'
		</div>
	</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= "";
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= "";

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
		
	} // fim campo tipo 1
	
	
	
	
	
	if ( $campo[ $i ][ 'tipo' ] == 2 ) {
		// option
		
		// opções dinamicas?
		if ($opcaodinamica){
			
			$opt = '
			<?
			$sqloption = sql("SELECT * FROM '.$opcaotabela.' ORDER BY '.$opcaoordem.'", $con);
			if (mysqli_num_rows($sqloption)){
				?><option <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == "") { echo \'selected="selected"\'; } ?> value="">Selecione</option><?
				while ($dadosopt = mysqli_fetch_assoc($sqloption)){
					?>
					<option <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == $dadosopt[\'id_'.$opcaotabela.'\']) { echo \'selected="selected"\'; } ?> value="<? echo $dadosopt[\'id_'.$opcaotabela.'\'] ?>"><? echo $dadosopt[\''.$opcaonome.'\'] ?></option>
					<?
				}
			} else {
				?><option <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == "") { echo \'selected="selected"\'; } ?> value="">Selecione</option><?
			}
			
			?>
			';
			
		} else {
			$opt = ' <option <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == "") { echo \'selected="selected"\'; } ?> value="">Selecione</option>';
		}
		
		// select
		$bloco = '
	<div class="form-group">
	  <label class="control-label col-md-3"> '.$label. $requiredmark .' </label>
	  <div class="col-md-4">
		<select class="form-control select2me '.$mascara.'" name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" '.$required.'>
		
		 '.$opt.'
		  
		</select>
		'.$dica.'
	  </div>
	</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= "";
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= "";

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
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
		
		
		// inicio insert code
		$trata_editar.= '$'.$nome.'_'.$sufixo.' = limpa_html($_POST[\''.$nome.'_'.$sufixo.'\']);
		';

		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= '$'.$nome.'_'.$sufixo.' = limpa_html($_POST[\''.$nome.'_'.$sufixo.'\']);
		';

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
		
		
		
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
			
			
		// inicio insert code
			
		if ($arquivo == 'imagem'){
			// processamento e upload de imagem
			$trata_editar.= '$'.$nome.' = $_FILES[\''.$nome.'_'.$sufixo.'\'];
			
			if ($_POST[\'altera_'.$nome.'\'] == 2){
				if (!empty ($'.$nome.'[\'type\'])){
					if ($'.$nome.'[\'type\'] == "image/jpeg" or $'.$nome.'[\'type\'] == "image/jpg" or $'.$nome.'[\'type\'] == "image/pjpeg" or $'.$nome.'[\'type\'] == "image/gif" or $'.$nome.'[\'type\'] == "image/png"){

						$'.$nome.'_'.$sufixo.' = upload_imagem($'.$nome.', '.$comprimento.', "../../imgs/");

					} else {
						volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../'.$nome.'-editar.php");
					}
				} else {
					$'.$nome.'_'.$sufixo.' = "";
				}
			} else {
				$'.$nome.'_'.$sufixo.' = $_POST[\''.$nome.'_antigo\'];
			}
			';
		} else {
			// processamento e upload de arquivo normal
			$trata_editar.= '$'.$nome.' = $_FILES[\''.$nome.'_'.$sufixo.'\'];
			
			if ($_POST[\'altera_'.$nome.'\'] == 2){
				if (!empty ($'.$nome.'[\'type\'])){
					
					$'.$nome.'_'.$sufixo.' = upload_arquivo($'.$nome.', "../../imgs/");
	
				} else {
					$'.$nome.'_'.$sufixo.' = "";
				}
			} else {
				$'.$nome.'_'.$sufixo.' = $_POST[\''.$nome.'_antigo\'];
			}
			';
			
		}
			
		

		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		if ($arquivo == 'imagem'){
			// processamento e upload de imagem
			$trata_inserir.= '$'.$nome.' = $_FILES[\''.$nome.'_'.$sufixo.'\'];
			
				if (!empty ($'.$nome.'[\'type\'])){
					if ($'.$nome.'[\'type\'] == "image/jpeg" or $'.$nome.'[\'type\'] == "image/jpg" or $'.$nome.'[\'type\'] == "image/pjpeg" or $'.$nome.'[\'type\'] == "image/gif" or $'.$nome.'[\'type\'] == "image/png"){

						$'.$nome.'_'.$sufixo.' = upload_imagem($'.$nome.', '.$comprimento.', "../../imgs/");

					} else {
						volta ("erro", "Somente imagens no formato JPG, PNG ou GIF são aceitas!", "../'.$nome.'-inserir.php");
					}
				} else {
					$'.$nome.'_'.$sufixo.' = "";
				}
			
			';
		} else {
			// processamento e upload de arquivo normal
			$trata_inserir.= '$'.$nome.' = $_FILES[\''.$nome.'_'.$sufixo.'\'];
			
			
				if (!empty ($'.$nome.'[\'type\'])){
					
					$'.$nome.'_'.$sufixo.' = upload_arquivo($'.$nome.', "../../imgs/");
	
				} else {
					$'.$nome.'_'.$sufixo.' = "";
				}
			
			';
			
		}
		
		

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
		
		
	} // fim campo tipo 4
	
	
	if ( $campo[ $i ][ 'tipo' ] == 5 ) {
		// radio
		
		// opções dinamicas?
		if ($opcaodinamica){
			
			$opt = '
			<?
			$sqloption = sql("SELECT * FROM '.$opcaotabela.' ORDER BY '.$opcaoordem.'", $con);
			if (mysqli_num_rows($sqloption)){
				while ($dadosopt = mysqli_fetch_assoc($sqloption)){
				?>
					<label>
					<input type="radio" name="'.$nome.'_'.$sufixo.'" value="<? echo $dadosopt[\'id_'.$opcaotabela.'\'] ?>" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == $dadosopt[\'id_'.$opcaotabela.'\']){ echo \'checked="checked"\'; } ?> />
					<? echo $dadosopt[\''.$opcaonome.'\'] ?> </label>
				<?
				
				}
			} else {
				?>
			  <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="1" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] <> 1){ echo \'checked="checked"\'; } ?> />
				Sim </label>
			  <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="2" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == 1){ echo \'checked="checked"\'; } ?> />
				Não </label>
				<?
			}
			
			?>
			';
			
		} else {
			$opt = '
			 <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="1" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] <> 1){ echo \'checked="checked"\'; } ?> />
				Sim </label>
			  <label>
				<input type="radio" name="'.$nome.'_'.$sufixo.'" value="2" required <? if ($dados[\''.$nome.'_'.$sufixo.'\'] == 1){ echo \'checked="checked"\'; } ?> />
				Não </label>
			';
		}
		
		
		$bloco = '	
		<div class="form-group">
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-4">
			<div class="radio-list" data-error-container="#'.$nome.'_'.$sufixo.'_error">
			 '.$opt.'
			</div>
			<div id="'.$nome.'_'.$sufixo.'_error"> </div>
		  </div>
		</div>
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= "";
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= "";

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
	} // fim campo tipo 5
	
	
	if ( $campo[ $i ][ 'tipo' ] == 6 ) {
		// checkbox
		
		// opções dinamicas?
		if ($opcaodinamica){
			
			$opt = '
			<?
			$sqloption = sql("SELECT * FROM '.$opcaotabela.' ORDER BY '.$opcaoordem.'", $con);
			if (mysqli_num_rows($sqloption)){
				while ($dadosopt = mysqli_fetch_assoc($sqloption)){
					?>
					<label>				
					<input type="checkbox" value="<? echo $dadosopt[\'id_'.$opcaotabela.'\'] ?>" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'<? echo $dadosopt[\'id_'.$opcaotabela.'\'] ?>" <? $atual = $dadosopt[\'id_'.$opcaotabela.'\']; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
					<? echo $dadosopt[\''.$opcaonome.'\'] ?> </label>
					<?
				
				}
			} else {
				?>
			  <label>
				<input type="checkbox" value="1" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'1" <? $atual = 1; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 1 </label>
			  <label>
				<input type="checkbox" value="2" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'2" <? $atual = 2; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 2 </label>
			  <label>
				<input type="checkbox" value="3" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'3" <? $atual = 3; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 3 </label>
				<?
			}
			
			?>
			';
			
		} else {
			$opt = '
			 <label>
				<input type="checkbox" value="1" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'1" <? $atual = 1; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 1 </label>
			  <label>
				<input type="checkbox" value="2" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'2" <? $atual = 2; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 2 </label>
			  <label>
				<input type="checkbox" value="3" name="'.$nome.'_'.$sufixo.'[]" id="'.$nome.'_'.$sufixo.'3" <? $atual = 3; if(in_array($atual, $var)){ echo \'checked="checked"\';	} else { echo \'\'; } ?> />
				Service 3 </label>
			';
		}
		
		
		$bloco = '
		<div class="form-group">
		<?
		$c = 1;
		$v = ereg_replace (", ", ",", $dados[\''.$nome.'_'.$sufixo.'\']); $var = explode (",", $v);
		?>
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-4">
			<div class="checkbox-list" data-error-container="#'.$nome.'_'.$sufixo.'_error">
			
			'.$opt.'
			
			  
			</div>
			<span class="help-block"> (Selecione pelo menos um) </span>
			<div id="'.$nome.'_'.$sufixo.'_error"> </div>
		  </div>
		</div>
		

		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		
		// inicio insert code
		$trata_editar.= '
		// checkbox
		$check = "";
		$num = sizeof ($_POST[\''.$nome.'_'.$sufixo.'\']);
		for ($z = 0; $z < $num; $z++) {
		  $check.= $_POST[\''.$nome.'_'.$sufixo.'\'][$z] . ", ";
		}
		$'.$nome.'_'.$sufixo.' = $check;
		if (!empty($'.$nome.'_'.$sufixo.')){  $'.$nome.'_'.$sufixo.' = substr($'.$nome.'_'.$sufixo.', 0, -2); }
		';
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= '
		// checkbox
		$check = "";
		$num = sizeof ($_POST[\''.$nome.'_'.$sufixo.'\']);
		for ($z = 0; $z < $num; $z++) {
		  $check.= $_POST[\''.$nome.'_'.$sufixo.'\'][$z] . ", ";
		}
		$'.$nome.'_'.$sufixo.' = $check;
		if (!empty($'.$nome.'_'.$sufixo.')){  $'.$nome.'_'.$sufixo.' = substr($'.$nome.'_'.$sufixo.', 0, -2); }
		';

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
	} // fim campo tipo 6
	
		
	if ( $campo[ $i ][ 'tipo' ] == 7 ) {
		// data
		$bloco = '
				<div class="form-group">
                  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
                  <div class="col-md-4">
                    <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                      <input type="text" class="form-control" readonly name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" '.$required.' value="<? if ($dados[\''.$nome.'_'.$sufixo.'\'] != \'0000-00-00\'){ echo date("d/m/Y", strtotime ($dados[\''.$nome.'_'.$sufixo.'\'])); }  ?>"  >
                      <span class="input-group-btn">
                      <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                      </span></div>
                    <!-- /input-group --> 
                    <span class="help-block"> selecione a data </span></div>
                </div>	
	';
		
		$form_editar.= $bloco;
		
		
		$bloco = '
		<div class="form-group">
                    <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
                    <div class="col-md-4">
                      <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                        <input type="text" class="form-control" readonly name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" '.$required.'>
                        <span class="input-group-btn">
                          <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                        </span></div>
                      <!-- /input-group -->
                      '.$dica.'
					  </div>
                  </div>
		';
		
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= '$'.$nome.'_'.$sufixo.' = data_sql ($_POST[\''.$nome.'_'.$sufixo.'\']);';
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= '$'.$nome.'_'.$sufixo.' = data_sql ($_POST[\''.$nome.'_'.$sufixo.'\']);';

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
	} // fim campo tipo 7
	
	
	if ( $campo[ $i ][ 'tipo' ] == 8 ) {
		// money
		$bloco = '
		 <div class="form-group">
		  <label class="control-label col-md-3">'.$label. $requiredmark .'</label>
		  <div class="col-md-4">
			<div class="input-icon right"> <i class="fa"></i>
			  <input type="text" class="form-control money" name="'.$nome.'_'.$sufixo.'" id="'.$nome.'_'.$sufixo.'" '.$required.' value="<? echo number_format ($dados[\''.$nome.'_'.$sufixo.'\'], 2, ",","") ;  ?>" />
			  '.$dica.'
			</div>
		  </div>
		</div>
		
	';
		
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= '$'.$nome.'_'.$sufixo.' = float_sql ($_POST[\''.$nome.'_'.$sufixo.'\']);';
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= '$'.$nome.'_'.$sufixo.' = float_sql ($_POST[\''.$nome.'_'.$sufixo.'\']);';

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
	} // fim campo tipo 8
    
    
    
    	
	if ( $campo[ $i ][ 'tipo' ] == 9 ) {
		// color
		$bloco = '
    <div class="form-group">
      <label class="control-label col-md-3">' . $label . $requiredmark . '</label>
      <div class="col-md-4">
        <div class="input-group colorpicker-component demo demo-auto">
          <input name="'.$nome.'_'.$sufixo.'" type="text" class="form-control" ' . $required . ' id="'.$nome.'_'.$sufixo.'" value="<? echo $dados[\''.$nome.'_' . $sufixo . '\']  ?>"  maxlength="' . $tamanho . '" />'.$dica.'
          <span class="input-group-addon"><i></i></span></div>
      </div>
    </div>    
		';
		$form_editar.= $bloco;
		$form_inserir.= $bloco;
		
		// inicio insert code
		$trata_editar.= "";
		$codigo_editar_value.= '`'.$nome.'_' . $sufixo.'` = \'$'.$nome.'_' . $sufixo.'\',';

		$trata_inserir.= "";

		$codigo_inserir_into.= '`'.$nome.'_' . $sufixo.'`,';
		$codigo_inserir_value.= '\'$'.$nome.'_' . $sufixo.'\',';
		
		
	} // fim campo tipo 9
	
	
	
	
	unset( $label, $required, $tamanho, $requiredmark, $mascara, $editor, $dica, $comprimento, $arquivo, $z, $check, $opcaonome, $opcaotabela, $opcaoordem, $opcaodinamica, $opt);
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


// finalizar e organizar os codigos finais

if (!empty($ordenador)){
    // codteste
    if ($adicional_atualiza_campo){
        $add1 = ' AND '.$adicional_atualiza_campo.'_'.$sufixo.' = $'.$adicional_atualiza_campo.'_'.$sufixo;
        $add2 = ', "'.$adicional_atualiza_campo.'_'.$sufixo.'", $'.$adicional_atualiza_campo.'_'.$sufixo;
        $add3 = '$'.$adicional_atualiza_campo.'_'.$sufixo.' = $dados_'.$sufixo.'[\''.$adicional_atualiza_campo.'_'.$sufixo.'\'];';
    }
	$ordem1 = '
	if (empty($'.$ordenador.'_'.$sufixo.') or $numero_'.$sufixo.' < 1){
		$'.$ordenador.'_'.$sufixo.' = 99999999;
	}
	
	if (!empty($'.$ordenador.'_'.$sufixo.') && $numero_'.$sufixo.' != 99999999){
		$sql = sql ("UPDATE `'.$sufixo.'` SET '.$ordenador.'_'.$sufixo.' = ('.$ordenador.'_'.$sufixo.' + 1) WHERE '.$ordenador.'_'.$sufixo.' >= \'$'.$ordenador.'_'.$sufixo.'\' '.$add1.'");
	}
	';
	$ordem2 = '
	AtualizaOrdem("'.$sufixo.'", "id_'.$sufixo.'", "'.$ordenador.'_'.$sufixo.'"'.$add2.');
	';
	
}





$formulario_inserir = '
<?
$sufixo = "'.$sufixo.'";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

include("_top_inserir.php"); ?>
'.$form_inserir.'
<?
include("_bot_inserir.php");
?>
';



$formulario_editar = '
<?
$sufixo = "'.$sufixo.'";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM '.$sufixo.' ORDER BY id_'.$sufixo.' ASC");

$id_'.$sufixo.' = trata($_GET[\'id_'.$sufixo.'\']);

if (!empty ($id_'.$sufixo.') && is_numeric ($id_'.$sufixo.')){
	$sql = mysqli_query ($con, "SELECT * FROM '.$sufixo.' WHERE id_'.$sufixo.' = ".$id_'.$sufixo.'." LIMIT 1") or die (mysqli_error($con));
	
	if(mysqli_num_rows ($sql) > 0){
		$dados = mysqli_fetch_array ($sql);
		
	} else {
		volta ("erro", "Registro não encontrado!", "'.$sufixo.'-admin.php");
	} 
	
} else {
	volta ("erro", "Registro não encontrado!", "'.$sufixo.'-admin.php");
}

include("_top_editar.php"); ?>
'.$form_editar.'
<?
include("_bot_editar.php");
?>
';



// ordenar admin pela data ou numero
if ($orddata){
    $or = '<span style="width: 1px; font-size: 1px; color: transparent;"><? echo date("Ymd", strtotime ($dados[\'data_'.$sufixo.'\'])); ?></span> <? echo date("d/m/Y", strtotime ($dados[\'data_'.$sufixo.'\'])); ?>';
    $orTit = 'Data';
    $ordJs = '$ord = "2d";';
} else {
   $or = '<? echo str_pad($dados[\'numero_'.$sufixo.'\'], 2, "0", STR_PAD_LEFT) ?>º';
    $orTit = 'Ordem';
    $ordJs = '';
}

$form_admin = '
            <form action="app/func_'.$sufixo.'_apagar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
            <table class="table table-striped table-bordered table-hover" id="sample_1">
              <thead>
                <tr>
                  <th width="7%" class="table-checkbox"> <input type="checkbox" class="group-checkable" data-set="#sample_1 .checkboxes"/>
                  </th>
                  <th width="14%"> Nome </th>
                  <th width="14%"> '.$orTit.' </th>

                  <th width="15%">Ação</th>
                </tr>
              </thead>
              <tbody>
              	<?
                while ($dados = mysqli_fetch_assoc($sql)){
				?>
                <tr class="odd gradeX">
                  <td><input type="checkbox" class="checkboxes" value="<? echo $dados[\'id_'.$sufixo.'\']; ?>" name="ids_'.$sufixo.'[]"/></td>
                  <td> <? echo $dados[\'nome_'.$sufixo.'\'] ?> </td>
 
                  <td>
				  '.$or.'
				  </td>
                 
                  <td><a href="'.$sufixo.'-editar.php?id_'.$sufixo.'=<? echo $dados[\'id_'.$sufixo.'\']; ?>" class="btn btn-xs yellow"> Editar <i class="fa fa-edit"></i> </a> <a href="app/func_'.$sufixo.'_apagar.php?id_'.$sufixo.'=<? echo $dados[\'id_'.$sufixo.'\']; ?>" class="btn btn-xs red btconfirm"> <i class="fa fa-times"></i> Excluir </a> <!--<a class="btn btn-xs green bt_adicionar btconfirm"> Adicionar <i class="fa fa-plus"></i> </a>--></td>
                </tr>
                <?
				} // fim while
				?>
              </tbody>
            </table>
            <br class="clearfix">
            <input name="select_del" type="hidden" id="select_del" value="1">
            <button id="btsubmit" type="submit" class="btn red btconfirm2 float-esq">Apagar selecionados</button>
            </form>

';

$formulario_admin = '
<?
$sufixo = "'.$sufixo.'";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM '.$sufixo.' ORDER BY id_'.$sufixo.' ASC");
'.$ordJs.'
include("_top_admin.php"); ?>
'.$form_admin.'
<?
include("_bot_admin.php");
?>
';





$codigo_editar_value = substr($codigo_editar_value, 0, -1);
$codigo_inserir_into = substr($codigo_inserir_into, 0, -1);
$codigo_inserir_value = substr($codigo_inserir_value, 0, -1);


$codigo_inserir = '<?
ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

if ( $_POST ) {

	foreach ( $_POST as $k => $v ) {
		$$k = trata( $v );
	}

'.$trata_inserir.'
'.$codigo_adicional_inserir.'
'.$ordem1.'

	$sql = sql ("
	INSERT INTO `'.$sufixo.'` ('.$codigo_inserir_into.')
	VALUES ('.$codigo_inserir_value.')
	");
	

if ($sql){
		'.$ordem2.'
		volta ("ok", "Registro alterado com sucesso!", "../'.$sufixo.'-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../'.$sufixo.'-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../'.$sufixo.'-inserir.php");
}
ob_end_flush();

?>
' ;


$codigo_editar = '<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

if(!empty ($_POST[\'id_'.$sufixo.'\'])){

	foreach( $_POST as $k => $v ){
		$$k = trata( $v );
	}
	
	
'.$trata_editar.'
'.$codigo_adicional_editar.'
'.$ordem1.'

	$sql = sql ("
	UPDATE `'.$sufixo.'` SET
	'.$codigo_editar_value.'
	WHERE `id_'.$sufixo.'` = \'$id_'.$sufixo.'\' LIMIT 1
	");
	
if ($sql){
		'.$ordem2.'
		volta ("ok", "Registro alterado com sucesso!", "../'.$sufixo.'-admin.php");
	} else {
		volta ("erro", "Erro ao alterar registro! Tente novamente mais tarde!", "../'.$sufixo.'-admin.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../'.$sufixo.'-editar.php");
}
ob_end_flush();
?>
' ;


$codigo_apagar = '
<?
ob_start();
@session_start();
include ("../../app/funcoes8.php");
include ("../../app/conexao8.php");

$volta = "pagina=1";

if(!empty($_GET[\'id_'.$sufixo.'\']) && is_numeric($_GET[\'id_'.$sufixo.'\'])){
	
	$id_'.$sufixo.' = $_GET[\'id_'.$sufixo.'\'];

	
	$sql_'.$sufixo.' = sql ("SELECT * FROM '.$sufixo.' WHERE id_'.$sufixo.' = \'$id_'.$sufixo.'\'", $con);
	$dados_'.$sufixo.' = mysqli_fetch_array ($sql_'.$sufixo.');
	
    '.$add3.'
    
	$sql = sql ("DELETE FROM '.$sufixo.' WHERE id_'.$sufixo.' = \'$id_'.$sufixo.'\' LIMIT 1", $con);
	
	if ($sql){
		@unlink ("../../imgs/".$dados_'.$sufixo.'[\'imagem_'.$sufixo.'\']);
		@unlink ("../../imgs/".$dados_'.$sufixo.'[\'pdf_'.$sufixo.'\']);
		'.$ordem2.'
		volta ("ok", "Registro excluído com sucesso!", "../'.$sufixo.'-admin.php");
	} else {
		volta ("erro", "Erro ao excluir o registro! Tente novamente mais tarde!", "../'.$sufixo.'-admin.php");
	}

} elseif($_POST[\'select_del\'] == 1){
	$ids = $_POST[\'ids_'.$sufixo.'\'];
	$num = sizeof ($ids);
	
	if ($num > 0){
		$c = 0;
		foreach ($ids as $id_'.$sufixo.') {
						
				$sql_'.$sufixo.' = sql ("SELECT * FROM '.$sufixo.' WHERE id_'.$sufixo.' = \'$id_'.$sufixo.'\'", $con);
				$dados_'.$sufixo.' = mysqli_fetch_array ($sql_'.$sufixo.');
				
                 '.$add3.'
                
				$sql = sql ("DELETE FROM '.$sufixo.' WHERE id_'.$sufixo.' = \'$id_'.$sufixo.'\' LIMIT 1", $con);
				
				if ($sql){
					$c++;
					@unlink ("../../imgs/".$dados_'.$sufixo.'[\'imagem_'.$sufixo.'\']);
					@unlink ("../../imgs/".$dados_'.$sufixo.'[\'pdf_'.$sufixo.'\']);
				}
						
		}
		
		if ($c > 0){
			if ($c > 1){
				$count = $c." Registros excluídos com sucesso!";
			} else {
				$count = "1 Registro excluído  com sucesso";
			}
		} else {
			$count = "0 Registros excluídos.";
		}
		
		if ($sql){
			'.$ordem2.'
			volta ("ok", $count, "../'.$sufixo.'-admin.php");
		} else {
			volta ("erro", "Erro ao excluir os registros! Tente novamente mais tarde!", "../'.$sufixo.'-admin.php");
		}
					
	} else {
		volta ("erro", "Selecione os registros a serem excluídos!", "../'.$sufixo.'-admin.php");
	}

	
} else {
	volta ("erro", "Selecione o registro a ser excluído!", "../'.$sufixo.'-admin.php");
}

?>
';



?>




<pre>

<?




?>


<strong>RESULTADO:</strong><br>
<?

if (!$ig_editar){
	$name = '../'.$sufixo.'-editar.php';
	$text = $formulario_editar;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text);
	fclose($file);
	if ($escreve){
		echo 'Formulário editar criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Formulário editar <br>';
	}
}

if (!$ig_inserir){
	$name = '../'.$sufixo.'-inserir.php';
	$text = $formulario_inserir;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text);
	fclose($file);
	if ($escreve){
		echo 'Formulário inserir criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Formulário inserir <br>';
	}
}


if (!$ig_admin){
	$name = '../'.$sufixo.'-admin.php';
	$text = $formulario_admin;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text);
	fclose($file);
	if ($escreve){
		echo 'Formulário admin criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Formulário admin <br>';
	}
}

if (!$ig_inserir_cod){
	$name = '../app/func_'.$sufixo.'_inserir.php';
	$text = $codigo_inserir;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text);
	fclose($file);
	if ($escreve){
		echo 'Código inserir criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Código inserir <br>';
	}
}

if (!$ig_editar_cod){
	$name = '../app/func_'.$sufixo.'_editar.php';
	$text = $codigo_editar;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text); 
	fclose($file);
	if ($escreve){
		echo 'Código editar criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Código editar <br>';
	}
}

if (!$ig_apagar_cod){
	$name = '../app/func_'.$sufixo.'_apagar.php';
	$text = $codigo_apagar;
	$file = fopen($name, 'w');
	$escreve = fwrite($file, $text); 
	fclose($file);
	if ($escreve){
		echo 'Código apagar criado com sucesso - '.$name.'<br>';
	} else {
		echo 'Erro ao criar Código apagar <br>';
	}
}

echo   '<br><br><strong><a href="index.php">VOLTAR PARA LISTA</a></strong>';
	
 // fim empty prefixo
?>
