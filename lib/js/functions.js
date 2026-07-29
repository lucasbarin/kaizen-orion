// Browser detection for when you get desparate. A measure of last resort.
// http://rog.ie/post/9089341529/html5boilerplatejs

// var b = document.documentElement;
// b.setAttribute('data-useragent',  navigator.userAgent);
// b.setAttribute('data-platform', navigator.platform);

// sample CSS: html[data-useragent*='Chrome/13.0'] { ... }


// remap jQuery to $
(function ($) {

  /* trigger when page is ready */
  $(document).ready(function () {

    $("a[rel=external]").click(function () {
      window.open(this.href);
      return false;
    });

    $(".btconfirma").click(function () {

      if (!confirm('Tem certeza que deseja continuar?')) {
        return false;
      }
    });


    $(".btconfirmaForm").click(function () {

      if (!confirm('Tem certeza que deseja continuar?')) {
        return false;
      } else {
        var formulario = $(this).attr("name");
        $("#" + formulario).submit();

      }

    });
    $("#bt-ciente").click(function () {
      $("#alert-custo").hide("fast");
    });


    $("#custo_kaizen").blur(function () {

      var original = $("#custo_kaizen").val();
      var maximo = $("#custo_kaizen").data("valor");

      var valor = original.replace("R$ ", "");
      var valor2 = valor.replace(",", ".");


      if (valor2 >= maximo) {
        $("#alert-custo").show("fast");
      } else {
        $("#alert-custo").hide("fast");
      }


    });

    $("#btMenu").click(function () {
      if ($("#menuMob").hasClass("MobFechado")) {
        $("#menuMob").removeClass("MobFechado");
        $("#btMenu").addClass("BtFecha");

      } else {
        $("#menuMob").addClass("MobFechado");
        $("#btMenu").removeClass("BtFecha");
      }
    });


    $(".data").inputmask("date");
    $(".decimal").inputmask('decimal', {
      rightAlign: false
    });
    $(".numeros").inputmask('integer', {
      leftAlign: false
    });
    $(".cpf").inputmask("999.999.999-99");
    $(".cnpj").inputmask("99.999.999/9999-99");
    $(".money").maskMoney({
      prefix: 'R$ ',
      allowNegative: true,
      thousands: '',
      decimal: ',',
      affixesStay: false
    });

    /*	 $(".amigavel").inputmask({
        mask: "*{1,20}[.",
        greedy: false,
        definitions: {
          '*': {
            validator: "[0-9A-Za-z!_.]",
            casing: "lower"
          }
        }
      });
    	*/


    $('.img-resp2 img').each(function () {
      $(this).css("max-width", $(this).attr("name") + "px");
    });

    /*		$("#tempo").hide();
    		$("#custo").hide();
    		$("#etapa2").hide();
    		$("#qual_kaizen").hide();
    		$(".complemento").hide();*/


    $("#removerimagem1").hide();
    $("#removerimagem2").hide();


    /*		$("#tipo").change(function(){
                $(".complemento").hide();
                $(".complemento input").val("");
                
    			var valor = $("#tipo").val();
                var ref  = $("#tipo").find(':selected').attr('data-ref');
                
                if (valor > 0){
                    $("#etapa2").show(500);
                    if (ref > 0){
                    $(".complemento"+ref).show(500);    
                     }
                    
                 } else {
    				$("#etapa2").hide();
    			}

                
    		});*/


    $("#outrosdep_kaizen").change(function () {


      var valor = $("#outrosdep_kaizen").val();


      if (valor == 1) {

        $("#qual_kaizen").show(500);
      } else {

        $("#qual_kaizen").hide();
      }

    });

    $(".input-arquivo").change(function () {
      var idinput = $(this).attr("id");
      var valor = $("#" + idinput).val();
      if (valor != "") {
        $("#remover" + idinput).show(500);
      }
    });

    $(".limpa-input").click(function () {
      var idinput = $(this).attr("rel");
      $("#" + idinput).val("");
      $(this).hide(500);

    });

    $("#enviar").click(function () {
      $("#formKaizen").submit();

    });

    $('a[href*=#]:not([href=#])').click(function () {
      if (location.pathname.replace(/^\//, '') === this.pathname.replace(/^\//, '') && location.hostname === this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');

        var alturaMenu = $("#Mobile").height();

        if (target.length) {
          var alvo = target.offset().top - (alturaMenu) /*  ALTURA DO MENU */ ;
          $('html,body').animate({
            scrollTop: alvo
          }, 1000, 'easeInOutQuad');
          if ($("#menuMob").hasClass("MobFechado")) {
            /* teste */
            $("#teste");

          } else {
            $("#menuMob").addClass("MobFechado");
            $("#btMenu").removeClass("BtFecha");
          }
          return false;
        }
      }
    });


    /*	$.each( properties, function( i, val ) {
	
	var orderClass = '';

	$("#" + val).click(function(e){
		e.preventDefault();
		$('.filter__link.filter__link--active').not(this).removeClass('filter__link--active');
  		$(this).toggleClass('filter__link--active');
   		$('.filter__link').removeClass('asc desc');

   		if(orderClass == 'desc' || orderClass == '') {
    			$(this).addClass('asc');
    			orderClass = 'asc';
       	} else {
       		$(this).addClass('desc');
       		orderClass = 'desc';
       	}

		var parent = $(this).closest('.header__item');
    		var index = $(".header__item").index(parent);
		var $table = $('.table-content');
		var rows = $table.find('.table-row').get();
		var isSelected = $(this).hasClass('filter__link--active');
		var isNumber = $(this).hasClass('filter__link--number');
			
		rows.sort(function(a, b){

			var x = $(a).find('.table-data').eq(index).text();
    			var y = $(b).find('.table-data').eq(index).text();
				
			if(isNumber == true) {
    					
				if(isSelected) {
					return x - y;
				} else {
					return y - x;
				}

			} else {
			
				if(isSelected) {		
					if(x < y) return -1;
					if(x > y) return 1;
					return 0;
				} else {
					if(x > y) return -1;
					if(x < y) return 1;
					return 0;
				}
			}
    		});

		$.each(rows, function(index,row) {
			$table.append(row);
		});

		return false;
	});

});*/

  });

  $(window).load(function () {
    $("#overlayLoad").delay(350).fadeOut("slow", "easeOutQuad");
  });

  /* optional triggers
  /* optional triggers
	
	$(window).load(function() {
		
	});
	
	$(window).resize(function() {
		
	});
	
	*/

  /* optional triggers
	
	$(window).load(function() {
		
	});
	
	$(window).resize(function() {
		
	});
	
	*/


  /* optional triggers
	
	$(window).load(function() {
		
	});
	
	$(window).resize(function() {
		
	});
	
	*/


})(window.jQuery);
/*Pace.on('done', function() {
		
	
});*/
