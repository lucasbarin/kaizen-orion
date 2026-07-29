<?php
include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$link_menu = 2;

$sql =  sql("SELECT * FROM teste ORDER BY id_teste ASC", $con);

$id_teste = trata($_GET['id_teste']);

if (!empty ($id_teste) && is_numeric ($id_teste)){
	$sql = mysqli_query($con, "SELECT * FROM teste WHERE id_teste = ".$id_teste." LIMIT 1") or die (mysqli_error($con));
	
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
		
	} else {
		volta ("erro", "Registro n�o encontrado!", "teste-admin.php");
	} 
	
} else {
	volta ("erro", "Registro n�o encontrado!", "teste-admin.php");
}
?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="pt" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="pt" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="pt-br"><!-- InstanceBegin template="/Templates/admin-pt.dwt.php" codeOutsideHTMLIsLocked="false" -->
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
<meta charset="utf-8"/>
<!-- InstanceBeginEditable name="doctitle" -->
<title>Painel Administrativo</title>
<!-- InstanceEndEditable -->
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta content="width=device-width, initial-scale=1.0" name="viewport"/>
<meta content="" name="description"/>
<meta content="" name="author"/>
<!-- BEGIN GLOBAL MANDATORY STYLES -->
<link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/simple-line-icons2/simple-line-icons.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/uniform/css/uniform.default.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/plugins/bootstrap-switch/css/bootstrap-switch.min.css" rel="stylesheet" type="text/css"/>
<!-- END GLOBAL MANDATORY STYLES -->
<!-- BEGIN PAGE LEVEL STYLES -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/datatables/plugins/bootstrap/dataTables.bootstrap.css"/>
<!-- END PAGE LEVEL STYLES -->
<!-- BEGIN THEME STYLES -->
<link href="assets/global/css/components.css" rel="stylesheet" type="text/css"/>
<link href="assets/global/css/plugins.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/layout.css" rel="stylesheet" type="text/css"/>
<link id="style_color" href="assets/admin/layout/css/themes/default.css" rel="stylesheet" type="text/css"/>
<link href="assets/admin/layout/css/custom.css" rel="stylesheet" type="text/css"/>
<!-- END THEME STYLES -->
<link rel="shortcut icon" href="favicon.ico"/>
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" type="text/css" href="assets/global/plugins/select2/select2.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.css"/>
<link rel="stylesheet" type="text/css" href="assets/global/plugins/bootstrap-datepicker/css/datepicker.css"/>
<!-- InstanceEndEditable -->
</head>
<!-- END HEAD -->
<!-- BEGIN BODY -->
<body class="page-header-fixed page-quick-sidebar-over-content ">
<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top"> 
  <!-- BEGIN HEADER INNER -->
  <div class="page-header-inner"> 
    <!-- BEGIN LOGO -->
    <div class="page-logo"> <a href="../"> <img src="assets/admin/layout/img/logo.png" alt="logo" class="logo-default"/> </a>
      <div class="menu-toggler sidebar-toggler hide"> 
        <!-- DOC: Remove the above "hide" to enable the sidebar toggler button on header --> 
      </div>
    </div>
    <!-- END LOGO --> 
    <!-- BEGIN RESPONSIVE MENU TOGGLER --> 
    <a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"> </a> 
    <!-- END RESPONSIVE MENU TOGGLER --> 
    <!-- BEGIN TOP NAVIGATION MENU -->
    <div class="top-menu">
      <ul class="nav navbar-nav pull-right">
        <li class="dropdown"> <a href="app/logout.php" class="dropdown-toggle"> <i class="icon-logout"></i> </a> </li>
      </ul>
    </div>
    <!-- END TOP NAVIGATION MENU --> 
  </div>
  <!-- END HEADER INNER --> 
</div>
<!-- END HEADER -->
<div class="clearfix"> </div>
<!-- BEGIN CONTAINER -->
<div class="page-container">
<!-- BEGIN SIDEBAR -->
<div class="page-sidebar-wrapper"> 
  <!-- DOC: Set data-auto-scroll="false" to disable the sidebar from auto scrolling/focusing --> 
  <!-- DOC: Change data-auto-speed="200" to adjust the sub menu slide up/down speed -->
  <div class="page-sidebar navbar-collapse collapse"> 
    <!-- BEGIN SIDEBAR MENU -->
    <ul class="page-sidebar-menu " data-auto-scroll="true" data-slide-speed="200">
      <!-- DOC: To remove the sidebar toggler from the sidebar you just need to completely remove the below "sidebar-toggler-wrapper" LI element -->
      <li class="sidebar-toggler-wrapper"> 
        <!-- BEGIN SIDEBAR TOGGLER BUTTON -->
        <div class="sidebar-toggler"> </div>
        <!-- END SIDEBAR TOGGLER BUTTON --> 
      </li>
      <!--<li class="active open">-->
      <? include ("menu.php"); ?>
    </ul>
    <!-- END SIDEBAR MENU --> 
  </div>
</div>
<!-- END SIDEBAR --> 
<!-- BEGIN CONTENT -->
<div class="page-content-wrapper">
  <div class="page-content"> 
    <!-- BEGIN SAMPLE PORTLET CONFIGURATION MODAL FORM-->
    <div class="modal fade" id="portlet-config" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
      <div class="modal-dialog">
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal" aria-hidden="true"></button>
            <h4 class="modal-title">Modal title</h4>
          </div>
          <div class="modal-body"> Widget settings form goes here </div>
          <div class="modal-footer">
            <button type="button" class="btn blue">Save changes</button>
            <button type="button" class="btn default" data-dismiss="modal">Close</button>
          </div>
        </div>
        <!-- /.modal-content --> 
      </div>
      <!-- /.modal-dialog --> 
    </div>
    <!-- /.modal --> 
    <!-- END SAMPLE PORTLET CONFIGURATION MODAL FORM--> 
    <!-- BEGIN STYLE CUSTOMIZER --> 
    
    <!-- END STYLE CUSTOMIZER --> 
    <!-- BEGIN PAGE HEADER-->
    <div id="preloader">
  <div id="status">&nbsp;</div>
</div>
    <h3 class="page-title"><!-- InstanceBeginEditable name="Titulo" -->Teste <small>| alterar registro</small><!-- InstanceEndEditable --></h3>
    <!-- END PAGE HEADER--> 
    <div class="row">
      <div class="col-md-12">
        <? resposta ($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
      </div>
  </div>
    <!-- BEGIN PAGE CONTENT-->
    <div class="row">
      <div class="col-md-12"><!-- InstanceBeginEditable name="Conteudo" --> 
        <!-- BEGIN VALIDATION STATES-->
        <div class="portlet box">
          <div class="portlet-body form"> 
            <!-- BEGIN FORM-->
            <form action="app/func_teste_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
              <input name="id_teste" type="hidden" id="id_teste" value="<? echo $dados['id_teste']  ?>">
              <input name="imagem1_antigo" type="hidden" id="imagem1_antigo" value="<? echo $dados['imagem1_teste']  ?>">
              <input name="imagem2_antigo" type="hidden" id="imagem2_antigo" value="<? echo $dados['imagem2_teste']  ?>">
              <input name="imagem3_antigo" type="hidden" id="imagem3_antigo" value="<? echo $dados['imagem3_teste']  ?>">
              <input name="pdf1_antigo" type="hidden" id="pdf1_antigo" value="<? echo $dados['pdf1_teste']  ?>">
              <input name="pdf2_antigo" type="hidden" id="pdf2_antigo" value="<? echo $dados['pdf2_teste']  ?>">
              <input name="pdf3_antigo" type="hidden" id="pdf3_antigo" value="<? echo $dados['pdf3_teste']  ?>">
              <div class="form-body">
                <div class="alert alert-danger display-hide">
                  <button class="close" data-close="alert"></button>
                  Voce tem erros no preenchimento do formul�rio. Por favor, verifique </div>
                <div class="alert alert-success display-hide">
                  <button class="close" data-close="alert"></button>
                  Formul�rio validado com sucesso! </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Nome <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <input name="nome_teste" type="text" required class="form-control" id="nome_teste" value="<? echo $dados['nome_teste']  ?>" maxlength="150" />
                  </div>
                </div>
                <div class="form-group">
                  <label class="col-md-3 control-label">Email <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="input-group"> <span class="input-group-addon"> <i class="fa fa-envelope"></i></span>
                      <input name="email_teste" type="email" required class="form-control" id="email_teste" placeholder="Email" value="<? echo $dados['email_teste']  ?>" maxlength="50">
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Site/ URL <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="input-icon right"> <i class="fa"></i>
                      <input name="site_teste" type="text" class="form-control" id="site_teste" value="<? echo $dados['site_teste']  ?>" maxlength="150"/>
                    </div>
                    <span class="help-block"> ex: http://www.demo.com or http://demo.com </span></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Input&nbsp;&nbsp;</label>
                  <div class="col-md-4">
                    <input name="input_teste" type="text" class="form-control" id="input_teste" maxlength="150" value="<? echo $dados['input_teste']  ?>"  />
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Ordem que aparece</label>
                  <div class="col-md-4">
                    <div class="input-icon right"> <i class="fa"></i>
                      <input type="text" class="form-control numeros" name="numero_teste" id="numero_teste" value="<? echo $dados['numero_teste']  ?>"/>
                    </div>
                  </div>
                </div>
                <span id="imagem1">
                <input name="altera1_img" type="hidden" id="altera1_img" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Imagem 1</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Imagem</button>
                  </div>
                  <div class="col-md-4">
                  <? if (empty($dados['imagem1_teste']) or !file_exists('../imgs/'.$dados['imagem1_teste'])){
					  echo '<img src="assets/global/img/noimage.jpg" width="200" height="151" alt=""/>';
				  } else {
					  echo '<a href="../imgs/'.$dados['imagem1_teste'].'" onClick="window.open(this.href); return false;" >';
					  tim_w("../imgs/".$dados['imagem1_teste'], 200, "../");
					  echo '</a>';
				  }
                   ?>
                   </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Imagem 1 <br>
                    (Somente arquivos JPG, PNG ou GIF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="imagem1_teste" id="imagem1_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter imagem atual</button>
                  </div>
                </div>
                </span> <span id="imagem2">
                <input name="altera2_img" type="hidden" id="altera2_img" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Imagem 2</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Imagem</button>
                  </div>
                  <div class="col-md-4">
                  <? if (empty($dados['imagem2_teste']) or !file_exists('../imgs/'.$dados['imagem2_teste'])){
					  echo '<img src="assets/global/img/noimage.jpg" width="200" height="151" alt=""/>';
				  } else {
					  echo '<a href="../imgs/'.$dados['imagem2_teste'].'" onClick="window.open(this.href); return false;" >';
					  tim_w("../imgs/".$dados['imagem2_teste'], 200, "../");
					  echo '</a>';
				  }
                   ?>
                  </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Imagem 2 <br>
                    (Somente arquivos JPG, PNG ou GIF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="imagem2_teste" id="imagem2_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter imagem atual</button>
                  </div>
                </div>
                </span> <span id="imagem3">
                <input name="altera3_img" type="hidden" id="altera3_img" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Imagem 3</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Imagem</button>
                  </div>
                  <div class="col-md-4"> 
                  <? if (empty($dados['imagem3_teste']) or !file_exists('../imgs/'.$dados['imagem3_teste'])){
					  echo '<img src="assets/global/img/noimage.jpg" width="200" height="151" alt=""/>';
				  } else {
					  echo '<a href="../imgs/'.$dados['imagem3_teste'].'" onClick="window.open(this.href); return false;" >';
					  tim_w("../imgs/".$dados['imagem3_teste'], 200, "../");
					  echo '</a>';
				  }
                   ?>
                  </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Imagem 3 <br>
                    (Somente arquivos JPG, PNG ou GIF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="imagem3_teste" id="imagem3_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter imagem atual</button>
                  </div>
                </div>
                </span> <span id="pdf1">
                <input name="altera1_pdf" type="hidden" id="altera1_pdf" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Arquivo 1</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
                  </div>
                  <div class="col-md-4"><br>
                    <br>
                    <? if (empty($dados['pdf1_teste']) or !file_exists('../imgs/'.$dados['pdf1_teste'])){
					  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
				  } else {
					  echo '<p>'.$dados['pdf1_teste'].' <a href="../imgs/'.$dados['pdf1_teste'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
				  }
                   ?>
                    
                  </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Arquivo 1 <br>
                    (Somente arquivos PDF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="pdf1_teste" id="pdf1_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter arquivo atual</button>
                  </div>
                </div>
                </span> <span id="pdf2">
                <input name="altera2_pdf" type="hidden" id="altera2_pdf" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Arquivo 2</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
                  </div>
                  <div class="col-md-4"><br>
                    <br>
                    <? if (empty($dados['pdf2_teste']) or !file_exists('../imgs/'.$dados['pdf2_teste'])){
					  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
				  } else {
					  echo '<p>'.$dados['pdf2_teste'].' <a href="../imgs/'.$dados['pdf2_teste'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
				  }
                   ?>
                  </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Arquivo 2 <br>
                    (Somente arquivos PDF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="pdf2_teste" id="pdf2_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter arquivo atual</button>
                  </div>
                </div>
                </span> <span id="pdf3">
                <input name="altera3_pdf" type="hidden" id="altera3_pdf" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Arquivo 3</label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Arquivo</button>
                  </div>
                  <div class="col-md-4"><br>
                    <br>
                    <? if (empty($dados['pdf3_teste']) or !file_exists('../imgs/'.$dados['pdf3_teste'])){
					  echo '<p class="label label-sm label-icon label-danger">Nenhum arquivo enviado.</p>';
				  } else {
					  echo '<p>'.$dados['pdf3_teste'].' <a href="../imgs/'.$dados['pdf3_teste'].'" onClick="window.open(this.href); return false;" class="btn btn-xs yellow">Visualizar <i class="fa fa-search"></i></a></p>';
				  }
                   ?>
                  </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Arquivo 3 <br>
                    (Somente arquivos PDF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="pdf3_teste" id="pdf3_teste" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter arquivo atual</button>
                  </div>
                </div>
                </span>
                <div class="form-group">
                  <label class="control-label col-md-3">Pre�o <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="input-icon right"> <i class="fa"></i>
                      <input type="text" class="form-control money" name="preco_teste" id="preco_teste" required value="<? echo number_format ($dados['preco_teste'], 2, ",","") ;  ?>" />
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3"> Op��o <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <select class="form-control select2me" name="opcao_teste" id="opcao_teste" required>
                      <option <? if ($dados['opcao_teste'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
                      <option <? if ($dados['opcao_teste'] == 1) { echo 'selected="selected"'; } ?> value="1">Option 1</option>
                      <option <? if ($dados['opcao_teste'] == 2) { echo 'selected="selected"'; } ?> value="2">Option 2</option>
                      <option <? if ($dados['opcao_teste'] == 3) { echo 'selected="selected"'; } ?> value="3">Option 3</option>
                      <option <? if ($dados['opcao_teste'] == 4) { echo 'selected="selected"'; } ?> value="4">Option 4</option>
                    </select>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Data</label>
                  <div class="col-md-4">
                    <div class="input-group date date-picker" data-date-format="dd/mm/yyyy">
                      <input type="text" class="form-control" readonly name="data_teste" id="data_teste" required value="<? if ($dados['data_teste'] != '0000-00-00'){ echo date("d/m/Y", strtotime ($dados['data_teste'])); }  ?>"  >
                      <span class="input-group-btn">
                      <button class="btn default" type="button"><i class="fa fa-calendar"></i></button>
                      </span></div>
                    <!-- /input-group --> 
                    <span class="help-block"> selecione a data </span></div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Radio <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="radio-list" data-error-container="#radio_teste_error">
                      <label>
                        <input type="radio" name="radio_teste" value="1" required <? if ($dados['radio_teste'] == 1){ echo 'checked="checked"'; } ?> />
                        Sim </label>
                      <label>
                        <input type="radio" name="radio_teste" value="2" required <? if ($dados['radio_teste'] == 2){ echo 'checked="checked"'; } ?> />
                        N�o </label>
                    </div>
                    <div id="radio_teste_error"> </div>
                  </div>
                </div>
                <div class="form-group">
                <?
        $c = 1;
        $v = str_replace(", ", ",", $dados['check_teste']); $var = explode (",", $v);
        ?>
                  <label class="control-label col-md-3">Check <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="checkbox-list" data-error-container="#check_teste_error">
                      <label>
                        <input type="checkbox" value="1" name="check_teste[]" id="check_teste1" <? $atual = 1; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
                        Service 1 </label>
                      <label>
                        <input type="checkbox" value="2" name="check_teste[]" id="check_teste2" <? $atual = 2; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
                        Service 2 </label>
                      <label>
                        <input type="checkbox" value="3" name="check_teste[]" id="check_teste3" <? $atual = 3; if(in_array($atual, $var)){ echo 'checked="checked"';	} else { echo ''; } ?> />
                        Service 3 </label>
                    </div>
                    <span class="help-block"> (Selecione pelo menos um) </span>
                    <div id="check_teste_error"> </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-3">WYSIHTML5 <span class="required"> * </span></label>
                  <div class="col-md-9">
                    <textarea class="wysihtml5 form-control" rows="6" name="texto1_teste" data-error-container="#texto1_teste_error" id="texto1_teste"><? echo $dados['texto1_teste'] ?></textarea>
                    <div id="texto1_teste_error"> </div>
                  </div>
                </div>
                <div class="form-group last">
                  <label class="control-label col-md-3">CKEditor <span class="required"> * </span></label>
                  <div class="col-md-9">
                    <textarea class="ckeditor form-control" name="texto2_teste" rows="6" data-error-container="#texto2_teste_error" id="texto2_teste"><? echo $dados['texto2_teste'] ?></textarea>
                    <div id="texto2_teste_error"> </div>
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
            <!-- END FORM--> 
          </div>
          <!-- END VALIDATION STATES--> 
        </div>
        <!-- InstanceEndEditable --> </div>
      <!-- END PAGE CONTENT--> 
    </div>
  </div>
  <!-- END CONTENT --> 
  
</div>
<!-- END CONTAINER --> 
<!-- BEGIN FOOTER -->
<div class="page-footer">
  <div class="page-footer-tools"> <span class="go-top"> <i class="fa fa-angle-up"></i> </span> </div>
</div>
<!-- END FOOTER --> 
<!-- BEGIN JAVASCRIPTS(Load javascripts at bottom, this will reduce page load time) --> 
<!-- BEGIN CORE PLUGINS --> 
<!--[if lt IE 9]>
<script src="assets/global/plugins/respond.min.js"></script>
<script src="assets/global/plugins/excanvas.min.js"></script> 
<![endif]--> 
<script src="assets/global/plugins/jquery-1.11.0.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-migrate-1.2.1.min.js" type="text/javascript"></script> 
<!-- IMPORTANT! Load jquery-ui-1.10.3.custom.min.js before bootstrap.min.js to fix bootstrap tooltip conflict with jquery ui tooltip --> 
<script src="assets/global/plugins/jquery-ui/jquery-ui-1.10.3.custom.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap/js/bootstrap.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery-slimscroll/jquery.slimscroll.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.blockui.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/jquery.cokie.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/uniform/jquery.uniform.min.js" type="text/javascript"></script> 
<script src="assets/global/plugins/bootstrap-switch/js/bootstrap-switch.min.js" type="text/javascript"></script> 
 
<!-- END CORE PLUGINS --> 
<!-- BEGIN PAGE LEVEL PLUGINS --> 
<!-- InstanceBeginEditable name="scripts" --> 
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/jquery.validate.min.js"></script> 
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/additional-methods.min.js"></script> 
<script type="text/javascript" src="assets/global/plugins/jquery-validation/js/messages_pt_BR.min.js"></script> 
<script type="text/javascript" src="assets/global/plugins/select2/select2.min.js"></script> 
<script type="text/javascript" src="assets/global/plugins/bootstrap-datepicker/js/bootstrap-datepicker.js"></script> 
<script src="assets/global/plugins/jquery-inputmask/jquery.inputmask.bundle.min.js"></script> 
<script src="assets/global/plugins/maskmoney/jquery.maskMoney.js"></script> 
<script src="assets/global/plugins/ckeditor2/ckeditor.js"></script> 
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/wysihtml5-0.3.0.js"></script> 
<script type="text/javascript" src="assets/global/plugins/bootstrap-wysihtml5/bootstrap-wysihtml5.js"></script> 
<script src="assets/global/scripts/metronic.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/layout.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/quick-sidebar.js" type="text/javascript"></script> 
<script src="assets/admin/layout/scripts/demo.js" type="text/javascript"></script> 
<!--<script src="assets/admin/pages/scripts/form-validation.js"></script> --> 
<script>
var FormValidation = function () {

   

    // advance validation
    var handleValidation = function() {
        // for more info visit the official plugin documentation: 
        // http://docs.jquery.com/Plugins/Validation

            var formulario = $('#formulario');
            var error3 = $('.alert-danger', formulario);
            var success3 = $('.alert-success', formulario);

            //IMPORTANT: update CKEDITOR textarea with actual content before submit
            formulario.on('submit', function() {
                for(var instanceName in CKEDITOR.instances) {
                    CKEDITOR.instances[instanceName].updateElement();
                }
            })

            formulario.validate({
                errorElement: 'span', //default input error message container
                errorClass: 'help-block help-block-error', // default input error message class
                focusInvalid: false, // do not focus the last invalid input
                ignore: "", // validate all fields including form hidden input
                rules: {
					numero_teste: {
                        required: false,
                        number: true
                    },
					site_teste: {
                        required: true,
                        url: true
                    },
                    radio_teste: {
                        required: true
                    },
                    'check_teste[]': {
                        required: true,
                        minlength: 1
                    },
                    texto1_teste: {
                        required: true
                    },
                    texto2_teste: {
                        required: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    radio_teste: {
                        required: "Selecione uma op��o"
                    },
                    check_teste: {
                        required: "Selecione pelo menos uma op��o",
                        minlength: jQuery.validator.format("Selecione pelo menos {0} op��o")
                    }
                },

                errorPlacement: function (error, element) { // render error placement for each input type
                    if (element.parent(".input-group").size() > 0) {
                        error.insertAfter(element.parent(".input-group"));
                    } else if (element.attr("data-error-container")) { 
                        error.appendTo(element.attr("data-error-container"));
                    } else if (element.parents('.radio-list').size() > 0) { 
                        error.appendTo(element.parents('.radio-list').attr("data-error-container"));
                    } else if (element.parents('.radio-inline').size() > 0) { 
                        error.appendTo(element.parents('.radio-inline').attr("data-error-container"));
                    } else if (element.parents('.checkbox-list').size() > 0) {
                        error.appendTo(element.parents('.checkbox-list').attr("data-error-container"));
                    } else if (element.parents('.checkbox-inline').size() > 0) { 
                        error.appendTo(element.parents('.checkbox-inline').attr("data-error-container"));
                    } else {
                        error.insertAfter(element); // for other inputs, just perform default behavior
                    }
                },

                invalidHandler: function (event, validator) { //display error alert on form submit   
                    success3.hide();
                    error3.show();
                    Metronic.scrollTo(error3, -200);
					$("#loader").css("display", "none");
					$("#btsubmit").css("display", "block");
                },

                highlight: function (element) { // hightlight error inputs
                   $(element)
                        .closest('.form-group').addClass('has-error'); // set error class to the control group
                },

                unhighlight: function (element) { // revert the change done by hightlight
                    $(element)
                        .closest('.form-group').removeClass('has-error'); // set error class to the control group
                },

                success: function (label) {
                    label
                        .closest('.form-group').removeClass('has-error'); // set success class to the control group
                },

              /*
			  submitHandler: function (form) {
                    success3.show();
                    error3.hide();
					$("#loader").css("display", "block");
					$("#btsubmit").css("display", "none");
					$("#formulario").submit();
                }
				*/

            });

             //apply validation on select2 dropdown value change, this only needed for chosen dropdown integration.
            $('.select2me', formulario).change(function () {
                formulario.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });

            // initialize select2 tags
            $("#select2_tags").change(function() {
                formulario.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            }).select2({
                tags: ["red", "green", "blue", "yellow", "pink"]
            });

            //initialize datepicker
            $('.date-picker').datepicker({
                rtl: Metronic.isRTL(),
                autoclose: true,
            });
			
            $('.date-picker .form-control').change(function() {
                formulario.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            })
    }

    var handleWysihtml5 = function() {
        if (!jQuery().wysihtml5) {
            
            return;
        }

        if ($('.wysihtml5').size() > 0) {
            $('.wysihtml5').wysihtml5({
                "stylesheets": ["../../assets/global/plugins/bootstrap-wysihtml5/wysiwyg-color.css"]
            });
        }
    }

    return {
        //main function to initiate the module
        init: function () {
            handleWysihtml5();
            handleValidation();

        }

    };

}();

</script> 

<!-- END PAGE LEVEL STYLES --> 
<script>
jQuery(document).ready(function() {   
	// initiate layout and plugins
	Metronic.init(); // init metronic core components
	Layout.init(); // init current layout
	QuickSidebar.init(); // init quick sidebar
	Demo.init(); // init demo features
	FormValidation.init();
	
	$(".imgtrocar").css("display", "none");
	
	$(".btmudar").click(function(){
		var span = $(this).closest("span");
    	var span_id = (span).attr("id");
		$("#"+span_id+" .imgmanter").css("display", "none");
		$("#"+span_id+" .imgtrocar").css("display", "block");
		$("#"+span_id+" input[type=hidden]").attr("value", "2");
	});
	
	$(".btmanter").click(function(){
		var span = $(this).closest("span");
    	var span_id = (span).attr("id");
		$("#"+span_id+" .imgmanter").css("display", "block");
		$("#"+span_id+" .imgtrocar").css("display", "none");
		$("#"+span_id+" input[type=hidden]").attr("value", "1");
	});
	
	
});
</script> 
<!-- END JAVASCRIPTS --> 
<!-- InstanceEndEditable --> 
<!-- END PAGE LEVEL PLUGINS -->
<script src="assets/admin/layout/scripts/custom.js" type="text/javascript"></script>
</body>

<!-- END BODY -->
<!-- InstanceEnd --></html>