<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

$id_kaizen = trata($_GET['kaizen']);

if (!empty($id_kaizen) && is_numeric($id_kaizen)){
	$sql = mysqli_query($con, "SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp, 
	categoria.nome_categoria, colaborador.nome_colaborador, 
	setor.nome_setor, unidade.nome_unidade
	FROM kaizen
	LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
	LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
	LEFT JOIN categoria ON categoria.id_categoria = kaizen.categoria_kaizen
	LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
	LEFT JOIN setor ON setor.id_setor = colaborador.setor_colaborador
	LEFT JOIN unidade ON unidade.id_unidade = colaborador.unidade_colaborador
	WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die (mysqli_error($con));
	 
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);
	} else {
		volta("erro", "Registro não encontrado!", "home.php");
	} 
} else {
	volta("erro", "Registro não encontrado!", "home.php");
}

if ($dados['colaborador_kaizen'] <> $_SESSION['usu']){
	volta("erro", "Este formulário pertence a outro usuário. Você não tem permissão para acessá-lo!", "home.php");	
}
?>
<!doctype html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Visualizar Kaizen #<?= $dados['id_kaizen'] ?> - Sistema Orion</title>
	
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
	
	<!-- CSS de Impressão -->
	<link rel="stylesheet" href="lib/css/visualizar-kaizen-print.css" media="print"/>
	
	<!-- jQuery -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	
	<!-- NACHO LIGHTBOX -->
	<link href="lib/nacho-lightbox-1.33/plugin/css/nchlightbox-1.3.css" rel="stylesheet">
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.hammer.min.js"></script>
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.nchlightbox-1.3.js"></script>
	
	<style>
		/* Estilos para visualização do Kaizen */
		.kaizen-header-card {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			color: white !important;
			border-radius: 1rem;
			padding: 2rem;
			margin-bottom: 2rem;
			box-shadow: 0 10px 40px rgba(0, 158, 227, 0.2);
			position: relative;
			overflow: hidden;
		}
		
		.kaizen-header-card * {
			color: white !important;
		}
		
		.kaizen-header-card::before {
			content: '';
			position: absolute;
			top: -50px;
			right: -50px;
			width: 200px;
			height: 200px;
			background: rgba(255, 255, 255, 0.1);
			border-radius: 50%;
		}
		
		.kaizen-number {
			font-size: 3rem;
			font-weight: 700;
			margin-bottom: 0;
		}
		
		.info-card {
			background: white;
			border-radius: 0.75rem;
			padding: 1.5rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
			border-left: 4px solid var(--orion-primary);
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
			color: var(--orion-text);
			min-width: 180px;
			flex-shrink: 0;
		}
		
		.info-value {
			color: var(--orion-text-light);
			flex-grow: 1;
		}
		
		.content-card {
			background: white;
			border-radius: 0.75rem;
			padding: 2rem;
			margin-bottom: 1.5rem;
			box-shadow: 0 4px 20px rgba(0, 0, 0, 0.06);
		}
		
		.content-card h5 {
			color: var(--orion-primary);
			font-weight: 600;
			margin-bottom: 1rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		
		.content-text {
			line-height: 1.8;
			color: var(--orion-text);
		}
		
		.gallery-grid {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(200px, 1fr));
			gap: 1rem;
			margin-top: 1rem;
		}
		
		.gallery-item {
			position: relative;
			border-radius: 0.5rem;
			overflow: hidden;
			box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
			transition: all 0.3s ease;
		}
		
		.gallery-item:hover {
			transform: translateY(-4px);
			box-shadow: 0 8px 24px rgba(0, 158, 227, 0.2);
		}
		
		.gallery-item img {
			width: 100%;
			height: 200px;
			object-fit: cover;
		}
		
		.attachment-item {
			background: #f8f9fa;
			border: 1px solid #e9ecef;
			border-radius: 0.5rem;
			padding: 1rem;
			margin-bottom: 0.75rem;
			display: flex;
			align-items: center;
			gap: 1rem;
			transition: all 0.3s ease;
		}
		
		.attachment-item:hover {
			background: #e9ecef;
			border-color: var(--orion-primary);
		}
		
		.attachment-icon {
			width: 48px;
			height: 48px;
			background: var(--orion-primary);
			color: white;
			border-radius: 0.5rem;
			display: flex;
			align-items: center;
			justify-content: center;
			font-size: 1.5rem;
			flex-shrink: 0;
		}
		
		.value-highlight {
			background: linear-gradient(135deg, var(--orion-secondary) 0%, #e0c100 100%);
			color: #2c3e50;
			padding: 1.5rem;
			border-radius: 0.75rem;
			text-align: center;
			margin: 1rem 0;
			box-shadow: 0 4px 15px rgba(252, 215, 0, 0.3);
		}
		
		.value-highlight .amount {
			font-size: 2rem;
			font-weight: 700;
			margin: 0;
		}
		
		.collaborators-list {
			display: flex;
			flex-wrap: wrap;
			gap: 0.5rem;
			margin-top: 1rem;
		}
		
		.collaborator-badge {
			background: var(--orion-primary);
			color: white;
			padding: 0.5rem 1rem;
			border-radius: 2rem;
			font-size: 0.9rem;
			display: inline-flex;
			align-items: center;
			gap: 0.5rem;
		}
	</style>
</head>

<body class="orion-body-gradient">
	<!-- Navbar -->
	<nav class="navbar navbar-expand-lg navbar-light fixed-top orion-navbar-glass">
		<div class="container">
			<a class="navbar-brand" href="home.php">
				<img src="lib/img/logo-kaizen.png" alt="Kaizen" height="40" class="d-inline-block align-text-top">
			</a>
			
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
				<span class="navbar-toggler-icon"></span>
			</button>
			
			<div class="collapse navbar-collapse" id="navbarNav">
				<ul class="navbar-nav ms-auto align-items-lg-center">
					<?php if (!empty($_SESSION['usu'])): ?>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="home.php"><i class="fas fa-home"></i> Página Inicial</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link" href="minhas-sugestoes.php"><i class="fab fa-wpforms"></i> Meus Kaizens</a>
						</li>
						<li class="nav-item d-lg-none"><hr class="dropdown-divider"></li>
						
						<li class="nav-item me-lg-3">
							<span class="nav-link">
								<i class="fas fa-user-circle"></i> 
								<strong><?= $usuario['nome_colaborador'] ?></strong>
								<br class="d-lg-none">
								<span class="badge-pontos ms-2">
									<i class="fas fa-star"></i> 
									<?php 
										$pontos = $usuario['ponto_colaborador'];
										echo ($pontos == 0) ? '0 pontos' : (($pontos == 1) ? '1 ponto' : $pontos . ' pontos');
									?>
								</span>
							</span>
						</li>
						
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
	
	<div style="height: 80px;"></div>
	
	<!-- Conteúdo Principal -->
	<main class="container py-5">
		
		<!-- Breadcrumb -->
		<nav aria-label="breadcrumb" class="mb-4">
			<ol class="breadcrumb">
				<li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
				<li class="breadcrumb-item"><a href="minhas-sugestoes.php">Meus Kaizens</a></li>
				<li class="breadcrumb-item active" aria-current="page">Kaizen #<?= $dados['id_kaizen'] ?></li>
			</ol>
		</nav>
		
		<!-- Mensagens -->
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<!-- Header do Kaizen -->
		<div class="kaizen-header-card">
			<div class="row align-items-center">
				<div class="col-md-8">
					<h1 class="kaizen-number mb-2">#<?= $dados['id_kaizen'] ?></h1>
					<h4 class="mb-0 opacity-75">
						<i class="fas fa-lightbulb"></i> Kaizen - Melhoria Contínua
					</h4>
				</div>
				<div class="col-md-4 text-md-end mt-3 mt-md-0">
					<?php 
					$status = $dados['status_kaizen'];
					switch($status){
						case 1:
							echo '<span class="badge-status status-pendente fs-5"><i class="fas fa-clock"></i> Pendente</span>';
							break;
						case 2:
							echo '<span class="badge-status status-aprovado fs-5"><i class="fas fa-check-circle"></i> Aprovado</span>';
							break;
						case 3:
							echo '<span class="badge-status status-rejeitado fs-5"><i class="fas fa-times-circle"></i> Rejeitado</span>';
							break;
						case 4:
							echo '<span class="badge-status status-rascunho fs-5"><i class="fas fa-edit"></i> Rascunho</span>';
							break;
					}
					?>
				</div>
			</div>
		</div>
		
		<div class="row">
			<div class="col-lg-4 mb-4 mb-lg-0">
				
				<!-- Card de Informações Básicas -->
				<div class="info-card">
					<h5><i class="fas fa-info-circle"></i> Informações</h5>
					
					<div class="info-row">
						<div class="info-label">Enviado por:</div>
						<div class="info-value"><?= $dados['nome_colaborador'] ?></div>
					</div>
					
					<div class="info-row">
						<div class="info-label">Data de envio:</div>
						<div class="info-value"><?php data_print($dados['datacadastro_kaizen']); ?></div>
					</div>
					
					<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != "0000-00-00"): ?>
					<div class="info-row">
						<div class="info-label">Analisado em:</div>
						<div class="info-value">
							<?php data_print($dados['dataconclusao_kaizen']); ?>
							<?php 
							if ($dados['admin_kaizen'] > 0){
								$sqladmin = sql("SELECT * FROM colaborador WHERE id_colaborador =".$dados['admin_kaizen']." LIMIT 1", $con);
								if (mysqli_num_rows($sqladmin)){
									$adm = mysqli_fetch_array($sqladmin);
									echo "<br>por " . $adm['nome_colaborador'];
								}
							}
							?>
						</div>
					</div>
					<?php endif; ?>
					
					<div class="info-row">
						<div class="info-label">Unidade:</div>
						<div class="info-value"><?= $dados['nome_unidade'] ?></div>
					</div>
					
					<div class="info-row">
						<div class="info-label">Setor:</div>
						<div class="info-value"><?= $dados['nome_setor'] ?></div>
					</div>
					
					<div class="info-row">
						<div class="info-label">Tipo de Benefício:</div>
						<div class="info-value">
							<?= $dados['nome_tipo'] ?>
							<?php if(!empty($dados['nome_comp'])): ?>
								<br><small><?= $dados['nome_comp'] ?></small>
							<?php endif; ?>
						</div>
					</div>
					
					<?php if(!empty($dados['add_tipo_texto'])): ?>
					<div class="info-row">
						<div class="info-label">Classificação:</div>
						<div class="info-value"><?= $dados['add_tipo_texto'] ?></div>
					</div>
					<?php endif; ?>
				</div>
				
				<!-- Card de Valor do Benefício (v2.1) -->
				<?php if($dados['valor_original_kaizen'] > 0 && $dados['tipo_complemento_kaizen'] > 0): ?>
				<div class="info-card">
					<h5><i class="fas fa-dollar-sign"></i> Benefício Anual</h5>
					
					<div class="value-highlight">
						<small class="d-block mb-1">Valor do Benefício</small>
						<p class="amount mb-0">
							<?= formatarValorComplemento($dados['valor_original_kaizen'], $dados['tipo_complemento_kaizen']) ?>
						</p>
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
					<?php } ?>
				</div>
				<?php endif; ?>
				
				<!-- Card de Pontos -->
				<?php if (!empty($dados['pontos_kaizen'])): ?>
				<div class="info-card">
					<h5><i class="fas fa-star"></i> Pontuação</h5>
					<div class="value-highlight" style="background: linear-gradient(135deg, var(--orion-primary), var(--orion-primary-dark));">
						<small class="d-block mb-1 text-white">Pontos Concedidos</small>
						<p class="amount mb-0 text-white">
							<i class="fas fa-trophy"></i> <?= $dados['pontos_kaizen'] ?> pontos
						</p>
					</div>
				</div>
				<?php endif; ?>
				
			</div>
			
			<div class="col-lg-8">
				
				<!-- Situação Atual -->
				<?php if(!empty($dados['situacao_atual'])): ?>
				<div class="content-card">
					<h5><i class="fas fa-exclamation-triangle"></i> Situação Atual</h5>
					<div class="content-text"><?= nl2br($dados['situacao_atual']) ?></div>
				</div>
				<?php endif; ?>
				
				<!-- Objetivo da Melhoria -->
				<div class="content-card">
					<h5><i class="fas fa-bullseye"></i> Objetivo da Melhoria</h5>
					<p class="fw-bold mb-3">O que foi feito e onde foi aplicada a ideia?</p>
					<div class="content-text"><?= nl2br($dados['onde_kaizen']) ?></div>
				</div>
				
				<!-- Resultados -->
				<div class="content-card">
					<h5><i class="fas fa-chart-line"></i> Resultados Obtidos</h5>
					<div class="content-text"><?= nl2br($dados['resultado_kaizen']) ?></div>
				</div>
				
				<!-- Anexos -->
				<?php
				$anexos = listarAnexos($dados['id_kaizen'], $con);
				if(count($anexos) > 0):
				?>
				<div class="content-card">
					<h5><i class="fas fa-paperclip"></i> Anexos</h5>
					
					<?php
					$imagens_array = [];
					$arquivos_array = [];
					
					foreach($anexos as $anexo){
						$caminho = "imgs/kaizen/" . $anexo['nome_arquivo'];
						$extensao = strtolower($anexo['tipo_arquivo']);
						$imagens_ext = ['jpg', 'jpeg', 'png', 'gif'];
						
						if(in_array($extensao, $imagens_ext)){
							$imagens_array[] = ['caminho' => $caminho, 'nome' => $anexo['nome_original']];
						} else {
							$arquivos_array[] = ['caminho' => $caminho, 'nome' => $anexo['nome_original'], 'tamanho' => $anexo['tamanho_arquivo']];
						}
					}
					?>
					
					<?php if(count($imagens_array) > 0): ?>
					<h6 class="mt-3 mb-3">Imagens</h6>
					<div class="gallery-grid">
						<?php foreach($imagens_array as $img): ?>
						<a href="<?= $img['caminho'] ?>" class="gallery-item nch-lightbox" rel="group1">
							<img src="<?= $img['caminho'] ?>" alt="<?= $img['nome'] ?>">
						</a>
						<?php endforeach; ?>
					</div>
					<?php endif; ?>
					
					<?php if(count($arquivos_array) > 0): ?>
					<h6 class="mt-4 mb-3">Documentos</h6>
					<?php foreach($arquivos_array as $arq): ?>
					<div class="attachment-item">
						<div class="attachment-icon">
							<i class="fas fa-file-<?= in_array(strtolower(pathinfo($arq['nome'], PATHINFO_EXTENSION)), ['pdf']) ? 'pdf' : (in_array(strtolower(pathinfo($arq['nome'], PATHINFO_EXTENSION)), ['doc', 'docx']) ? 'word' : (in_array(strtolower(pathinfo($arq['nome'], PATHINFO_EXTENSION)), ['xls', 'xlsx']) ? 'excel' : 'archive')) ?>"></i>
						</div>
						<div class="flex-grow-1">
							<strong><?= $arq['nome'] ?></strong>
							<br><small class="text-muted"><?= number_format($arq['tamanho']/1024, 2) ?> KB</small>
						</div>
						<a href="<?= $arq['caminho'] ?>" class="btn btn-sm btn-primary" target="_blank" download>
							<i class="fas fa-download"></i> Baixar
						</a>
					</div>
					<?php endforeach; ?>
					<?php endif; ?>
				</div>
				<?php elseif(verimg($dados['imagem1_kaizen']) || verimg($dados['imagem2_kaizen'])): ?>
				<div class="content-card">
					<h5><i class="fas fa-image"></i> Imagens (v1 - legado)</h5>
					<div class="gallery-grid">
						<?php if(verimg($dados['imagem1_kaizen'])): ?>
						<a href="imgs/<?= $dados['imagem1_kaizen'] ?>" class="gallery-item nch-lightbox" rel="group1">
							<img src="imgs/<?= $dados['imagem1_kaizen'] ?>" alt="Imagem 1">
						</a>
						<?php endif; ?>
						<?php if(verimg($dados['imagem2_kaizen'])): ?>
						<a href="imgs/<?= $dados['imagem2_kaizen'] ?>" class="gallery-item nch-lightbox" rel="group1">
							<img src="imgs/<?= $dados['imagem2_kaizen'] ?>" alt="Imagem 2">
						</a>
						<?php endif; ?>
					</div>
				</div>
				<?php endif; ?>
				
				<!-- Implementação em outros departamentos -->
				<div class="content-card">
					<h5><i class="fas fa-building"></i> Implementação em Outros Departamentos</h5>
					<p class="mb-2"><strong>A ideia pode ser implementada em outros departamentos?</strong></p>
					<p class="content-text">
						<?php if ($dados['outrosdep_kaizen'] == 1): ?>
							<span class="badge bg-success"><i class="fas fa-check"></i> Sim</span>
							<?php if($dados['qual_kaizen']): ?>
								<br><br><strong>Quais:</strong> <?= $dados['qual_kaizen'] ?>
							<?php endif; ?>
						<?php else: ?>
							<span class="badge bg-secondary"><i class="fas fa-times"></i> Não</span>
						<?php endif; ?>
					</p>
				</div>
				
				<!-- Colaboradores -->
				<?php
				$colaboradores_participantes = [];
				for ($i=1; $i <= 4; $i++){
					if (!empty($dados['colaborador'.$i.'_kaizen'])){
						$sqlcol = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1", $con);
						$col = mysqli_fetch_array($sqlcol);
						if($col){
							$colaboradores_participantes[] = $col['nome_colaborador'];
						}
					}
				}
				
				if(count($colaboradores_participantes) > 0):
				?>
				<div class="content-card">
					<h5><i class="fas fa-users"></i> Colaboradores Participantes</h5>
					<div class="collaborators-list">
						<?php foreach($colaboradores_participantes as $colaborador): ?>
						<span class="collaborator-badge">
							<i class="fas fa-user"></i> <?= $colaborador ?>
						</span>
						<?php endforeach; ?>
					</div>
				</div>
				<?php endif; ?>
				
				<!-- Botões de Ação -->
				<div class="d-flex gap-2 flex-wrap mt-4">
					<a href="minhas-sugestoes.php" class="btn btn-outline-primary">
						<i class="fas fa-arrow-left"></i> Voltar para lista
					</a>
					<button onclick="window.print()" class="btn btn-outline-secondary">
						<i class="fas fa-print"></i> Imprimir
					</button>
				</div>
				
			</div>
		</div>
		
	</main>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
</body>
</html>
