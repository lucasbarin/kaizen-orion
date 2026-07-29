// Admin Base Scripts - Orion Kaizen
$(document).ready(function() {
	// Sidebar Toggle
	$('#sidebarToggle').on('click', function() {
		$('#adminSidebar').toggleClass('show');
	});
	
	// Close sidebar on outside click (mobile)
	$(document).on('click', function(e) {
		if ($(window).width() <= 768) {
			if (!$(e.target).closest('.admin-sidebar, #sidebarToggle').length) {
				$('#adminSidebar').removeClass('show');
			}
		}
	});
	
	// Active menu item
	var currentPage = window.location.pathname.split('/').pop();
	if (currentPage) {
		// Remove a classe active de todos os links primeiro
		$('.sidebar-menu a').removeClass('active');
		
		var matchFound = false;
		
		// Adiciona a classe active apenas ao link que corresponde exatamente à página atual
		$('.sidebar-menu a').each(function() {
			var href = $(this).attr('href');
			
			// Comparação exata do nome do arquivo
			if (href === currentPage && !matchFound) {
				$(this).addClass('active');
				matchFound = true;
				return false; // Para a iteração após encontrar o primeiro match
			}
		});
	}
	
	// Preloader hide
	if ($('#preloader').length) {
		$(window).on('load', function() {
			$('#preloader').fadeOut('slow');
		});
	}
});
