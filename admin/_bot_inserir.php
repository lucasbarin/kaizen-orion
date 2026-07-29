	
				<!-- END FORM-->
            </div>
            <!-- END VALIDATION STATES-->
          </div>
      </div>
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
					numero_<? echo $sufixo ?>: {
                        required: false,
                        number: false
                    },
					site_<? echo $sufixo ?>: {
                        required: false,
                        url: false
                    },
                    radio_<? echo $sufixo ?>: {
                        required: false
                    },
                    'check_<? echo $sufixo ?>[]': {
                        required: false,
                        minlength: 1
                    },
                    texto1_<? echo $sufixo ?>: {
                        required: false
                    },
                    texto2_<? echo $sufixo ?>: {
                        required: false
                    }
                },

                messages: { // custom messages for radio buttons and checkboxes
                    radio_<? echo $sufixo ?>: {
                        required: "Selecione uma opção"
                    },
                    check_<? echo $sufixo ?>: {
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
            /*$('.select2me', formulario).change(function () {
                formulario.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input
            });

            // initialize select2 tags
            $("#select2_tags").change(function() {
                formulario.validate().element($(this)); //revalidate the chosen dropdown value and show error or success message for the input 
            }).select2({
                tags: ["red", "green", "blue", "yellow", "pink"]
            });*/

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
});
</script> 
<!-- END JAVASCRIPTS --> 
<!-- END PAGE LEVEL PLUGINS -->
<script src="assets/admin/layout/scripts/custom.js" type="text/javascript"></script>
</body>

<!-- END BODY -->
</html>