<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

if (empty($usuario['nome_setor'])){
	volta("erro", "Área restrita para líderes de setor. Você não tem permissão para acessá-lo!", "home.php");
}

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
<!DOCTYPE html>
<html lang="pt-br"><!-- InstanceBegin template="/Templates/kaizen-bs5.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Gestor de Setor - Analisar Kaizens | Sistema Orion Kaizen</title>
	<!-- InstanceEndEditable -->
	
	<meta name="description" content="Sistema de Gestão Kaizen - Melhoria Contínua">
	<link rel="shortcut icon" href="favicon.ico"/>
	
	<!-- Bootstrap 5.3.2 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- CSS Customizado Orion -->
	<link rel="stylesheet" href="lib/css/orion-custom.css">
	<link rel="stylesheet" href="assets/css/estilos-v2.css">
	
	<!-- jQuery (compatibilidade) -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	
	<!-- InstanceBeginEditable name="head" -->
	<style>
		.setor-header {
			background: linear-gradient(135deg, #009ee3 0%, #0088c7 100%);
			color: white;
			padding: 2rem;
			border-radius: 1rem;
			margin-bottom: 2rem;
			box-shadow: 0 4px 20px rgba(0, 158, 227, 0.2);
		}
		
		.setor-header h1 {
			font-size: 2rem;
			font-weight: 700;
			margin: 0;
			display: flex;
			align-items: center;
			gap: 1rem;
		}
		
		.setor-header p {
			margin: 0.5rem 0 0;
			opacity: 0.9;
		}
		
		.filter-card {
			background: white;
			border-radius: 1rem;
			padding: 1.5rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
			margin-bottom: 2rem;
		}
		
		.kaizen-table-responsive {
			background: white;
			border-radius: 1rem;
			overflow: hidden;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
		}
		
		.table-header-setor {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			color: white;
			padding: 1rem;
			font-weight: 600;
		}
		
		/* Mobile Cards */
		.kaizen-mobile-card {
			background: white;
			border-radius: 1rem;
			padding: 1.5rem;
			margin-bottom: 1rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
			transition: all 0.3s ease;
		}
		
		.kaizen-mobile-card:hover {
			box-shadow: 0 4px 20px rgba(0, 158, 227, 0.15);
			transform: translateY(-2px);
		}
		
		.mobile-card-header {
			display: flex;
			justify-content: space-between;
			align-items: start;
			margin-bottom: 1rem;
			padding-bottom: 1rem;
			border-bottom: 2px solid #f0f0f0;
		}
		
		.mobile-card-title {
			font-size: 1.1rem;
			font-weight: 600;
			color: var(--orion-text);
			margin: 0;
		}
		
		.mobile-card-info {
			display: grid;
			gap: 0.75rem;
			margin-bottom: 1rem;
		}
		
		.info-row {
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		
		.info-row i {
			width: 20px;
			color: var(--orion-primary);
		}
		
		.info-row strong {
			min-width: 80px;
		}
	</style>
	<!-- InstanceEndEditable -->
</head>

<body class="orion-body-gradient">
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg orion-navbar-glass sticky-top">
		<div class="container">
			<a class="navbar-brand" href="home.php">
				<img src="lib/img/logo-kaizen.png" alt="Orion Kaizen" style="height: 40px;">
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-center">
					<li class="nav-item d-lg-none">
						<a class="nav-link" href="home.php">
							<i class="fas fa-home"></i> Início
						</a>
					</li>
					<li class="nav-item d-lg-none">
						<a class="nav-link" href="minhas-sugestoes.php">
							<i class="fas fa-lightbulb"></i> Meus Kaizens
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
							<i class="fas fa-list"></i> Extrato
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
						<a class="nav-link active" href="lider-analisar.php">
							<i class="fas fa-users"></i> Gestor Setor
						</a>
					</li>
					<?php endif; ?>
					
					<li class="nav-item d-lg-none">
						<hr class="dropdown-divider">
					</li>
					
					<li class="nav-item dropdown">
						<a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">
							<i class="fas fa-user-circle"></i> <?= $usuario['nome_colaborador'] ?>
							<span class="badge-pontos ms-2"><?= $usuario['ponto_colaborador'] ?> pts</span>
						</a>
						<ul class="dropdown-menu dropdown-menu-end">
							<li><a class="dropdown-item" href="altera-senha.php"><i class="fas fa-key"></i> Alterar Senha</a></li>
							<li><hr class="dropdown-divider"></li>
							<li><a class="dropdown-item" href="app/func_logout.php"><i class="fas fa-sign-out-alt"></i> Sair</a></li>
						</ul>
					</li>
				</ul>
			</div>
		</div>
	</nav>

	<!-- Conteúdo Principal -->
	<main class="container my-4">
		<!-- InstanceBeginEditable name="conteudo" -->
		
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb" class="mb-3">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
				<li class="breadcrumb-item active" aria-current="page">Gestor Setor <?= $usuario['nome_setor'] ?></li>
			</ol>
		</nav>
		
		<!-- Header -->
		<div class="setor-header">
			<h1><i class="fas fa-users"></i> Gestor de Setor - <?= $usuario['nome_setor'] ?></h1>
			<p>Analise os Kaizens enviados pelo seu setor</p>
		</div>
		
		<!-- Filtros -->
		<div class="filter-card">
			<form method="GET" class="row g-3 align-items-end">
				<div class="col-md-5">
					<label for="ordem" class="form-label"><i class="fas fa-sort"></i> Ordenar por</label>
					<select name="ordem" id="ordem" class="form-select">
						<option <?php if ($ordem < 2 || $ordem > 4){ echo 'selected'; }?> value="1">Número do Kaizen</option>
						<option <?php if ($ordem == 2){ echo 'selected'; }?> value="2">Tipo de Benefício</option>
						<option <?php if ($ordem == 3){ echo 'selected'; }?> value="3">Data de Cadastro</option>
						<option <?php if ($ordem == 4){ echo 'selected'; }?> value="4">Status</option>
					</select>
				</div>
				<div class="col-md-4">
					<label for="ordem2" class="form-label"><i class="fas fa-arrow-down-up-across-line"></i> Direção</label>
					<select name="ordem2" id="ordem2" class="form-select">
						<option <?php if ($ordem2 == 1){ echo 'selected'; }?> value="1">Crescente ↑</option>
						<option <?php if ($ordem2 <> 1){ echo 'selected'; }?> value="2">Decrescente ↓</option>
					</select>
				</div>
				<div class="col-md-3">
					<button type="submit" class="btn btn-primary w-100">
						<i class="fas fa-filter"></i> Aplicar Filtro
					</button>
				</div>
			</form>
		</div>
		
		<!-- Desktop: Tabela -->
		<div class="d-none d-lg-block kaizen-table-responsive">
			<table class="table table-hover align-middle mb-0">
				<thead class="table-header-setor">
					<tr>
						<th width="15%">NÚMERO</th>
						<th width="25%">TIPO</th>
						<th width="20%">DATA</th>
						<th width="15%" class="text-center">STATUS</th>
						<th width="15%" class="text-center">AÇÕES</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$sql = sql("SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp, colaborador.nome_colaborador FROM kaizen
					LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
					LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
					LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
					WHERE kaizen.setor_kaizen = ".$usuario['id_setor']." ORDER BY ".$filtro, $con);
					
					if (mysqli_num_rows($sql)):
						while ($dados = mysqli_fetch_assoc($sql)):
					?>
					<tr>
						<td>
							<strong>#<?= $dados['id_kaizen'] ?></strong>
							<br><small class="text-muted"><?= $dados['nome_colaborador'] ?></small>
						</td>
						<td>
							<?= $dados['nome_tipo'] ?>
							<?php if (!empty($dados['nome_comp'])): ?>
								<br><small class="text-muted"><?= $dados['nome_comp'] ?></small>
							<?php endif; ?>
						</td>
						<td>
							<small class="text-muted">Cadastro:</small><br>
							<?php data_print($dados['datacadastro_kaizen']); ?>
							<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00'): ?>
								<br><small class="text-success"><i class="fas fa-check-circle"></i> Concluído: <?php data_print($dados['dataconclusao_kaizen']); ?></small>
							<?php endif; ?>
						</td>
						<td class="text-center">
							<?php $status = $dados['status_kaizen']; include("_status.php"); ?>
						</td>
						<td class="text-center">
							<a href="analisar-kaizen.php?kaizen=<?= $dados['id_kaizen'] ?>" class="btn btn-sm btn-primary">
								<i class="fas fa-eye"></i> Visualizar
							</a>
						</td>
					</tr>
					<?php
						endwhile;
					else:
					?>
					<tr>
						<td colspan="5" class="text-center py-5">
							<i class="fas fa-inbox fa-3x text-muted mb-3 d-block"></i>
							<p class="text-muted mb-0">Nenhum Kaizen encontrado no seu setor</p>
						</td>
					</tr>
					<?php endif; ?>
				</tbody>
			</table>
		</div>
		
		<!-- Mobile: Cards -->
		<div class="d-lg-none">
			<?php
			$sql = sql("SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp, colaborador.nome_colaborador FROM kaizen
			LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
			LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
			LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
			WHERE kaizen.setor_kaizen = ".$usuario['id_setor']." ORDER BY ".$filtro, $con);
			
			if (mysqli_num_rows($sql)):
				while ($dados = mysqli_fetch_assoc($sql)):
			?>
			<div class="kaizen-mobile-card">
				<div class="mobile-card-header">
					<div>
						<h5 class="mobile-card-title">Kaizen #<?= $dados['id_kaizen'] ?></h5>
						<small class="text-muted"><?= $dados['nome_colaborador'] ?></small>
					</div>
					<div>
						<?php $status = $dados['status_kaizen']; include("_status.php"); ?>
					</div>
				</div>
				
				<div class="mobile-card-info">
					<div class="info-row">
						<i class="fas fa-tag"></i>
						<strong>Tipo:</strong>
						<span><?= $dados['nome_tipo'] ?><?php if (!empty($dados['nome_comp'])): ?> - <?= $dados['nome_comp'] ?><?php endif; ?></span>
					</div>
					<div class="info-row">
						<i class="fas fa-calendar"></i>
						<strong>Cadastro:</strong>
						<span><?php data_print($dados['datacadastro_kaizen']); ?></span>
					</div>
					<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00'): ?>
					<div class="info-row">
						<i class="fas fa-check-circle text-success"></i>
						<strong>Concluído:</strong>
						<span><?php data_print($dados['dataconclusao_kaizen']); ?></span>
					</div>
					<?php endif; ?>
				</div>
				
				<div class="d-grid">
					<a href="analisar-kaizen.php?kaizen=<?= $dados['id_kaizen'] ?>" class="btn btn-primary">
						<i class="fas fa-eye"></i> Visualizar Kaizen
					</a>
				</div>
			</div>
			<?php
				endwhile;
			else:
			?>
			<div class="text-center py-5">
				<i class="fas fa-inbox fa-4x text-muted mb-3"></i>
				<p class="text-muted">Nenhum Kaizen encontrado no seu setor</p>
			</div>
			<?php endif; ?>
		</div>
		
		<!-- InstanceEndEditable -->
	</main>

	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
<!-- InstanceEnd --></html>
