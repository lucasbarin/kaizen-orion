
<?
$sufixo = "tabela";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

include("_top_inserir.php"); ?>

<form action="app/func_tabela_inserir.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  
  <div class="form-body">

	<div class="form-group">
		<label class="control-label col-md-3">Nome do campo<span class="required"> * </span></label>
		<div class="col-md-4">
			<input name="nome_tabela" type="text" required class="form-control" id="nome_tabela" value="<? echo $dados['nome_tabela']  ?>" maxlength="100"/>
		
		</div>
	</div>
		
	<div class="form-group">
		<label class="control-label col-md-3">E-mail do campo</label>
		<div class="col-md-4">
			<input name="email_tabela" type="text"  class="form-control" id="email_tabela" value="<? echo $dados['email_tabela']  ?>" maxlength="100"/>
		
		</div>
	</div>
		
		<div class="form-group">
			<label class="control-label col-md-3">Arquivo Imagem</label>
			<div class="col-md-4">
			  <input type="file" name="imagem1_tabela" id="imagem1_tabela" class="form-control" >
			  
			</div>
		  </div>
		
		<div class="form-group">
			<label class="control-label col-md-3">Arquivo Geral</label>
			<div class="col-md-4">
			  <input type="file" name="pdf1_tabela" id="pdf1_tabela" class="form-control" >
			  <span class="help-block"> Insira arquivos com no máximo 5MB </span>
			</div>
		  </div>
		
		<div class="form-group">
		  <label class="control-label col-md-3">Área de texto</label>
		  <div class="col-md-9">
			<textarea class="form-control ckeditor" rows="6" name="texto1_tabela" data-error-container="#texto1_tabela_error" id="texto1_tabela"><? echo $dados['texto1_tabela'] ?></textarea>
			<div id="texto1_tabela_error"> </div>
			
		  </div>
		</div>
		
	<div class="form-group">
	  <label class="control-label col-md-3"> Select de opções </label>
	  <div class="col-md-4">
		<select class="form-control select2me " name="opcao_tabela" id="opcao_tabela" >
		
		  <option <? if ($dados['opcao_tabela'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
		  
		</select>
		
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
