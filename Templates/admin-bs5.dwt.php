<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!-- TemplateBeginEditable name="doctitle" -->
	<title>Painel Administrativo | Sistema Orion Kaizen</title>
	<!-- TemplateEndEditable -->
	
	<meta name="description" content="Painel Administrativo - Sistema Kaizen">
	<link rel="shortcut icon" href="favicon.ico"/>
	
	<!-- Bootstrap 5.3.2 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- DataTables Bootstrap 5 -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
	
	<!-- Select2 Bootstrap 5 -->
	<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
	<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" />
	
	<!-- Admin Custom CSS -->
	<style>
		:root {
			--orion-primary: #009ee3;
			--orion-primary-dark: #0088c7;
			--orion-secondary: #fcd700;
			--orion-success: #00812e;
			--orion-text: #565656;
			--sidebar-width: 260px;
			--header-height: 60px;
		}
		
		* {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
		}
		
		body {
			background: #f4f6f9;
			font-size: 0.95rem;
		}
		
		/* Header */
		.admin-header {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			height: var(--header-height);
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			z-index: 1030;
			display: flex;
			align-items: center;
			padding: 0 1rem;
		}
		
		.admin-header .logo {
			color: white;
			font-size: 1.3rem;
			font-weight: 700;
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: 0.5rem;
			padding: 0 1rem;
		}
		
		.admin-header .logo img {
			height: 35px;
		}
		
		.sidebar-toggle {
			background: none;
			border: none;
			color: white;
			font-size: 1.3rem;
			cursor: pointer;
			padding: 0.5rem;
			margin-right: 1rem;
		}
		
		.sidebar-toggle:hover {
			opacity: 0.8;
		}
		
		.header-right {
			margin-left: auto;
			display: flex;
			align-items: center;
			gap: 1rem;
		}
		
		.header-right .btn-logout {
			background: rgba(255, 255, 255, 0.2);
			border: none;
			color: white;
			padding: 0.5rem 1rem;
			border-radius: 0.5rem;
			text-decoration: none;
			display: flex;
			align-items: center;
			gap: 0.5rem;
			font-size: 0.9rem;
			transition: all 0.3s ease;
		}
		
		.header-right .btn-logout:hover {
			background: rgba(255, 255, 255, 0.3);
		}
		
		/* Sidebar */
		.admin-sidebar {
			position: fixed;
			top: var(--header-height);
			left: 0;
			width: var(--sidebar-width);
			height: calc(100vh - var(--header-height));
			background: #2c3e50;
			overflow-y: auto;
			transition: all 0.3s ease;
			z-index: 1020;
		}
		
		.admin-sidebar::-webkit-scrollbar {
			width: 6px;
		}
		
		.admin-sidebar::-webkit-scrollbar-track {
			background: #34495e;
		}
		
		.admin-sidebar::-webkit-scrollbar-thumb {
			background: #4a5f7f;
			border-radius: 3px;
		}
		
		.sidebar-menu {
			list-style: none;
			padding: 0;
			margin: 0;
		}
		
		.sidebar-menu li {
			border-bottom: 1px solid rgba(255, 255, 255, 0.05);
		}
		
		.sidebar-menu a {
			color: #b8c7ce;
			padding: 0.8rem 1.2rem;
			display: flex;
			align-items: center;
			gap: 0.75rem;
			text-decoration: none;
			transition: all 0.3s ease;
			font-size: 0.95rem;
		}
		
		.sidebar-menu a:hover {
			background: rgba(255, 255, 255, 0.05);
			color: white;
			padding-left: 1.5rem;
		}
		
		.sidebar-menu a.active {
			background: linear-gradient(90deg, var(--orion-primary) 0%, rgba(0, 158, 227, 0.8) 100%);
			color: white;
			border-left: 4px solid var(--orion-secondary);
		}
		
		.sidebar-menu a i {
			width: 20px;
			text-align: center;
			font-size: 1.1rem;
		}
		
		.sidebar-header {
			padding: 1rem 1.2rem;
			color: #7f8c8d;
			font-size: 0.75rem;
			font-weight: 600;
			text-transform: uppercase;
			letter-spacing: 0.5px;
			margin-top: 0.5rem;
		}
		
		/* Main Content */
		.admin-content {
			margin-left: var(--sidebar-width);
			margin-top: var(--header-height);
			padding: 2rem;
			min-height: calc(100vh - var(--header-height));
		}
		
		/* Page Header */
		.page-header {
			background: white;
			padding: 1.5rem;
			border-radius: 0.75rem;
			margin-bottom: 2rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
		}
		
		.page-header h1 {
			font-size: 1.75rem;
			font-weight: 700;
			color: var(--orion-text);
			margin: 0 0 0.25rem;
		}
		
		.page-header .subtitle {
			color: #95a5a6;
			font-size: 0.9rem;
			margin: 0;
		}
		
		/* Cards */
		.admin-card {
			background: white;
			border-radius: 0.75rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
			margin-bottom: 2rem;
		}
		
		.admin-card-header {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			color: white;
			padding: 1rem 1.5rem;
			border-radius: 0.75rem 0.75rem 0 0;
			font-weight: 600;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		
		.admin-card-body {
			padding: 1.5rem;
		}
		
		/* Stat Cards */
		.stat-card {
			background: white;
			border-radius: 0.75rem;
			padding: 1.5rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
			border-left: 4px solid var(--orion-primary);
			transition: all 0.3s ease;
		}
		
		.stat-card:hover {
			transform: translateY(-5px);
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
		}
		
		.stat-card.success {
			border-left-color: var(--orion-success);
		}
		
		.stat-card.warning {
			border-left-color: #ffc107;
		}
		
		.stat-card.danger {
			border-left-color: #dc3545;
		}
		
		.stat-card .stat-icon {
			font-size: 2.5rem;
			opacity: 0.3;
			float: right;
		}
		
		.stat-card .stat-value {
			font-size: 2rem;
			font-weight: 700;
			color: var(--orion-text);
			margin: 0.5rem 0;
		}
		
		.stat-card .stat-label {
			color: #95a5a6;
			font-size: 0.9rem;
			text-transform: uppercase;
			letter-spacing: 0.5px;
		}
		
		/* Buttons */
		.btn-orion-primary {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			border: none;
			color: white;
			padding: 0.5rem 1.5rem;
			border-radius: 0.5rem;
			font-weight: 500;
			transition: all 0.3s ease;
		}
		
		.btn-orion-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3);
			color: white;
		}
		
		.btn-orion-secondary {
			background: var(--orion-secondary);
			border: none;
			color: var(--orion-text);
			padding: 0.5rem 1.5rem;
			border-radius: 0.5rem;
			font-weight: 500;
			transition: all 0.3s ease;
		}
		
		.btn-orion-secondary:hover {
			background: #e5c500;
			transform: translateY(-2px);
			color: var(--orion-text);
		}
		
		/* Tables */
		.table-orion {
			background: white;
		}
		
		.table-orion thead {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			color: white;
		}
		
		.table-orion thead th {
			border: none;
			font-weight: 600;
			font-size: 0.9rem;
			padding: 1rem;
		}
		
		.table-orion tbody tr {
			transition: all 0.2s ease;
		}
		
		.table-orion tbody tr:hover {
			background: #f8f9fa;
		}
		
		/* Badges */
		.badge-status-1 {
			background: #ffc107;
			color: #000;
		}
		
		.badge-status-2 {
			background: var(--orion-success);
		}
		
		.badge-status-3 {
			background: #dc3545;
		}
		
		.badge-status-4 {
			background: #ff9800;
		}
		
		/* Alerts */
		.alert-orion {
			border-left: 4px solid var(--orion-primary);
			background: rgba(0, 158, 227, 0.1);
			color: var(--orion-text);
		}
		
		/* Footer */
		.admin-footer {
			margin-left: var(--sidebar-width);
			padding: 1.5rem 2rem;
			background: white;
			border-top: 1px solid #e9ecef;
			color: #95a5a6;
			font-size: 0.85rem;
			text-align: center;
		}
		
		/* Responsive */
		@media (max-width: 768px) {
			.admin-sidebar {
				left: calc(-1 * var(--sidebar-width));
			}
			
			.admin-sidebar.show {
				left: 0;
			}
			
			.admin-content {
				margin-left: 0;
			}
			
			.admin-footer {
				margin-left: 0;
			}
			
			.page-header h1 {
				font-size: 1.5rem;
			}
		}
		
		/* Loading Overlay */
		#preloader {
			position: fixed;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: rgba(255, 255, 255, 0.95);
			z-index: 9999;
			display: none;
		}
		
		#status {
			width: 50px;
			height: 50px;
			position: absolute;
			left: 50%;
			top: 50%;
			margin: -25px 0 0 -25px;
			border: 5px solid var(--orion-primary);
			border-top: 5px solid transparent;
			border-radius: 50%;
			animation: spin 1s linear infinite;
		}
		
		@keyframes spin {
			0% { transform: rotate(0deg); }
			100% { transform: rotate(360deg); }
		}
	</style>
	
	<!-- TemplateBeginEditable name="head" -->
	<!-- Espaço para CSS/JS específicos da página -->
	<!-- TemplateEndEditable -->
</head>

<body>
	<!-- Loading Overlay -->
	<div id="preloader">
		<div id="status"></div>
	</div>
	
	<!-- Header -->
	<header class="admin-header">
		<button class="sidebar-toggle" id="sidebarToggle">
			<i class="fas fa-bars"></i>
		</button>
		
		<a href="../" class="logo">
			<img src="assets/admin/layout/img/logo.png" alt="Orion Kaizen" onerror="this.style.display='none'">
			<span>ORION KAIZEN</span>
		</a>
		
		<div class="header-right">
			<a href="app/logout.php" class="btn-logout">
				<i class="fas fa-sign-out-alt"></i>
				<span>Sair</span>
			</a>
		</div>
	</header>
	
	<!-- Sidebar -->
	<aside class="admin-sidebar" id="adminSidebar">
		<ul class="sidebar-menu">
			<li class="sidebar-header">MENU PRINCIPAL</li>
			
			<li>
				<a href="home.php">
					<i class="fas fa-tachometer-alt"></i>
					<span>Dashboard</span>
				</a>
			</li>
			
			<li class="sidebar-header">SISTEMA KAIZEN</li>
			
			<li>
				<a href="colaborador-admin.php">
					<i class="fas fa-users"></i>
					<span>Colaboradores</span>
				</a>
			</li>
			
			<li>
				<a href="setor-admin.php">
					<i class="fas fa-sitemap"></i>
					<span>Setores</span>
				</a>
			</li>
			
			<li>
				<a href="unidade-admin.php">
					<i class="fas fa-building"></i>
					<span>Unidades</span>
				</a>
			</li>
			
			<li>
				<a href="kaizen-admin.php">
					<i class="fas fa-lightbulb"></i>
					<span>Kaizens</span>
				</a>
			</li>
			
			<li>
				<a href="kaizen-busca.php">
					<i class="fas fa-chart-bar"></i>
					<span>Relatórios</span>
				</a>
			</li>
			
			<li class="sidebar-header">PRODUTOS & TROCAS</li>
			
			<li>
				<a href="catproduto-admin.php">
					<i class="fas fa-tags"></i>
					<span>Categorias de Produtos</span>
				</a>
			</li>
			
			<li>
				<a href="produto-admin.php">
					<i class="fas fa-gift"></i>
					<span>Produtos</span>
				</a>
			</li>
			
			<li>
				<a href="troca-admin.php">
					<i class="fas fa-exchange-alt"></i>
					<span>Trocas</span>
				</a>
			</li>
			
			<li class="sidebar-header">CONFIGURAÇÕES</li>
			
			<li>
				<a href="pg-editar.php?id_pg=101">
					<i class="fas fa-star"></i>
					<span>Tabela de Pontos</span>
				</a>
			</li>
			
			<li>
				<a href="login-editar.php">
					<i class="fas fa-key"></i>
					<span>Alterar Senha</span>
				</a>
			</li>
		</ul>
	</aside>
	
	<!-- Main Content -->
	<main class="admin-content">
		<!-- TemplateBeginEditable name="content" -->
		<!-- Conteúdo da página aqui -->
		<!-- TemplateEndEditable -->
	</main>
	
	<!-- Footer -->
	<footer class="admin-footer">
		<p>&copy; <?= date('Y') ?> Sistema Orion Kaizen - Desenvolvido por catenacom.com</p>
	</footer>
	
	<!-- jQuery (necessário para compatibilidade) -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- DataTables -->
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
	
	<!-- Select2 -->
	<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
	
	<!-- Input Mask & MaskMoney -->
	<script src="../lib/js/jquery.inputmask.bundle.min.js"></script>
	<script src="../lib/js/jquery.maskMoney.js"></script>
	
	<!-- Base Scripts -->
	<script>
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
			$('.sidebar-menu a').each(function() {
				var href = $(this).attr('href');
				if (href && href.indexOf(currentPage) !== -1 && currentPage !== '') {
					$(this).addClass('active');
				}
			});
			
			// Initialize Select2
			if ($.fn.select2) {
				$('.select2').select2({
					theme: 'bootstrap-5',
					width: '100%'
				});
			}
			
			// Initialize DataTables (se existir tabela com classe dataTable)
			if ($.fn.DataTable && $('.dataTable').length) {
				$('.dataTable').DataTable({
					responsive: true,
					language: {
						url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
					},
					pageLength: 25,
					order: [[0, 'desc']]
				});
			}
			
			// Confirmação de exclusão
			$(document).on('click', '.btn-delete', function(e) {
				if (!confirm('Tem certeza que deseja excluir este registro?')) {
					e.preventDefault();
					return false;
				}
			});
			
			// Máscaras
			if ($.fn.maskMoney) {
				$('.money').maskMoney({
					decimal: ',',
					thousands: '.',
					allowZero: true
				});
			}
		});
	</script>
	
	<!-- TemplateBeginEditable name="scripts" -->
	<!-- Espaço para scripts específicos da página -->
	<!-- TemplateEndEditable -->
</body>
</html>
