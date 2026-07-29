
<?
$sufixo = "teste";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM teste ORDER BY id_teste ASC", $con);

$id_teste = trata($_GET['id_teste']);

if (!empty ($id_teste) && is_numeric ($id_teste)){
	$sql = mysqli_query($con, "SELECT * FROM teste WHERE id_teste = ".$id_teste." LIMIT 1") or die (mysqli_error($con));
	
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
		
	} else {
		volta ("erro", "Registro não encontrado!", "teste-admin.php");
	} 
	
} else {
	volta ("erro", "Registro não encontrado!", "teste-admin.php");
}

include("_top_editar.php"); ?>

<form action="app/func_teste_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  <input name="id_teste" type="hidden" id="id_teste" value="<? echo $dados['id_teste']  ?>">
  <div class="form-body">

	<div class="form-group">
		<label class="control-label col-md-3">Nome do colaborador<span class="required"> * </span></label>
		<div class="col-md-4">
			<input name="nome_teste" type="text" required class="form-control" id="nome_teste" value="<? echo $dados['nome_teste']  ?>" maxlength="100"/>
		
		</div>
	</div>
		
	<div class="form-group">
		<label class="control-label col-md-3">E-mail do colaborador</label>
		<div class="col-md-4">
			<input name="email_teste" type="text"  class="form-control" id="email_teste" value="<? echo $dados['email_teste']  ?>" maxlength="100"/>
		
		</div>
	</div>
		
	<div class="form-group">
		<label class="control-label col-md-3">Usuário<span class="required"> * </span></label>
		<div class="col-md-4">
			<input name="usuario_teste" type="text" required class="form-control" id="usuario_teste" value="<? echo $dados['usuario_teste']  ?>" maxlength="20"/>
		<span class="help-block"> (utilize apenas letras, números, _ e .) </span>
		</div>
	</div>
		
	<div class="form-group">
		<label class="control-label col-md-3">Senha <span class="required"> * </span></label>
		<div class="col-md-4">
			<input name="senha_teste" type="text" required class="form-control" id="senha_teste" value="<? echo $dados['senha_teste']  ?>" maxlength="20"/>
		<span class="help-block"> (utilize apenas letras, números, _ e .) </span>
		</div>
	</div>
		
	<div class="form-group">
	  <label class="control-label col-md-3"> Setor<span class="required"> * </span> </label>
	  <div class="col-md-4">
		<select class="form-control select2me " name="setor_teste" id="setor_teste" required>
		  <option <? if ($dados['setor_teste'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
		</select>
		
	  </div>
	</div>
		
	<div class="form-group">
	  <label class="control-label col-md-3"> Líder Kaizen? </label>
	  <div class="col-md-4">
		<select class="form-control select2me " name="lider_teste" id="lider_teste" required>
		  <option <? if ($dados['lider_teste'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
		</select>
		
	  </div>
	</div>
		
		<div class="form-group">
		  <label class="control-label col-md-3">Área de texto</label>
		  <div class="col-md-9">
			<textarea class="form-control ckeditor" rows="6" name="textarea_teste" data-error-container="#textarea_teste_error" id="textarea_teste"><? echo $dados['textarea_teste'] ?></textarea>
			<div id="textarea_teste_error"> </div>
			
		  </div>
		</div>
		
		<span id="arquivo7">
		<input name="imagem1_antigo" type="hidden" id="imagem1_antigo" value="<? echo $dados['imagem1_teste']  ?>">
			<input name="altera_imagem1" type="hidden" id="altera_imagem1" value="1">
			<div class="form-group imgmanter">
			  <div class="col-md-3 align-dir">
				<label class="control-label"> Arquivo Imagem</label>
				<br>
				<button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
			  </div>
			  <div class="col-md-4"><br>
				<br>
				<? if (empty($dados['imagem1_teste']) or !file_exists("../imgs/".$dados['imagem1_teste'])){
				  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
			  } else {
				   echo '<p>'.$dados['imagem1_teste'].' <a href="../imgs/'.$dados['imagem1_teste'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
			  }
			   ?>

			  </div>
			</div>
			<div class="form-group imgtrocar">
			  <label class="control-label col-md-3">Arquivo Imagem  </label>
			  <div class="col-md-4">
				<input type="file" name="imagem1_teste" id="imagem1_teste" class="form-control"  >
				<button type="button" class="btn blue btmanter">Manter arquivo atual</button>
				
			  </div>
			</div>
		</span>
		
		<span id="arquivo8">
		<input name="pdf1_antigo" type="hidden" id="pdf1_antigo" value="<? echo $dados['pdf1_teste']  ?>">
			<input name="altera_pdf1" type="hidden" id="altera_pdf1" value="1">
			<div class="form-group imgmanter">
			  <div class="col-md-3 align-dir">
				<label class="control-label"> Arquivo Geral</label>
				<br>
				<button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
			  </div>
			  <div class="col-md-4"><br>
				<br>
				<? if (empty($dados['pdf1_teste']) or !file_exists("../imgs/".$dados['pdf1_teste'])){
				  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
			  } else {
				   echo '<p>'.$dados['pdf1_teste'].' <a href="../imgs/'.$dados['pdf1_teste'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
			  }
			   ?>

			  </div>
			</div>
			<div class="form-group imgtrocar">
			  <label class="control-label col-md-3">Arquivo Geral  </label>
			  <div class="col-md-4">
				<input type="file" name="pdf1_teste" id="pdf1_teste" class="form-control"  >
				<button type="button" class="btn blue btmanter">Manter arquivo atual</button>
				<span class="help-block"> Insira arquivos com no máximo 5MB </span>
			  </div>
			</div>
		</span>
			
		<div class="form-group">
		  <label class="control-label col-md-3">Rádiooo</label>
		  <div class="col-md-4">
			<div class="radio-list" data-error-container="#radio_teste_error">
			  <label>
				<input type="radio" name="radio_teste" value="1" required <? if ($dados['radio_teste'] <> 1){ echo 'checked="checked"'; } ?> />
				Sim </label>
			  <label>
				<input type="radio" name="radio_teste" value="2" required <? if ($dados['radio_teste'] == 1){ echo 'checked="checked"'; } ?> />
				Não </label>
			</div>
			<div id="radio_teste_error"> </div>
		  </div>
		</div>
		
		<div class="form-group">
		<?
		$c = 1;
		$v = ereg_replace (", ", ",", $dados['ckbox_teste']); $var = explode (",", $v);
		?>
		  <label class="control-label col-md-3">Checkk Boxx</label>
		  <div class="col-md-4">
			<div class="checkbox-list" data-error-container="#ckbox_teste_error">
			  <label>
				<input type="checkbox" value="1" name="ckbox_teste[]" id="ckbox_teste1" <? $atual = 1; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 1 </label>
			  <label>
				<input type="checkbox" value="2" name="ckbox_teste[]" id="ckbox_teste2" <? $atual = 2; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 2 </label>
			  <label>
				<input type="checkbox" value="3" name="ckbox_teste[]" id="ckbox_teste3" <? $atual = 3; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 3 </label>
			</div>
			<span class="help-block"> (Selecione pelo menos um) </span>
			<div id="ckbox_teste_error"> </div>
		  </div>
		</div>
		

		
		
	<div class="form-group">
	  <label class="control-label col-md-3">Data de Hoje</label>
	  <div class="col-md-4">
		<div class="input-group  date-picker" data-date-format="dd/mm/yyyy">
		  <input type="text" class="form-control" readonly name="data_teste" id="data_teste" required value="<? if ($dados['data_teste'] != '0000-00-00'){ echo date("d/m/Y", strtotime ($dados['data_teste'])); }  ?>"  >
		  <span class="input-group-btn">
		  <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
		  </span></div>
		<!-- /input-group --> 
		<span class="help-block"> apenas datas futuras </span></div>
	</div>
		 <div class="form-group">
		  <label class="control-label col-md-3">Preço</label>
		  <div class="col-md-4">
			<div class="input-icon right"> <i class="fa"></i>
			  <input type="text" class="form-control money" name="preco_teste" id="preco_teste"  value="<? echo number_format ($dados['preco_teste'], 2, ",","") ;  ?>" />
			  <span class="help-block"> valor em reais </span>
			</div>
		  </div>
		</div>
		
	
</div>
  <div class="form-actions">
	<div class="row">
	  <div class="col-md-offset-3 col-md-9">
		<button id="btsubmit" type="submit" class="btn green">Enviar</button>
		<img src="assets/global/img/loading-spinner-grey.gif" alt="" width="22" height="22" id="loader"/></div>
	</div>
  </div>
</form>
<?
include("_bot_editar.php");
?>
