<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<!-- TemplateBeginEditable name="doctitle" -->
	<title>Kaizen - Sistema Orion</title>
	<!-- TemplateEndEditable -->
	
	<meta name="title" content="Kaizen - Sistema Orion"/>
	<meta name="author" content="Orion"/>
	<meta name="description" content="Sistema de Melhoria Contínua Kaizen"/>
	
	<link rel="shortcut icon" href="../favicon.ico"/>
	
	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	
	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- CSS Customizado Orion -->
	<link rel="stylesheet" href="../lib/css/orion-custom.css"/>
	
	<!-- jQuery (mantido para compatibilidade com scripts existentes) -->
	<script src="../lib/js/jquery-1.11.3.min.js"></script>
	<script src="../lib/js/jquery.inputmask.bundle.min.js"></script> 
	<script src="../lib/js/jquery.maskMoney.js"></script>
	
	<!-- TemplateBeginEditable name="head" -->
	<!-- TemplateEndEditable -->
</head>

<body class="orion-body-gradient">
	<!-- Navbar Desktop/Mobile Responsiva com Glassmorphism -->
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar-glass">
		<div class="container">
			<!-- Logo -->
			<a class="navbar-brand" href="../home.php">
				<img src="../lib/img/logo-kaizen.png" alt="Kaizen" height="40" class="d-inline-block align-text-top">
			</a>
			
			<!-- Botão Menu Mobile -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<!-- Menu -->
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						
						<!-- Links principais (Desktop e Mobile) -->
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../home.php">
								<i class="fas fa-home"></i> Página Inicial
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../minhas-sugestoes.php">
								<i class="fab fa-wpforms"></i> Meus Kaizens
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../novo-kaizen.php">
								<i class="fas fa-plus-circle"></i> Novo Kaizen
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../categorias.php">
								<i class="fas fa-gift"></i> Trocar Pontos
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../minhas-trocas.php">
								<i class="fas fa-exchange-alt"></i> Minhas Trocas
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="../extrato-pontos.php">
								<i class="far fa-list-alt"></i> Extrato de Pontos
							</a>
						</li>
						
						<?php if ($usuario['lider_colaborador'] == 1): ?>
							<li class="nav-item d-lg-none">
								<a class="nav-link" href="../sugestoes-analisar.php">
									<i class="fas fa-tasks"></i> Gestor Kaizen
								</a>
							</li>
						<?php endif; ?>
						
						<?php if (!empty($usuario['nome_setor'])): ?>
							<li class="nav-item d-lg-none">
								<a class="nav-link" href="../lider-analisar.php">
									<i class="fas fa-users"></i> Gestor Setor <?= $usuario['nome_setor'] ?>
								</a>
							</li>
						<?php endif; ?>
						
						<li class="nav-item d-lg-none">
							<hr class="dropdown-divider">
						</li>
						
						<!-- Informações do usuário -->
						<li class="nav-item me-lg-3">
							<span class="nav-link">
								<i class="fas fa-user-circle"></i> 
								<strong><?= $usuario['nome_colaborador'] ?></strong>
								<br class="d-lg-none">
								<span class="badge-pontos ms-2">
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
						
						<!-- Botão Sair -->
						<li class="nav-item">
							<a class="btn btn-outline-primary" href="../app/func_logout.php">
								<i class="fas fa-sign-out-alt"></i> Sair
							</a>
						</li>
						
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	
	<!-- Espaçamento para navbar fixed -->
	<div style="height: 80px;"></div>
	
	<!-- Conteúdo Principal -->
	<main class="container-fluid py-4">
		<div class="container">
			<!-- TemplateBeginEditable name="conteudo" -->
			<div class="alert alert-info">
				<i class="fas fa-info-circle"></i> 
				Conteúdo da página aqui
			</div>
			<!-- TemplateEndEditable -->
		</div>
	</main>
	
	<!-- Footer -->
	<footer class="orion-footer mt-5 py-3 border-top">
		<div class="container text-center text-muted">
			<small>Desenvolvido por catenacom.com</small>
		</div>
	</footer>
	
	<!-- Bootstrap 5.3 JS Bundle (inclui Popper) -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- Scripts customizados -->
	<script src="../lib/js/functions.js"></script>
	
	<!-- TemplateBeginEditable name="scripts" -->
	<!-- TemplateEndEditable -->
</body>
</html>
