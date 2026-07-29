<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

$id_kaizen = trata($_GET['kaizen']);

if (!empty($id_kaizen) && is_numeric($id_kaizen)){
	$sql = mysqli_query($con, "SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp,
	categoria.nome_categoria, colaborador.nome_colaborador, colaborador.gestor_colaborador,
	setor.nome_setor, setor.id_setor, unidade.nome_unidade
	FROM kaizen
	LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
	LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
	LEFT JOIN categoria ON categoria.id_categoria = kaizen.categoria_kaizen
	LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
	LEFT JOIN setor ON setor.id_setor = colaborador.setor_colaborador
	LEFT JOIN unidade ON unidade.id_unidade = colaborador.unidade_colaborador
	WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die(mysqli_error($con));
	 
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
	} else {
		volta("erro", "Registro não encontrado!", "home.php");
	} 
} else {
	volta("erro", "Registro não encontrado!", "home.php");
}

if ($usuario['lider_colaborador'] <> 1){
	if ($usuario['id_setor'] <> $dados['setor_kaizen']){
		volta("erro", "Área restrita para líderes Kaizen ou Líderes de Setor. Você não tem permissão para acessá-lo!", "home.php");	
	}
}

if ($usuario['id_colaborador'] <> $dados['gestor_colaborador']){
	volta("erro", "Você não é o gestor responsável pelos Kaizens deste colaborador!", "home.php");	
}

if ($dados['tipo_kaizen'] == 1){
	$sqltipo = sql("SELECT * FROM reducao 
	LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
	WHERE reducao.id_reducao = ".$dados['custo_kaizen']." LIMIT 1", $con);
	$tipo = mysqli_fetch_array($sqltipo);
} elseif ($dados['tipo_kaizen'] == 2) {
	$sqltipo = sql("SELECT * FROM reducao 
	LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
	WHERE reducao.id_reducao = ".$dados['tempo_kaizen']." LIMIT 1", $con);
	$tipo = mysqli_fetch_array($sqltipo);	
} else {
	$sqltipo = sql("SELECT * FROM reducao 
	LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
	WHERE reducao.id_reducao = ".$dados['reducao_kaizen']." LIMIT 1", $con);
	$tipo = mysqli_fetch_array($sqltipo);	
}
?>
<!DOCTYPE html>
<html lang="pt-br"><!-- InstanceBegin template="/Templates/kaizen-bs5.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Analisar Kaizen #<?= $dados['id_kaizen'] ?> | Sistema Orion Kaizen</title>
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
	
	<!-- NACHO LIGHTBOX -->
	<link href="lib/nacho-lightbox-1.33/plugin/css/nchlightbox-1.3.css" rel="stylesheet">
	
	<!-- jQuery (compatibilidade) -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.hammer.min.js"></script>
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.nchlightbox-1.3.js"></script>
	
	<!-- InstanceBeginEditable name="head" -->
	<style>
		.analise-header {
			background: linear-gradient(135deg, var(--orion-success) 0%, #027a2e 100%);
			color: white;
			padding: 2rem;
			border-radius: 1rem;
			margin-bottom: 2rem;
			box-shadow: 0 4px 20px rgba(0, 129, 46, 0.2);
		}
		
		.analise-header h1 {
			font-size: 2rem;
			font-weight: 700;
			margin: 0 0 0.5rem;
			color: white !important;
		}
		
		.analise-header h1 i {
			color: white !important;
		}
		
		/* Ajuste de contraste para alerts dentro do header verde */
		.analise-header .alert {
			border: 2px solid rgba(255, 255, 255, 0.3);
			font-weight: 600;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
		}
		
		.analise-header .alert-warning {
			background-color: rgba(255, 193, 7, 0.95);
			color: #664d03;
			border-color: rgba(255, 255, 255, 0.5);
		}
		
		.analise-header .alert-success {
			background-color: rgba(25, 135, 84, 0.95);
			color: white;
			border-color: rgba(255, 255, 255, 0.5);
		}
		
		.analise-header .alert-danger {
			background-color: rgba(220, 53, 69, 0.95);
			color: white;
			border-color: rgba(255, 255, 255, 0.5);
		}
		
		.analise-header .alert-info {
			background-color: rgba(13, 202, 240, 0.95);
			color: #055160;
			border-color: rgba(255, 255, 255, 0.5);
		}
		
		.analise-header .badge {
			font-size: 0.9rem;
			padding: 0.5rem 1rem;
		}
		
		.info-card {
			background: white;
			border-radius: 0.75rem;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
		}
		
		.info-card h5 {
			color: var(--orion-primary);
			font-weight: 600;
			margin-bottom: 1rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		
		.info-row {
			display: flex;
			padding: 0.75rem 0;
			border-bottom: 1px solid #f0f0f0;
		}
		
		.info-row:last-child {
			border-bottom: none;
		}
		
		.info-label {
			font-weight: 600;
			min-width: 200px;
			color: var(--orion-text);
		}
		
		.info-value {
			color: var(--orion-text-light);
			flex: 1;
		}
		
		.value-highlight {
			background: linear-gradient(135deg, var(--orion-primary), var(--orion-primary-dark));
			color: white;
			padding: 1.5rem;
			border-radius: 0.75rem;
			text-align: center;
			margin: 1rem 0;
		}
		
		.value-highlight .amount {
			font-size: 2rem;
			font-weight: 700;
			margin: 0;
		}
		
		.action-buttons {
			display: flex;
			gap: 1rem;
			margin-top: 2rem;
			flex-wrap: wrap;
		}
		
		.action-buttons .btn {
			flex: 1;
			min-width: 150px;
			padding: 0.75rem 1.5rem;
			font-size: 1.1rem;
			font-weight: 600;
		}
		
		.devolver-form {
			background: #fff3cd;
			border: 2px solid #ffc107;
			border-radius: 0.75rem;
			padding: 1.5rem;
			margin-top: 2rem;
		}
		
		.image-gallery {
			display: grid;
			grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
			gap: 1.5rem;
			margin: 1.5rem 0;
		}
		
		.image-item {
			position: relative;
			border-radius: 0.75rem;
			overflow: hidden;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
		}
		
		.image-item img {
			width: 100%;
			height: auto;
			display: block;
			cursor: pointer;
			transition: transform 0.3s ease;
		}
		
		.image-item:hover img {
			transform: scale(1.05);
		}
		
		.image-label {
			position: absolute;
			top: 10px;
			left: 10px;
			background: rgba(0, 0, 0, 0.7);
			color: white;
			padding: 0.5rem 1rem;
			border-radius: 0.5rem;
			font-size: 0.9rem;
			font-weight: 600;
		}
		
		/* Anexos */
		.anexos-list {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 1rem;
			margin-top: 1rem;
		}
		
		.anexo-item {
			background: #f8f9fa;
			border: 2px solid #e9ecef;
			border-radius: 0.75rem;
			padding: 1rem;
			display: flex;
			align-items: center;
			gap: 1rem;
			transition: all 0.3s ease;
		}
		
		.anexo-item:hover {
			border-color: var(--orion-primary);
			background: #e7f3ff;
			transform: translateY(-2px);
			box-shadow: 0 4px 12px rgba(0, 158, 227, 0.15);
		}
		
		.anexo-icon {
			width: 50px;
			height: 50px;
			background: white;
			border-radius: 0.5rem;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.5rem;
			flex-shrink: 0;
		}
		
		.anexo-icon.pdf { color: #dc3545; }
		.anexo-icon.doc { color: #2b579a; }
		.anexo-icon.xls { color: #107c41; }
		.anexo-icon.zip { color: #6c757d; }
		.anexo-icon.img { color: #0dcaf0; }
		
		.anexo-info {
			flex: 1;
			min-width: 0;
		}
		
		.anexo-nome {
			font-weight: 600;
			color: var(--orion-text);
			margin-bottom: 0.25rem;
			word-break: break-word;
		}
		
		.anexo-meta {
			font-size: 0.85rem;
			color: var(--orion-text-light);
		}
		
		.anexo-item .btn {
			flex-shrink: 0;
		}
		
		@media (max-width: 768px) {
			.info-row {
				flex-direction: column;
			}
			
			.info-label {
				min-width: auto;
				margin-bottom: 0.25rem;
			}
			
			.action-buttons {
				flex-direction: column;
			}
			
			.action-buttons .btn {
				width: 100%;
			}
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
						<a class="nav-link" href="lider-analisar.php">
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
				<?php if ($usuario['lider_colaborador'] == 1): ?>
					<li class="breadcrumb-item"><a href="sugestoes-analisar.php">Gestor Kaizen</a></li>
				<?php else: ?>
					<li class="breadcrumb-item"><a href="lider-analisar.php">Gestor Setor</a></li>
				<?php endif; ?>
				<li class="breadcrumb-item active" aria-current="page">Kaizen #<?= $dados['id_kaizen'] ?></li>
			</ol>
		</nav>
		
		<!-- Header -->
		<div class="analise-header">
			<h1><i class="fas fa-search-plus"></i> Analisar Kaizen #<?= $dados['id_kaizen'] ?></h1>
			<div class="mt-2">
				<?php $status = $dados['status_kaizen']; include("_status.php"); ?>
				
				<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != "0000-00-00"): ?>
					<span class="badge bg-light text-dark ms-2">
						<i class="fas fa-calendar-check"></i> Analisado em <?php data_print($dados['dataconclusao_kaizen']); ?>
						<?php 
						if ($dados['admin_kaizen'] > 0 && !empty($dados['admin_kaizen'])){ 
							$sqladmin = sql("SELECT * FROM colaborador WHERE id_colaborador =".$dados['admin_kaizen']." LIMIT 1", $con);
							if (mysqli_num_rows($sqladmin)){
								$adm = mysqli_fetch_array($sqladmin);
								echo ' por '.$adm['nome_colaborador'];
							}
						}
						?>
					</span>
				<?php endif; ?>
			</div>
		</div>
		
		<!-- Informações do Colaborador -->
		<div class="info-card">
			<h5><i class="fas fa-user"></i> Informações do Colaborador</h5>
			<div class="info-row">
				<div class="info-label">Colaborador:</div>
				<div class="info-value"><strong><?= $dados['nome_colaborador'] ?></strong></div>
			</div>
			<div class="info-row">
				<div class="info-label">Data de Envio:</div>
				<div class="info-value"><?php data_print($dados['datacadastro_kaizen']); ?></div>
			</div>
			<div class="info-row">
				<div class="info-label">Unidade / Setor:</div>
				<div class="info-value"><?= $dados['nome_unidade'] ?> / <?= $dados['nome_setor'] ?></div>
			</div>
		</div>
		
		<!-- Tipo de Benefício -->
		<div class="info-card">
			<h5><i class="fas fa-tag"></i> Tipo de Benefício</h5>
			<div class="info-row">
				<div class="info-label">Tipo:</div>
				<div class="info-value">
					<strong><?= $dados['nome_tipo'] ?></strong>
					<?php if (!empty($dados['nome_comp'])): ?>
						<br><small class="text-muted"><?= $dados['nome_comp'] ?></small>
					<?php endif; ?>
				</div>
			</div>
			
			<?php
			// Exibir valores (v2.1 - Entrada Direta)
			if ($dados['valor_original_kaizen'] > 0 && $dados['tipo_complemento_kaizen'] > 0) {
				?>
				<div class="value-highlight">
					<small class="d-block mb-1">Valor do Benefício Anual</small>
					<p class="amount mb-0"><?= formatarValorComplemento($dados['valor_original_kaizen'], $dados['tipo_complemento_kaizen']) ?></p>
				</div>
				
				<?php
				// Exibir tipo de benefício
				$sql_comp = sql("SELECT nome_comp FROM comp WHERE id_comp = ".$dados['tipo_complemento_kaizen']." LIMIT 1", $con);
				if(mysqli_num_rows($sql_comp) > 0){
					$comp_info = mysqli_fetch_array($sql_comp);
				?>
				<div class="info-row mt-2">
					<div class="info-label text-muted">
						<small><?= $comp_info['nome_comp'] ?></small>
					</div>
				</div>
				<?php
				}
			} else {
				// Fallback para Kaizens v1 (antes de complemento_kaizen)
				if ($dados['custo_kaizen']) { 
					?>
					<div class="info-row">
						<div class="info-label">Redução Anual:</div>
						<div class="info-value">R$ <?php money_print($dados['custo_kaizen']); ?></div>
					</div>
					<?php
				}
				if ($dados['tempo_kaizen']) { 
					?>
					<div class="info-row">
						<div class="info-label">Redução Anual:</div>
						<div class="info-value"><?= $dados['tempo_kaizen'] ?> hora(s)</div>
					</div>
					<?php
				}
				if ($dados['incidente_kaizen']) { 
					?>
					<div class="info-row">
						<div class="info-label">Redução Anual:</div>
						<div class="info-value"><?= $dados['incidente_kaizen'] ?> incidente(s)</div>
					</div>
					<?php
				}
			}
			?>
			
			<?php if (!empty($dados['pontos_kaizen'])): ?>
			<div class="info-row">
				<div class="info-label">Pontos Concedidos:</div>
				<div class="info-value">
					<span class="badge bg-warning text-dark fs-6">
						<i class="fas fa-star"></i> <?= $dados['pontos_kaizen'] ?> pontos
					</span>
				</div>
			</div>
			<?php endif; ?>
		</div>
		
		<!-- Situação Atual -->
		<?php if (!empty($dados['situacao_atual'])): ?>
		<div class="info-card">
			<h5><i class="fas fa-exclamation-circle"></i> Situação Atual - Descreva</h5>
			<div style="line-height: 1.8; color: var(--orion-text);">
				<?= nl2br($dados['situacao_atual']) ?>
			</div>
		</div>
		<?php endif; ?>
		
		<!-- Descrição do Kaizen -->
		<div class="info-card">
			<h5><i class="fas fa-clipboard-list"></i> O que foi feito e onde foi aplicada sua ideia?</h5>
			<div style="line-height: 1.8; color: var(--orion-text);">
				<?= nl2br($dados['onde_kaizen']) ?>
			</div>
		</div>
		
		<!-- Resultados -->
		<div class="info-card">
			<h5><i class="fas fa-chart-line"></i> Quais os resultados obtidos?</h5>
			<div style="line-height: 1.8; color: var(--orion-text);">
				<?= nl2br($dados['resultado_kaizen']) ?>
			</div>
		</div>
		
		<!-- Galeria de Imagens -->
		<?php if (verimg($dados['imagem1_kaizen']) || verimg($dados['imagem2_kaizen'])): ?>
		<div class="info-card">
			<h5><i class="fas fa-images"></i> Evidências Fotográficas</h5>
			<div class="image-gallery">
				<?php if (verimg($dados['imagem1_kaizen'])): ?>
				<div class="image-item">
					<div class="image-label">Antes</div>
					<a href="imgs/<?= $dados['imagem1_kaizen'] ?>" class="nch-lightbox" data-group="kaizen<?= $dados['id_kaizen'] ?>">
						<img src="imgs/<?= $dados['imagem1_kaizen'] ?>" alt="Antes">
					</a>
				</div>
				<?php endif; ?>
				
				<?php if (verimg($dados['imagem2_kaizen'])): ?>
				<div class="image-item">
					<div class="image-label">Depois</div>
					<a href="imgs/<?= $dados['imagem2_kaizen'] ?>" class="nch-lightbox" data-group="kaizen<?= $dados['id_kaizen'] ?>">
						<img src="imgs/<?= $dados['imagem2_kaizen'] ?>" alt="Depois">
					</a>
				</div>
				<?php endif; ?>
			</div>
		</div>
		<?php endif; ?>
		
		<!-- Arquivos Anexos -->
		<?php 
		$anexos = listarAnexos($dados['id_kaizen'], $con);
		if (!empty($anexos) && count($anexos) > 0): 
		?>
		<div class="info-card">
			<h5><i class="fas fa-paperclip"></i> Arquivos Anexos</h5>
			<div class="anexos-list">
				<?php foreach ($anexos as $anexo): 
					// Determinar ícone baseado na extensão
					$ext = strtolower(pathinfo($anexo['nome_arquivo'], PATHINFO_EXTENSION));
					$icon_class = 'fa-file';
					$icon_color = 'img';
					
					if ($ext == 'pdf') {
						$icon_class = 'fa-file-pdf';
						$icon_color = 'pdf';
					} elseif (in_array($ext, ['doc', 'docx'])) {
						$icon_class = 'fa-file-word';
						$icon_color = 'doc';
					} elseif (in_array($ext, ['xls', 'xlsx'])) {
						$icon_class = 'fa-file-excel';
						$icon_color = 'xls';
					} elseif (in_array($ext, ['zip', 'rar'])) {
						$icon_class = 'fa-file-zipper';
						$icon_color = 'zip';
					} elseif (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
						$icon_class = 'fa-file-image';
						$icon_color = 'img';
					}
					
					// Formatar tamanho do arquivo
					$tamanho = $anexo['tamanho_arquivo'];
					if ($tamanho < 1024) {
						$tamanho_formatado = $tamanho . ' B';
					} elseif ($tamanho < 1048576) {
						$tamanho_formatado = round($tamanho / 1024, 2) . ' KB';
					} else {
						$tamanho_formatado = round($tamanho / 1048576, 2) . ' MB';
					}
				?>
				<div class="anexo-item">
					<div class="anexo-icon <?= $icon_color ?>">
						<i class="fas <?= $icon_class ?>"></i>
					</div>
					<div class="anexo-info">
						<div class="anexo-nome"><?= htmlspecialchars($anexo['nome_original']) ?></div>
						<div class="anexo-meta">
							<i class="fas fa-hdd"></i> <?= $tamanho_formatado ?>
							<?php if (!empty($anexo['data_upload'])): ?>
								<span class="ms-2"><i class="fas fa-clock"></i> <?php data_print($anexo['data_upload']); ?></span>
							<?php endif; ?>
						</div>
					</div>
					<a href="imgs/kaizen/<?= $anexo['nome_arquivo'] ?>" 
					   class="btn btn-outline-primary btn-sm" 
					   download="<?= htmlspecialchars($anexo['nome_original']) ?>"
					   target="_blank">
						<i class="fas fa-download"></i>
					</a>
				</div>
				<?php endforeach; ?>
			</div>
		</div>
		<?php endif; ?>
		
		<!-- Implementação em Outros Departamentos -->
		<div class="info-card">
			<h5><i class="fas fa-sitemap"></i> Aplicação em Outros Departamentos</h5>
			<div class="info-row">
				<div class="info-label">Pode ser implementada em outros departamentos?</div>
				<div class="info-value">
					<?php if ($dados['outrosdep_kaizen'] == 1): ?>
						<span class="badge bg-success">Sim</span>
						<?php if ($dados['qual_kaizen']): ?>
							<br><small class="text-muted mt-2 d-block"><?= $dados['qual_kaizen'] ?></small>
						<?php endif; ?>
					<?php else: ?>
						<span class="badge bg-secondary">Não</span>
					<?php endif; ?>
				</div>
			</div>
		</div>
		
		<!-- Colaboradores Participantes -->
		<?php if (!empty($dados['colaborador1_kaizen']) || !empty($dados['colaborador2_kaizen']) || !empty($dados['colaborador3_kaizen']) || !empty($dados['colaborador4_kaizen'])): ?>
		<div class="info-card">
			<h5><i class="fas fa-users"></i> Colaboradores Participantes</h5>
			<ul class="list-unstyled mb-0">
				<?php
				for ($i = 1; $i <= 4; $i++) {
					if (!empty($dados['colaborador'.$i.'_kaizen'])) {
						$sqlcol = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1", $con);
						$col = mysqli_fetch_array($sqlcol);
						echo '<li class="py-1"><i class="fas fa-user-check text-success"></i> '.$col['nome_colaborador'].'</li>';
					}
				}
				?>
			</ul>
		</div>
		<?php endif; ?>
		
		<!-- Ações de Análise (apenas para Kaizen pendente e líder Kaizen) -->
		<?php if ($dados['status_kaizen'] == 1 && $usuario['lider_colaborador'] == 1): ?>
		<div class="info-card">
			<h5><i class="fas fa-gavel"></i> Análise do Kaizen</h5>
			<p class="text-muted mb-3">Escolha uma ação para finalizar a análise deste Kaizen:</p>
			
			<div class="action-buttons">
				<a href="app/func_kaizen_analisar.php?kaizen=<?= $dados['id_kaizen'] ?>&analise=2" 
				   class="btn btn-success btconfirma" 
				   data-confirm="Tem certeza que deseja APROVAR este Kaizen?">
					<i class="fas fa-check-circle"></i> Aprovar
				</a>
				<a href="app/func_kaizen_analisar.php?kaizen=<?= $dados['id_kaizen'] ?>&analise=3" 
				   class="btn btn-danger btconfirma" 
				   data-confirm="Tem certeza que deseja REPROVAR este Kaizen?">
					<i class="fas fa-times-circle"></i> Reprovar
				</a>
			</div>
			
			<div class="devolver-form mt-4">
				<h6 class="mb-3"><i class="fas fa-undo"></i> Devolver para Ajuste/Correção</h6>
				<form id="formdevolver" action="app/func_kaizen_analisar.php" method="GET">
					<input name="kaizen" type="hidden" value="<?= $dados['id_kaizen'] ?>">
					<input name="analise" type="hidden" value="4">
					
					<div class="mb-3">
						<label for="obs_kaizen" class="form-label">Motivo da devolução: *</label>
						<textarea name="obs_kaizen" id="obs_kaizen" class="form-control" rows="4" 
								  placeholder="Descreva os ajustes necessários..." required></textarea>
					</div>
					
					<button type="submit" class="btn btn-warning btconfirmaForm">
						<i class="fas fa-undo"></i> Devolver para Correção
					</button>
				</form>
			</div>
		</div>
		<?php endif; ?>
		
		<!-- InstanceEndEditable -->
	</main>

	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<script>
	// Confirmação para ações críticas
	document.querySelectorAll('.btconfirma').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			var msg = this.getAttribute('data-confirm') || 'Tem certeza que deseja realizar esta ação?';
			if (!confirm(msg)) {
				e.preventDefault();
			}
		});
	});
	
	// Confirmação para form de devolução
	document.querySelectorAll('.btconfirmaForm').forEach(function(btn) {
		btn.addEventListener('click', function(e) {
			if (!confirm('Tem certeza que deseja devolver este Kaizen para correção?')) {
				e.preventDefault();
			}
		});
	});
	</script>
</body>
<!-- InstanceEnd --></html>
