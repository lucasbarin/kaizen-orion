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
	<title>Extrato de Pontos - Sistema Orion</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<link rel="stylesheet" href="assets/css/estilos-v2.css"/>
	<style>
		.timeline { position: relative; padding: 2rem 0; }
		.timeline-item { position: relative; padding-left: 3rem; padding-bottom: 2rem; border-left: 3px solid var(--orion-primary); }
		.timeline-item:last-child { border-left-color: transparent; }
		.timeline-icon { position: absolute; left: -1.25rem; width: 2.5rem; height: 2.5rem; border-radius: 50%; background: linear-gradient(135deg, var(--orion-primary), var(--orion-primary-dark)); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.2rem; box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3); }
		.timeline-content { background: white; border-radius: 0.75rem; padding: 1.5rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); }
		.points-positive { color: #28a745; font-weight: 700; font-size: 1.2rem; }
		.points-negative { color: #dc3545; font-weight: 700; font-size: 1.2rem; }
	</style>
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
						<li class="nav-item d-lg-none"><a class="nav-link active" href="extrato-pontos.php"><i class="far fa-list-alt"></i> Extrato de Pontos</a></li>
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
				<li class="breadcrumb-item active">Extrato de Pontos</li>
			</ol>
		</nav>
		
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<div class="row mb-4">
			<div class="col-lg-8">
				<h1 class="display-5 fw-bold text-primary mb-2"><i class="far fa-list-alt"></i> Extrato de Pontos</h1>
				<p class="text-muted">Histórico completo de movimentações</p>
			</div>
			<div class="col-lg-4 text-lg-end">
				<div class="d-inline-block" style="background: linear-gradient(135deg, var(--orion-secondary) 0%, #e0c100 100%); padding: 1.5rem; border-radius: 1rem; box-shadow: 0 4px 15px rgba(252, 215, 0, 0.3);">
					<small class="d-block" style="color: #2c3e50;">Saldo Atual</small>
					<h2 class="mb-0" style="color: #2c3e50; font-weight: 700;"><i class="fas fa-star"></i> <?= $usuario['ponto_colaborador'] ?></h2>
				</div>
			</div>
		</div>
		
		<?php
		$sql = sql("SELECT * FROM log WHERE usu_log = ".$_SESSION['usu']." ORDER BY id_log DESC", $con);
		if (mysqli_num_rows($sql)):
		?>
		<div class="timeline">
			<?php while ($dados = mysqli_fetch_assoc($sql)): ?>
			<div class="timeline-item">
				<div class="timeline-icon">
					<?php if($dados['ponto_log'] > 0): ?>
						<i class="fas fa-plus"></i>
					<?php else: ?>
						<i class="fas fa-minus"></i>
					<?php endif; ?>
				</div>
				<div class="timeline-content">
					<div class="row align-items-center">
						<div class="col-md-8">
							<h6 class="mb-2"><?= nl2br($dados['descricao_log']) ?></h6>
							<small class="text-muted"><i class="far fa-calendar"></i> <?= date("d/m/Y H:i:s", strtotime($dados['data_log'])); ?></small>
						</div>
						<div class="col-md-4 text-md-end mt-3 mt-md-0">
							<?php if($dados['ponto_log'] > 0): ?>
								<div class="points-positive"><i class="fas fa-arrow-up"></i> +<?= $dados['ponto_log'] ?> pontos</div>
							<?php else: ?>
								<div class="points-negative"><i class="fas fa-arrow-down"></i> <?= $dados['ponto_log'] ?> pontos</div>
							<?php endif; ?>
							<small class="text-muted d-block mt-1">Saldo: <?= $dados['saldo_log'] ?> pts</small>
						</div>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		<?php else: ?>
		<div class="text-center py-5">
			<i class="far fa-list-alt" style="font-size: 5rem; color: var(--orion-primary); opacity: 0.3;"></i>
			<h3 class="mt-4">Nenhuma movimentação registrada</h3>
			<p class="text-muted">Seu extrato de pontos está vazio.</p>
		</div>
		<?php endif; ?>
	</main>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
