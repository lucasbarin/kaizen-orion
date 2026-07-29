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
	<title>Minhas Trocas - Sistema Orion</title>
	<link rel="shortcut icon" href="favicon.ico"/>
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	<style>
		.troca-card { background: white; border-radius: 0.75rem; padding: 1.5rem; margin-bottom: 1rem; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06); border-left: 4px solid var(--orion-primary); transition: all 0.3s ease; }
		.troca-card:hover { transform: translateY(-4px); box-shadow: 0 8px 30px rgba(0, 158, 227, 0.12); }
		.troca-number { font-size: 1.5rem; font-weight: 700; color: var(--orion-primary); }
		.status-aguardando { background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%); color: #000; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; }
		.status-finalizado { background: linear-gradient(135deg, #28a745 0%, #20924c 100%); color: #fff; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; }
		.status-cancelado { background: linear-gradient(135deg, #dc3545 0%, #c82333 100%); color: #fff; padding: 0.5rem 1rem; border-radius: 2rem; font-weight: 600; font-size: 0.85rem; }
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
						<li class="nav-item d-lg-none"><a class="nav-link" href="categorias.php"><i class="fas fa-gift"></i> Trocar Pontos</a></li>
						<li class="nav-item d-lg-none"><a class="nav-link active" href="minhas-trocas.php"><i class="fas fa-exchange-alt"></i> Minhas Trocas</a></li>
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
				<li class="breadcrumb-item active">Minhas Trocas</li>
			</ol>
		</nav>
		
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<div class="row mb-4">
			<div class="col-12">
				<h1 class="display-5 fw-bold text-primary mb-2"><i class="fas fa-exchange-alt"></i> Histórico de Trocas</h1>
				<p class="text-muted">Acompanhe todas as suas trocas de pontos</p>
			</div>
		</div>
		
		<?php
		$sql = sql("SELECT * FROM logtroca 
			LEFT JOIN colaborador ON colaborador.id_colaborador = logtroca.id_colaborador
			LEFT JOIN produto ON produto.id_produto = logtroca.id_produto
			WHERE logtroca.id_colaborador = ".$_SESSION['usu']."
			ORDER BY id_logtroca DESC", $con);
		
		if (mysqli_num_rows($sql)):
		?>
		
		<!-- Desktop: Tabela -->
		<div class="d-none d-lg-block">
			<div class="table-responsive">
				<table class="table table-hover align-middle">
					<thead style="background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%); color: white;">
						<tr>
							<th width="100">NÚMERO</th>
							<th>PRODUTO</th>
							<th width="200">DATA</th>
							<th width="150" class="text-center">STATUS</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($dados = mysqli_fetch_assoc($sql)): ?>
						<tr>
							<td><span class="troca-number">#<?= $dados['id_logtroca'] ?></span></td>
							<td><strong><?= $dados['nome_logtroca'] ?></strong><br><small class="text-muted"><?= $dados['ponto_logtroca'] ?> pontos</small></td>
							<td><?= date("d/m/Y H:i:s", strtotime($dados['data_logtroca'])); ?></td>
							<td class="text-center">
								<?php
								if ($dados['status_logtroca'] == 2) echo '<span class="status-finalizado"><i class="fas fa-check-circle"></i> Finalizado</span>';
								elseif ($dados['status_logtroca'] == 3) echo '<span class="status-cancelado"><i class="fas fa-times-circle"></i> Cancelado</span>';
								else echo '<span class="status-aguardando"><i class="fas fa-clock"></i> Aguardando</span>';
								?>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- Mobile: Cards -->
		<div class="d-lg-none">
			<?php
			$sql = sql("SELECT * FROM logtroca 
				LEFT JOIN colaborador ON colaborador.id_colaborador = logtroca.id_colaborador
				LEFT JOIN produto ON produto.id_produto = logtroca.id_produto
				WHERE logtroca.id_colaborador = ".$_SESSION['usu']."
				ORDER BY id_logtroca DESC", $con);
			
			while ($dados = mysqli_fetch_assoc($sql)):
			?>
			<div class="troca-card">
				<div class="d-flex justify-content-between align-items-start mb-3">
					<div><span class="troca-number">#<?= $dados['id_logtroca'] ?></span></div>
					<div>
						<?php
						if ($dados['status_logtroca'] == 2) echo '<span class="status-finalizado"><i class="fas fa-check-circle"></i> Finalizado</span>';
						elseif ($dados['status_logtroca'] == 3) echo '<span class="status-cancelado"><i class="fas fa-times-circle"></i> Cancelado</span>';
						else echo '<span class="status-aguardando"><i class="fas fa-clock"></i> Aguardando</span>';
						?>
					</div>
				</div>
				<h6 class="mb-2"><i class="fas fa-gift text-primary"></i> <strong><?= $dados['nome_logtroca'] ?></strong></h6>
				<p class="text-muted mb-2"><i class="fas fa-star text-warning"></i> <?= $dados['ponto_logtroca'] ?> pontos</p>
				<p class="text-muted mb-0"><i class="far fa-calendar"></i> <?= date("d/m/Y H:i:s", strtotime($dados['data_logtroca'])); ?></p>
			</div>
			<?php endwhile; ?>
		</div>
		
		<?php else: ?>
		<div class="text-center py-5">
			<i class="fas fa-exchange-alt" style="font-size: 5rem; color: var(--orion-primary); opacity: 0.3;"></i>
			<h3 class="mt-4">Nenhuma troca realizada</h3>
			<p class="text-muted mb-4">Você ainda não trocou pontos. Visite o catálogo!</p>
			<a href="categorias.php" class="btn btn-primary btn-lg"><i class="fas fa-gift me-2"></i> Ver Catálogo</a>
		</div>
		<?php endif; ?>
	</main>
	
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
