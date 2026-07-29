<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Página Inicial - Kaizen</title>
	
	<meta name="title" content="Kaizen - Sistema Orion"/>
	<meta name="author" content="Orion"/>
	<meta name="description" content="Sistema de Melhoria Contínua Kaizen"/>
	
	<link rel="shortcut icon" href="favicon.ico"/>
	
	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	
	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- CSS Customizado Orion -->
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<link rel="stylesheet" href="assets/css/estilos-v2.css"/>
	
	<style>
		/* Versão 2 - Design Moderno */
		body {
			font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
			background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
			min-height: 100vh;
		}
		
		/* Navbar com glassmorphism */
		.orion-navbar-v2 {
			background: rgba(255, 255, 255, 0.95) !important;
			backdrop-filter: blur(10px);
			-webkit-backdrop-filter: blur(10px);
			border-bottom: 1px solid rgba(0, 158, 227, 0.1);
			box-shadow: 0 4px 30px rgba(0, 0, 0, 0.05);
		}
		
		/* Card Hero com gradiente */
		.hero-card {
			background: linear-gradient(135deg, #009ee3 0%, #0088c7 100%);
			color: white;
			border-radius: 1.5rem;
			padding: 3rem 2rem;
			box-shadow: 0 20px 60px rgba(0, 158, 227, 0.3);
			margin-bottom: 3rem;
			position: relative;
			overflow: hidden;
		}
		
		.hero-card::before {
			content: '';
			position: absolute;
			top: -50%;
			right: -20%;
			width: 400px;
			height: 400px;
			background: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
		}
		
		.hero-card h1 {
			color: white;
			font-weight: 700;
			font-size: 2.5rem;
			margin-bottom: 0.5rem;
			position: relative;
			z-index: 1;
		}
		
		.hero-card p {
			font-size: 1.1rem;
			opacity: 0.95;
			position: relative;
			z-index: 1;
		}
		
		.badge-pontos-v2 {
			background: rgba(252, 215, 0, 1);
			color: #2c3e50;
			padding: 0.75rem 1.5rem;
			border-radius: 2rem;
			font-size: 1.1rem;
			font-weight: 600;
			display: inline-block;
			margin-top: 1rem;
			box-shadow: 0 4px 15px rgba(252, 215, 0, 0.4);
		}
		
		/* Cards de ação modernos */
		.action-card {
			background: white;
			border: none;
			border-radius: 1rem;
			padding: 1.75rem;
			margin-bottom: 1.5rem;
			transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
			cursor: pointer;
			text-decoration: none;
			display: block;
			position: relative;
			overflow: hidden;
		}
		
		.action-card::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			width: 4px;
			height: 100%;
			background: var(--orion-primary);
			transform: scaleY(0);
			transition: transform 0.3s ease;
		}
		
		.action-card:hover {
			transform: translateY(-8px);
			box-shadow: 0 12px 40px rgba(0, 158, 227, 0.15);
		}
		
		.action-card:hover::before {
			transform: scaleY(1);
		}
		
		.action-card-icon {
			width: 60px;
			height: 60px;
			background: linear-gradient(135deg, #009ee3 0%, #0088c7 100%);
			border-radius: 1rem;
			display: flex;
			align-items: center;
			justify-content: center;
			margin-bottom: 1rem;
			transition: all 0.3s ease;
		}
		
		.action-card:hover .action-card-icon {
			transform: scale(1.1) rotate(5deg);
		}
		
		.action-card-icon i {
			font-size: 1.75rem;
			color: white;
		}
		
		.action-card-title {
			font-size: 1.1rem;
			font-weight: 600;
			color: var(--orion-text);
			margin-bottom: 0.5rem;
		}
		
		.action-card-desc {
			font-size: 0.9rem;
			color: var(--orion-text-light);
			margin: 0;
		}
		
		/* Cards de gestão com cor diferenciada */
		.action-card.gestao .action-card-icon {
			background: linear-gradient(135deg, #00812e 0%, #027a2e 100%);
		}
		
		.action-card.gestao::before {
			background: var(--orion-success);
		}
		
		/* Card sistema legado (discreto) */
		.action-card.legacy-card {
			background: #f8f9fa;
			border: 1px dashed #dee2e6;
		}
		
		.action-card.legacy-card .action-card-icon {
			background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
		}
		
		.action-card.legacy-card::before {
			background: #6c757d;
		}
		
		.action-card.legacy-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 8px 25px rgba(108, 117, 125, 0.15);
		}
		
		/* Seção de gestão */
		.section-title {
			display: flex;
			align-items: center;
			gap: 0.75rem;
			margin: 3rem 0 2rem;
			font-size: 1.5rem;
			font-weight: 600;
			color: var(--orion-text);
		}
		
		.section-title i {
			width: 48px;
			height: 48px;
			background: linear-gradient(135deg, #009ee3 0%, #0088c7 100%);
			border-radius: 0.75rem;
			display: flex;
			align-items: center;
			justify-content: center;
			color: white;
			font-size: 1.25rem;
		}
		
		/* Animações suaves */
		@keyframes fadeInUp {
			from {
				opacity: 0;
				transform: translateY(30px);
			}
			to {
				opacity: 1;
				transform: translateY(0);
			}
		}
		
		.fade-in-up {
			animation: fadeInUp 0.6s ease-out backwards;
		}
		
		.fade-in-up:nth-child(1) { animation-delay: 0.1s; }
		.fade-in-up:nth-child(2) { animation-delay: 0.2s; }
		.fade-in-up:nth-child(3) { animation-delay: 0.3s; }
		.fade-in-up:nth-child(4) { animation-delay: 0.4s; }
		.fade-in-up:nth-child(5) { animation-delay: 0.5s; }
		.fade-in-up:nth-child(6) { animation-delay: 0.6s; }
		
		/* Badge no navbar */
		.navbar .badge-pontos-nav {
			background: linear-gradient(135deg, #fcd700 0%, #e0c100 100%);
			color: #2c3e50;
			padding: 0.4rem 0.9rem;
			border-radius: 2rem;
			font-weight: 600;
			font-size: 0.85rem;
		}
		
		/* Botão sair moderno */
		.btn-logout {
			background: linear-gradient(135deg, #009ee3 0%, #0088c7 100%);
			border: none;
			color: white;
			padding: 0.5rem 1.5rem;
			border-radius: 2rem;
			font-weight: 500;
			transition: all 0.3s ease;
		}
		
		.btn-logout:hover {
			transform: translateY(-2px);
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.4);
			color: white;
		}
		
		/* Responsividade */
		@media (max-width: 991.98px) {
			.hero-card h1 {
				font-size: 1.75rem;
			}
			
			.hero-card {
				padding: 2rem 1.5rem;
			}
		}
	</style>
	
	<!-- jQuery (mantido para compatibilidade) -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
</head>

<body>
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar orion-navbar-v2">
		<div class="container">
			<a class="navbar-brand" href="home.php">
				<img src="lib/img/logo-kaizen.png" alt="Kaizen" height="40" class="d-inline-block align-text-top">
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="home.php">
								<i class="fas fa-home"></i> Página Inicial
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="minhas-sugestoes.php">
								<i class="fab fa-wpforms"></i> Meus Kaizens
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="novo-kaizen.php">
								<i class="fas fa-plus-circle"></i> Novo Kaizen
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="categorias.php">
								<i class="fas fa-gift"></i> Trocar Pontos
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="minhas-trocas.php">
								<i class="fas fa-exchange-alt"></i> Minhas Trocas
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="extrato-pontos.php">
								<i class="far fa-list-alt"></i> Extrato de Pontos
							</a>
						</li>
						
						<?php if ($usuario['lider_colaborador'] == 1): ?>
							<li class="nav-item d-lg-none">
								<a class="nav-link" href="sugestoes-analisar.php">
									<i class="fas fa-tasks"></i> Gestor Kaizen
								</a>
							</li>
						<?php endif; ?>
						
						<?php if (!empty($usuario['nome_setor'])): ?>
							<li class="nav-item d-lg-none">
								<a class="nav-link" href="lider-analisar.php">
									<i class="fas fa-users"></i> Gestor Setor <?= $usuario['nome_setor'] ?>
								</a>
							</li>
						<?php endif; ?>
						
						<li class="nav-item d-lg-none">
							<hr class="dropdown-divider">
						</li>
						
						<li class="nav-item me-lg-3">
							<span class="nav-link">
								<i class="fas fa-user-circle"></i> 
								<strong><?= $usuario['nome_colaborador'] ?></strong>
								<br class="d-lg-none">
								<span class="badge-pontos-nav ms-2">
									<i class="fas fa-star"></i> 
									<?php 
										$pontos = $usuario['ponto_colaborador'];
										if ($pontos == 0) {
											echo '0 pontos';
										} elseif ($pontos == 1) {
											echo '1 ponto';
										} else {
											echo $pontos . ' pontos';
										}
									?>
								</span>
							</span>
						</li>
						
						<li class="nav-item">
							<a class="btn btn-logout" href="app/func_logout.php">
								<i class="fas fa-sign-out-alt"></i> Sair
							</a>
						</li>
						
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	
	<!-- Espaçamento navbar -->
	<div style="height: 90px;"></div>
	
	<!-- Conteúdo Principal -->
	<main class="container py-4">
		
		<!-- Mensagens de retorno -->
		<?php if (!empty($_GET['resp'])): ?>
			<div class="fade-in-up">
				<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
			</div>
		<?php endif; ?>
		
		<!-- Hero Card -->
		<div class="hero-card fade-in-up">
			<div class="row align-items-center">
				<div class="col-lg-8">
					<h1><i class="fas fa-hand-sparkles"></i> Olá, <?= $usuario['nome_colaborador'] ?>!</h1>
					<p class="mb-0">Bem-vindo ao Sistema Kaizen. Continue transformando ideias em resultados.</p>
					<span class="badge-pontos-v2">
						<i class="fas fa-trophy"></i> Você tem 
						<strong>
							<?php 
								$pontos = $usuario['ponto_colaborador'];
								if ($pontos == 0) {
									echo '0 pontos';
								} elseif ($pontos == 1) {
									echo '1 ponto';
								} else {
									echo $pontos . ' pontos';
								}
							?>
						</strong>
					</span>
				</div>
			</div>
		</div>
		
		<!-- Menu de Ações em Cards -->
		<div class="row">
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="minhas-sugestoes.php" class="action-card">
					<div class="action-card-icon">
						<i class="fab fa-wpforms"></i>
					</div>
					<h3 class="action-card-title">Meus Kaizens</h3>
					<p class="action-card-desc">Visualize e acompanhe suas sugestões de melhoria</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="novo-kaizen.php" class="action-card">
					<div class="action-card-icon">
						<i class="fas fa-plus-circle"></i>
					</div>
					<h3 class="action-card-title">Novo Kaizen</h3>
					<p class="action-card-desc">Crie uma nova sugestão de melhoria contínua</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="categorias.php" class="action-card">
					<div class="action-card-icon">
						<i class="fas fa-gift"></i>
					</div>
					<h3 class="action-card-title">Trocar Pontos</h3>
					<p class="action-card-desc">Resgate seus pontos por prêmios no catálogo</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="minhas-trocas.php" class="action-card">
					<div class="action-card-icon">
						<i class="fas fa-exchange-alt"></i>
					</div>
					<h3 class="action-card-title">Minhas Trocas</h3>
					<p class="action-card-desc">Histórico de resgates e prêmios recebidos</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="extrato-pontos.php" class="action-card">
					<div class="action-card-icon">
						<i class="far fa-list-alt"></i>
					</div>
					<h3 class="action-card-title">Extrato de Pontos</h3>
					<p class="action-card-desc">Acompanhe toda movimentação dos seus pontos</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="altera-senha.php" class="action-card">
					<div class="action-card-icon">
						<i class="fas fa-lock"></i>
					</div>
					<h3 class="action-card-title">Alterar Senha</h3>
					<p class="action-card-desc">Mantenha sua conta segura atualizando a senha</p>
				</a>
			</div>
			
			<div class="col-md-6 col-lg-4 fade-in-up">
				<a href="https://orionprovisorio2.websiteseguro.com" target="_blank" class="action-card legacy-card">
					<div class="action-card-icon">
						<i class="fas fa-history"></i>
					</div>
					<h3 class="action-card-title">Sistema Anterior</h3>
					<p class="action-card-desc">Consultar dados históricos do sistema V1</p>
				</a>
			</div>
		</div>
		
		<!-- Área de Gestão -->
		<?php if ($usuario['lider_colaborador'] == 1 || !empty($usuario['nome_setor'])): ?>
			<div class="section-title fade-in-up">
				<i class="fas fa-user-shield"></i>
				<span>Área de Gestão</span>
			</div>
			
			<div class="row">
				<?php if ($usuario['lider_colaborador'] == 1): ?>
					<div class="col-md-6 col-lg-4 fade-in-up">
						<a href="sugestoes-analisar.php" class="action-card gestao">
							<div class="action-card-icon">
								<i class="fas fa-tasks"></i>
							</div>
							<h3 class="action-card-title">Gestor Kaizen</h3>
							<p class="action-card-desc">Analise e aprove sugestões de melhoria</p>
						</a>
					</div>
				<?php endif; ?>
				
				<?php if (!empty($usuario['nome_setor'])): ?>
					<div class="col-md-6 col-lg-4 fade-in-up">
						<a href="lider-analisar.php" class="action-card gestao">
							<div class="action-card-icon">
								<i class="fas fa-users"></i>
							</div>
							<h3 class="action-card-title">Gestor Setor</h3>
							<p class="action-card-desc">Gerencie o setor <?= $usuario['nome_setor'] ?></p>
						</a>
					</div>
				<?php endif; ?>
			</div>
		<?php endif; ?>
		
	</main>
	
	<!-- Footer -->
	<footer class="text-center py-4 mt-5">
		<div class="container">
			<p class="text-muted mb-0">
				<small>Desenvolvido por catenacom.com | Versão 2.0</small>
			</p>
		</div>
	</footer>
	
	<!-- Bootstrap JS -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- Scripts -->
	<script src="lib/js/functions.js"></script>
</body>
</html>
