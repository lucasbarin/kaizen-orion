$(document).ready(function(){
	
	 //<![CDATA[
	$(window).load(function() { // makes sure the whole site is loaded
		$('#status').fadeOut(); // will first fade out the loading animation
		$('#preloader').delay(350).fadeOut('slow'); // will fade out the white DIV that covers the website.
		$('#conteudo').delay(350).css({'overflow': 'visible'});
	})
   //]]>
	
	
   $(".data").inputmask("date");  
   $(".decimal").inputmask('decimal', { rightAlign: false }); 
   $(".numeros").inputmask('integer', { rightAlign: false });  
   $(".cpf").inputmask("999.999.999-99");  
   $(".cnpj").inputmask("99.999.999/9999-99");  
   $(".money").maskMoney({prefix:'R$ ', allowNegative: true, thousands:'', decimal:',', affixesStay: false});
   
	 $(".amigavel").inputmask({
    mask: "*{1,20}[.",
    greedy: false,
    definitions: {
      '*': {
        validator: "[0-9A-Za-z!_.]",
        casing: "lower"
      }
    }
  });
	
	$(".botaoconfirma").click(function(){

			if(!confirm('Tem certeza que deseja continuar? Esta ação não poderá ser desfeita.')) {
				return false;
			}


		});
	
   /*$("#btsubmit").click(function(){
   		var form = $("#formulario");
		if (form.valid()){
			success3.show();
			error3.hide();
			$("#loader").css("display", "block");
			$("#btsubmit").css("display", "none");
			$("#formulario").submit();
		}
   });*/
   	
	$("#btsubmit").click(function(){
		$("#loader").css("display", "block");
		$("#btsubmit").css("display", "none");
   });
   
});