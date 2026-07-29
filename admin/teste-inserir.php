
<?
$sufixo = "teste";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

include("_top_inserir.php"); ?>

<form action="app/func_teste_inserir.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  
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
		
		<div class="form-group">
			<label class="control-label col-md-3">Arquivo Imagem</label>
			<div class="col-md-4">
			  <input type="file" name="imagem1_teste" id="imagem1_teste" class="form-control" >
			  
			</div>
		  </div>
		
		<div class="form-group">
			<label class="control-label col-md-3">Arquivo Geral</label>
			<div class="col-md-4">
			  <input type="file" name="pdf1_teste" id="pdf1_teste" class="form-control" >
			  <span class="help-block"> Insira arquivos com no máximo 5MB </span>
			</div>
		  </div>
			
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
include("_bot_inserir.php");
?>
