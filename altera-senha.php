<?php
include("app/conexao8.php");
include("app/funcoes8.php");
$pgAltera = 1;
include("app/sessao.php");
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Alterar Senha - Sistema Orion</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<script src="lib/js/jquery-1.11.3.min.js"></script>
</head>
<body class="orion-body-gradient">
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar-glass">
		<div class="container">
			<a class="navbar-brand" href="home.php"><img src="lib/img/logo-kaizen.png" alt="Kaizen" height="40"></a>
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"><span class="navbar-toggler-icon"></span></button>
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						<li class="nav-item d-lg-none"><a class="nav-link" href="home.php"><i class="fas fa-home"></i> Página Inicial</a></li>
						<li class="nav-item d-lg-none"><a class="nav-link active" href="altera-senha.php"><i class="fas fa-key"></i> Alterar Senha</a></li>
						<li class="nav-item d-lg-none"><hr class="dropdown-divider"></li>
						<li class="nav-item me-lg-3">
							<span class="nav-link"><i class="fas fa-user-circle"></i> <strong><?= $usuario['nome_colaborador'] ?></strong><br class="d-lg-none"><span class="badge-pontos ms-2"><i class="fas fa-star"></i> <?php $pontos = $usuario['ponto_colaborador']; echo ($pontos == 0) ? '0 pontos' : (($pontos == 1) ? '1 ponto' : $pontos . ' pontos'); ?></span></span>
						</li>
						<li class="nav-item"><a class="btn btn-outline-primary" href="app/func_logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
					<?php endif; ?>
				</ul>
			</div>
		</div>
	</nav>
	
	<div style="height: 80px;"></div>
	
	<main class="container py-5">
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
				<li class="breadcrumb-item active">Alterar Senha</li>
			</ol>
		</nav>
		
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<div class="row justify-content-center">
			<div class="col-lg-6">
				<div class="card shadow-sm" style="border: none; border-radius: 1rem; overflow: hidden;">
					<div class="card-header text-white" style="background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%); padding: 2rem;">
						<h3 class="mb-0"><i class="fas fa-key"></i> Alterar Senha</h3>
						<p class="mb-0 mt-2 opacity-75">Atualize suas credenciais de acesso</p>
					</div>
					<div class="card-body p-4">
						<form action="app/func_altera_senha.php" method="post" id="formSenha">
							
							<div class="mb-4">
								<label for="senha_atual" class="form-label fw-bold">
									<i class="fas fa-lock"></i> Senha Atual *
								</label>
								<input type="password" name="senha_atual" id="senha_atual" class="form-control form-control-lg" required placeholder="Digite sua senha atual">
							</div>
							
							<div class="mb-4">
								<label for="senha_nova" class="form-label fw-bold">
									<i class="fas fa-lock-open"></i> Nova Senha *
								</label>
								<input type="password" name="senha_nova" id="senha_nova" class="form-control form-control-lg" required placeholder="Digite a nova senha" minlength="6">
								<div class="form-text">Mínimo de 6 caracteres</div>
							</div>
							
							<div class="mb-4">
								<label for="senha_confirma" class="form-label fw-bold">
									<i class="fas fa-check-double"></i> Confirmar Nova Senha *
								</label>
								<input type="password" name="senha_confirma" id="senha_confirma" class="form-control form-control-lg" required placeholder="Digite novamente a nova senha">
							</div>
							
							<div class="d-grid gap-2">
								<button type="submit" class="btn btn-primary btn-lg">
									<i class="fas fa-check-circle"></i> Alterar Senha
								</button>
								<a href="home.php" class="btn btn-outline-secondary btn-lg">
									<i class="fas fa-times"></i> Cancelar
								</a>
							</div>
							
						</form>
					</div>
				</div>
			</div>
		</div>
	</main>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	<script>
	$('#formSenha').on('submit', function(e){
		var nova = $('#senha_nova').val();
		var confirma = $('#senha_confirma').val();
		
		if(nova !== confirma){
			e.preventDefault();
			alert('A nova senha e a confirmação não coincidem!');
			return false;
		}
		
		if(nova.length < 6){
			e.preventDefault();
			alert('A nova senha deve ter no mínimo 6 caracteres!');
			return false;
		}
	});
	</script>
</body>
</html>
