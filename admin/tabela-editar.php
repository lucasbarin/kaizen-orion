
<?
$sufixo = "tabela";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$sql =  sql("SELECT * FROM tabela ORDER BY id_tabela ASC", $con);

$id_tabela = trata($_GET['id_tabela']);

if (!empty ($id_tabela) && is_numeric ($id_tabela)){
	$sql = mysqli_query($con, "SELECT * FROM tabela WHERE id_tabela = ".$id_tabela." LIMIT 1") or die (mysqli_error($con));
	
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
		
	} else {
		volta ("erro", "Registro não encontrado!", "tabela-admin.php");
	} 
	
} else {
	volta ("erro", "Registro não encontrado!", "tabela-admin.php");
}

include("_top_editar.php"); ?>

<form action="app/func_tabela_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  <input name="id_tabela" type="hidden" id="id_tabela" value="<? echo $dados['id_tabela']  ?>">
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
		
		<span id="arquivo2">
		<input name="imagem1_antigo" type="hidden" id="imagem1_antigo" value="<? echo $dados['imagem1_tabela']  ?>">
			<input name="altera_imagem1" type="hidden" id="altera_imagem1" value="1">
			<div class="form-group imgmanter">
			  <div class="col-md-3 align-dir">
				<label class="control-label"> Arquivo Imagem</label>
				<br>
				<button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
			  </div>
			  <div class="col-md-4"><br>
				<br>
				<? if (empty($dados['imagem1_tabela']) or !file_exists("../imgs/".$dados['imagem1_tabela'])){
				  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
			  } else {
				   echo '<p>'.$dados['imagem1_tabela'].' <a href="../imgs/'.$dados['imagem1_tabela'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
			  }
			   ?>

			  </div>
			</div>
			<div class="form-group imgtrocar">
			  <label class="control-label col-md-3">Arquivo Imagem  </label>
			  <div class="col-md-4">
				<input type="file" name="imagem1_tabela" id="imagem1_tabela" class="form-control"  >
				<button type="button" class="btn blue btmanter">Manter arquivo atual</button>
				
			  </div>
			</div>
		</span>
		
		<span id="arquivo3">
		<input name="pdf1_antigo" type="hidden" id="pdf1_antigo" value="<? echo $dados['pdf1_tabela']  ?>">
			<input name="altera_pdf1" type="hidden" id="altera_pdf1" value="1">
			<div class="form-group imgmanter">
			  <div class="col-md-3 align-dir">
				<label class="control-label"> Arquivo Geral</label>
				<br>
				<button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
			  </div>
			  <div class="col-md-4"><br>
				<br>
				<? if (empty($dados['pdf1_tabela']) or !file_exists("../imgs/".$dados['pdf1_tabela'])){
				  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
			  } else {
				   echo '<p>'.$dados['pdf1_tabela'].' <a href="../imgs/'.$dados['pdf1_tabela'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
			  }
			   ?>

			  </div>
			</div>
			<div class="form-group imgtrocar">
			  <label class="control-label col-md-3">Arquivo Geral  </label>
			  <div class="col-md-4">
				<input type="file" name="pdf1_tabela" id="pdf1_tabela" class="form-control"  >
				<button type="button" class="btn blue btmanter">Manter arquivo atual</button>
				<span class="help-block"> Insira arquivos com no máximo 5MB </span>
			  </div>
			</div>
		</span>
		
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
include("_bot_editar.php");
?>
