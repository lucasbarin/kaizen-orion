<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

// Lógica de ordenação
$ordem_get = $_GET['ordem'] ?? 0;
$ordem2_get = $_GET['ordem2'] ?? 2;

if ($ordem_get >= 1 && $ordem_get <= 4){
	$ordem = $ordem_get; // Garantir que está sempre definido
	if ($ordem2_get == 1){
		$ordem2 = 1;
		$or2 = "ASC";
	} else {
		$ordem2 = 2;
		$or2 = "DESC";
	}
	
	switch($ordem_get){
		case 2:
			$ordem = 2;
			$or = "nome_tipo ";
			break;
			
		case 3:
			$ordem = 3;
			$or = "datacadastro_kaizen ";
			break;
			
		case 4:
			$ordem = 4;
			$or = "status_kaizen ";
			break;
			
		default:
			$ordem = 1;
			$or = "id_kaizen ";
			break;
	}
	
	$filtro = $or.$or2;
	
} else {
	$ordem = 1;
	$ordem2 = 2;
	$filtro = "id_kaizen DESC";
}
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Meus Kaizens - Sistema Orion</title>
	
	<meta name="title" content="Kaizen - Sistema Orion"/>
	<meta name="author" content="Orion"/>
	<meta name="description" content="Sistema de Melhoria Contínua Kaizen"/>
	
	<link rel="shortcut icon" href="favicon.ico"/>
	
	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	
	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- CSS Customizado Orion -->
	<link rel="stylesheet" href="lib/css/orion-custom.css"/>
	<link rel="stylesheet" href="assets/css/estilos-v2.css"/>
	
	<!-- jQuery -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	
	<style>
		/* Estilos para cards de kaizen */
		.kaizen-card {
			background: white;
			border-radius: 0.75rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
			transition: all 0.3s ease;
			border-left: 4px solid var(--orion-primary);
			margin-bottom: 1rem;
			overflow: hidden;
		}
		
		.kaizen-card:hover {
			transform: translateY(-4px);
			box-shadow: 0 8px 30px rgba(0, 158, 227, 0.12);
		}
		
		.kaizen-card-body {
			padding: 1.5rem;
		}
		
		.kaizen-number {
			font-size: 1.5rem;
			font-weight: 700;
			color: var(--orion-primary);
		}
		
		.kaizen-type {
			font-size: 0.9rem;
			color: var(--orion-text);
			margin-bottom: 0.5rem;
		}
		
		.kaizen-date {
			font-size: 0.85rem;
			color: var(--orion-text-light);
		}
		
		.filter-panel {
			background: white;
			border-radius: 0.75rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
			padding: 1.5rem;
			margin-bottom: 2rem;
		}
		
		.empty-state {
			text-align: center;
			padding: 4rem 2rem;
			background: white;
			border-radius: 0.75rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
		}
		
		.empty-state i {
			font-size: 5rem;
			color: var(--orion-primary);
			opacity: 0.3;
			margin-bottom: 1.5rem;
		}
		
		/* Badges de status modernos */
		.badge-status {
			padding: 0.5rem 1rem;
			border-radius: 2rem;
			font-weight: 600;
			font-size: 0.85rem;
		}
		
		.status-pendente {
			background: linear-gradient(135deg, #ffc107 0%, #ffb300 100%);
			color: #000;
		}
		
		.status-aprovado {
			background: linear-gradient(135deg, #28a745 0%, #20924c 100%);
			color: #fff;
		}
		
		.status-rejeitado {
			background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
			color: #fff;
		}
		
		.status-rascunho {
			background: linear-gradient(135deg, #6c757d 0%, #5a6268 100%);
			color: #fff;
		}
		
		/* Tabela responsiva moderna */
		@media (min-width: 992px) {
			.kaizen-table-header {
				background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
				color: white;
				padding: 1rem;
				border-radius: 0.75rem 0.75rem 0 0;
				font-weight: 600;
			}
		}
	</style>
</head>

<body class="orion-body-gradient">
	<!-- Navbar Desktop/Mobile Responsiva com Glassmorphism -->
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar-glass">
		<div class="container">
			<!-- Logo -->
			<a class="navbar-brand" href="home.php">
				<img src="lib/img/logo-kaizen.png" alt="Kaizen" height="40" class="d-inline-block align-text-top">
			</a>
			
			<!-- Botão Menu Mobile -->
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<!-- Menu -->
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						
						<!-- Links principais (Mobile) -->
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="home.php">
								<i class="fas fa-home"></i> Página Inicial
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link active" href="minhas-sugestoes.php">
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
							<a class="btn btn-outline-primary" href="app/func_logout.php">
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
	<main class="container py-5">
		
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
				<li class="breadcrumb-item active" aria-current="page">Meus Kaizens</li>
			</ol>
		</nav>
		
		<!-- Mensagens -->
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<!-- Header com título -->
		<div class="row mb-4">
			<div class="col-12">
				<h1 class="display-5 fw-bold text-primary mb-2">
					<i class="fab fa-wpforms"></i> Meus Kaizens
				</h1>
				<p class="text-muted">Visualize e acompanhe todos os seus formulários enviados</p>
			</div>
		</div>
		
		<!-- Painel de Filtros -->
		<div class="filter-panel">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-md-4">
					<label for="ordem" class="form-label fw-bold">
						<i class="fas fa-sort"></i> Ordenar por
					</label>
					<select name="ordem" id="ordem" class="form-select">
						<option <?php if ($ordem == 1) echo 'selected'; ?> value="1">Número</option>
						<option <?php if ($ordem == 2) echo 'selected'; ?> value="2">Tipo</option>
						<option <?php if ($ordem == 3) echo 'selected'; ?> value="3">Data</option>
						<option <?php if ($ordem == 4) echo 'selected'; ?> value="4">Status</option>
					</select>
				</div>
				<div class="col-md-3">
					<label for="ordem2" class="form-label fw-bold">
						<i class="fas fa-arrows-alt-v"></i> Direção
					</label>
					<select name="ordem2" id="ordem2" class="form-select">
						<option <?php if ($ordem2 == 1) echo 'selected'; ?> value="1">Crescente</option>
						<option <?php if ($ordem2 == 2) echo 'selected'; ?> value="2">Decrescente</option>
					</select>
				</div>
				<div class="col-md-3">
					<button type="submit" class="btn btn-primary w-100">
						<i class="fas fa-filter"></i> Aplicar Filtros
					</button>
				</div>
				<div class="col-md-2">
					<a href="novo-kaizen.php" class="btn btn-success w-100">
						<i class="fas fa-plus"></i> Novo
					</a>
				</div>
			</form>
		</div>
		
		<?php
		$sql = sql("SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp FROM kaizen
		  LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
		  LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
		  WHERE kaizen.colaborador_kaizen = ".$_SESSION['usu']." ORDER BY ".$filtro, $con);
		
		if (mysqli_num_rows($sql)):
		?>
		
		<!-- View Desktop: Tabela -->
		<div class="d-none d-lg-block">
			<div class="table-responsive">
				<table class="table table-hover align-middle">
					<thead class="kaizen-table-header">
						<tr>
							<th width="100">NÚMERO</th>
							<th>TIPO</th>
							<th width="180">DATA</th>
							<th width="150" class="text-center">STATUS</th>
							<th width="150" class="text-center">AÇÕES</th>
						</tr>
					</thead>
					<tbody>
						<?php while ($dados = mysqli_fetch_assoc($sql)): ?>
						<tr>
							<td>
								<span class="kaizen-number">#<?= $dados['id_kaizen'] ?></span>
							</td>
							<td>
								<strong><?= $dados['nome_tipo'] ?></strong>
								<?php if(!empty($dados['nome_comp'])): ?>
									<br><small class="text-muted"><?= $dados['nome_comp'] ?></small>
								<?php endif; ?>
							</td>
							<td>
								<?php data_print($dados['datacadastro_kaizen']); ?>
								<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00'): ?>
									<br><small class="text-muted">Concluído: <?php data_print($dados['dataconclusao_kaizen']); ?></small>
								<?php endif; ?>
							</td>
							<td class="text-center">
								<?php 
								$status = $dados['status_kaizen'];
								switch($status){
									case 1:
										echo '<span class="badge-status status-pendente"><i class="fas fa-clock"></i> Pendente</span>';
										break;
									case 2:
										echo '<span class="badge-status status-aprovado"><i class="fas fa-check-circle"></i> Aprovado</span>';
										break;
									case 3:
										echo '<span class="badge-status status-rejeitado"><i class="fas fa-times-circle"></i> Rejeitado</span>';
										break;
									case 4:
										echo '<span class="badge-status status-rascunho"><i class="fas fa-edit"></i> Rascunho</span>';
										break;
									default:
										echo '<span class="badge bg-secondary">Indefinido</span>';
								}
								?>
							</td>
							<td class="text-center">
								<?php if ($dados['status_kaizen'] != 4): ?>
									<a class="btn btn-sm btn-primary" href="visualizar-kaizen.php?kaizen=<?= $dados['id_kaizen'] ?>">
										<i class="fas fa-eye"></i> Visualizar
									</a>
								<?php else: ?>
									<a class="btn btn-sm btn-warning" href="kaizen-editar.php?kaizen=<?= $dados['id_kaizen'] ?>">
										<i class="fas fa-edit"></i> Alterar
									</a>
								<?php endif; ?>
							</td>
						</tr>
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
		
		<!-- View Mobile: Cards -->
		<div class="d-lg-none">
			<?php
			// Resetar resultado para mobile
			$sql = sql("SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp FROM kaizen
			  LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
			  LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
			  WHERE kaizen.colaborador_kaizen = ".$_SESSION['usu']." ORDER BY ".$filtro, $con);
			
			while ($dados = mysqli_fetch_assoc($sql)):
			?>
			<div class="kaizen-card">
				<div class="kaizen-card-body">
					<div class="d-flex justify-content-between align-items-start mb-3">
						<div>
							<span class="kaizen-number">#<?= $dados['id_kaizen'] ?></span>
						</div>
						<div>
							<?php 
							$status = $dados['status_kaizen'];
							switch($status){
								case 1:
									echo '<span class="badge-status status-pendente"><i class="fas fa-clock"></i> Pendente</span>';
									break;
								case 2:
									echo '<span class="badge-status status-aprovado"><i class="fas fa-check-circle"></i> Aprovado</span>';
									break;
								case 3:
									echo '<span class="badge-status status-rejeitado"><i class="fas fa-times-circle"></i> Rejeitado</span>';
									break;
								case 4:
									echo '<span class="badge-status status-rascunho"><i class="fas fa-edit"></i> Rascunho</span>';
									break;
							}
							?>
						</div>
					</div>
					
					<div class="kaizen-type">
						<i class="fas fa-tag text-primary"></i> <strong><?= $dados['nome_tipo'] ?></strong>
						<?php if(!empty($dados['nome_comp'])): ?>
							- <?= $dados['nome_comp'] ?>
						<?php endif; ?>
					</div>
					
					<div class="kaizen-date mb-3">
						<i class="far fa-calendar"></i> <?php data_print($dados['datacadastro_kaizen']); ?>
						<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00'): ?>
							<br><i class="fas fa-check"></i> Concluído: <?php data_print($dados['dataconclusao_kaizen']); ?>
						<?php endif; ?>
					</div>
					
					<div class="d-grid">
						<?php if ($dados['status_kaizen'] != 4): ?>
							<a class="btn btn-primary" href="visualizar-kaizen.php?kaizen=<?= $dados['id_kaizen'] ?>">
								<i class="fas fa-eye"></i> Visualizar Detalhes
							</a>
						<?php else: ?>
							<a class="btn btn-warning" href="kaizen-editar.php?kaizen=<?= $dados['id_kaizen'] ?>">
								<i class="fas fa-edit"></i> Alterar Rascunho
							</a>
						<?php endif; ?>
					</div>
				</div>
			</div>
			<?php endwhile; ?>
		</div>
		
		<?php else: ?>
		
		<!-- Estado vazio -->
		<div class="empty-state">
			<i class="fab fa-wpforms"></i>
			<h3 class="mb-3">Nenhum Kaizen encontrado</h3>
			<p class="text-muted mb-4">Você ainda não enviou nenhum formulário. Que tal compartilhar sua primeira ideia de melhoria?</p>
			<a href="novo-kaizen.php" class="btn btn-primary btn-lg">
				<i class="fas fa-plus-circle me-2"></i> Enviar Primeiro Kaizen
			</a>
		</div>
		
		<?php endif; ?>
		
	</main>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>
