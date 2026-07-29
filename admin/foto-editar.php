<?php
include ("app/sessao.php");
include ("../app/conexao8.php");
include ("../app/funcoes8.php");

$link_menu = 2;

$sql =  sql("SELECT * FROM foto ORDER BY id_foto ASC", $con);

$id_foto = trata($_GET['id_foto']);

if (!empty ($id_foto) && is_numeric ($id_foto)){
	$sql = mysqli_query($con, "SELECT * FROM foto WHERE id_foto = ".$id_foto." LIMIT 1") or die (mysqli_error($con));
	
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
		
	} else {
		volta ("erro", "Registro não encontrado!", "foto-admin.php");
	} 
	
} else {
	volta ("erro", "Registro não encontrado!", "foto-admin.php");
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
    <h3 class="page-title"><!-- InstanceBeginEditable name="Titulo" -->Foto <small>| alterar registro</small><!-- InstanceEndEditable --></h3>
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
            <form action="app/func_foto_editar.php" method="post" enctype="multipart/form-data" class="form-horizontal" id="formulario">
              <input name="id_foto" type="hidden" id="id_foto" value="<? echo $dados['id_foto']  ?>">
              <input name="imagem1_antigo" type="hidden" id="imagem1_antigo" value="<? echo $dados['imagem1_foto']  ?>">
              <input name="id_album" type="hidden" id="id_album" value="<? echo $dados['id_album'] ?>">
 
              <div class="form-body">
                <div class="alert alert-danger display-hide">
                  <button class="close" data-close="alert"></button>
                  Voce tem erros no preenchimento do formulário. Por favor, verifique </div>
                <div class="alert alert-success display-hide">
                  <button class="close" data-close="alert"></button>
                  Formulário validado com sucesso! </div>
                <div class="form-group">
                  <label class="control-label col-md-3">Título da foto</label>
                  <div class="col-md-4">
                    <input name="nome_foto" type="text"  class="form-control" id="nome_foto" value="<? echo $dados['nome_foto']  ?>" maxlength="150" />
                  </div>
                </div>
                
                <div class="form-group">
                  <label class="control-label col-md-3">Ordem / Posição <span class="required"> * </span></label>
                  <div class="col-md-4">
                    <div class="input-icon right"> <i class="fa"></i>
                      <input type="text" class="form-control numeros" name="numero_foto" id="numero_foto" value="<? echo $dados['numero_foto']  ?>"/>
                    </div>
                  </div>
                </div>
                <span id="imagem1">
                <input name="altera1_img" type="hidden" id="altera1_img" value="1">
                <div class="form-group imgmanter">
                  <div class="col-md-3 align-dir">
                    <label class="control-label"> Imagem </label>
                    <br>
                    <button type="button" class="btn yellow btmudar">Alterar Imagem</button>
                  </div>
                  <div class="col-md-4">
                  <? if (empty($dados['imagem1_foto']) or !file_exists('../imgs/'.$dados['imagem1_foto'])){
					  echo '<img src="assets/global/img/noimage.jpg" width="200" height="151" alt=""/>';
				  } else {
					  echo '<a href="../imgs/'.$dados['imagem1_foto'].'" onClick="window.open(this.href); return false;" >';
					  tim_w("../imgs/".$dados['imagem1_foto'], 200, "../");
					  echo '</a>';
				  }
                   ?>
                   </div>
                </div>
                <div class="form-group imgtrocar">
                  <label class="control-label col-md-3">Imagem  <br>
                    (Somente arquivos JPG, PNG ou GIF): <span class="required"> * </span> </label>
                  <div class="col-md-4">
                    <input type="file" name="imagem1_foto" id="imagem1_foto" class="form-control"  >
                    <button type="button" class="btn blue btmanter">Manter imagem atual</button>
                  </div>
                </div>
                </span> 
                <div class="form-group last">
                  <label class="control-label col-md-3">Descrição <span class="required"> * </span></label>
                  <div class="col-md-9">
                    <textarea class=" form-control" rows="6" name="texto1_foto" data-error-container="#texto1_foto_error" id="texto1_foto"><? echo $dados['texto1_foto'] ?></textarea>
                    <div id="texto1_foto_error"> </div>
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
					numero_foto: {
                        required: true,
                        number: true
                    },
					site_foto: {
                        required: true,
                        url: true
                    },
                    radio_foto: {
                        required: true
                    },
                    'check_foto[]': {
                        required: true,
                        minlength: 1
                    },
                    texto1_foto: {
                        required: false
                    },
                    texto2_foto: {
                        required: true
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    radio_foto: {
                        required: "Selecione uma opção"
                    },
                    check_foto: {
                        required: "Selecione pelo menos uma opção",
                        minlength: jQuery.validator.format("Selecione pelo menos {0} opção")
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
