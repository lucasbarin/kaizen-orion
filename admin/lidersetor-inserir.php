
<?
$sufixo = "lidersetor";

include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

include("_top_inserir.php"); ?>

<form action="app/func_lidersetor_inserir.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
  
  <div class="form-body">

	<div class="form-group">
		<label class="control-label col-md-3">Nome do líder<span class="required"> * </span></label>
		<div class="col-md-4">
			<input name="nome_lidersetor" type="text" required class="form-control" id="nome_lidersetor" value="<? echo $dados['nome_lidersetor']  ?>" maxlength="100"/>
		
		</div>
	</div>
		
		<div class="form-group">
		<?
		$c = 1;
		$v = ereg_replace (", ", ",", $dados['setores_lidersetor']); $var = explode (",", $v);
		?>
		  <label class="control-label col-md-3">Setores</label>
		  <div class="col-md-4">
			<div class="checkbox-list" data-error-container="#setores_lidersetor_error">
			
			
			<?
			$sqloption = sql("SELECT * FROM setor ORDER BY nome_setor", $con);
			if (mysqli_num_rows($sqloption)){
				while ($dadosopt = mysqli_fetch_assoc($sqloption)){
					?>
					<label>				
					<input type="checkbox" value="<? echo $dadosopt['id_setor'] ?>" name="setores_lidersetor[]" id="setores_lidersetor<? echo $dadosopt['id_setor'] ?>" <? $atual = $dadosopt['id_setor']; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
					<? echo $dadosopt['nome_setor'] ?> </label>
					<?
				
				}
			} else {
				?>
			  <label>
				<input type="checkbox" value="1" name="setores_lidersetor[]" id="setores_lidersetor1" <? $atual = 1; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 1 </label>
			  <label>
				<input type="checkbox" value="2" name="setores_lidersetor[]" id="setores_lidersetor2" <? $atual = 2; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 2 </label>
			  <label>
				<input type="checkbox" value="3" name="setores_lidersetor[]" id="setores_lidersetor3" <? $atual = 3; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
				Service 3 </label>
				<?
			}
			
			?>
			
			
			  
			</div>
			<span class="help-block"> (Selecione pelo menos um) </span>
			<div id="setores_lidersetor_error"> </div>
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
