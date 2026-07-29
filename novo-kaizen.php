<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

$sqlAlert = sql("SELECT * FROM pg WHERE id_pg = 101 LIMIT 1", $con);
$alert = mysqli_fetch_array($sqlAlert);

// Buscar variáveis para calculadora consultiva
$custo_linha_parada = floatval($alert['nome4_pg'] ?? 0); // Custo hora linha parada
$produtividade_usd = floatval($alert['nome5_pg'] ?? 0);  // Produtividade em USD
$cambio_dolar = floatval($alert['nome6_pg'] ?? 0);       // Câmbio do dólar
?>
<!doctype html>
<html lang="pt-br">

<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<title>Novo Kaizen - Sistema Orion</title>

	<meta name="title" content="Kaizen - Sistema Orion" />
	<meta name="author" content="Orion" />
	<meta name="description" content="Sistema de Melhoria Contínua Kaizen" />

	<link rel="shortcut icon" href="favicon.ico" />

	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">

	<!-- CSS Customizado Orion -->
	<link rel="stylesheet" href="lib/css/orion-custom.css" />
	<link rel="stylesheet" href="assets/css/estilos-v2.css" />

	<!-- jQuery (mantido para compatibilidade) -->
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	<script src="lib/js/jquery.inputmask.bundle.min.js"></script>
	<script src="lib/js/jquery.maskMoney.js"></script>

	<style>
		/* Estilos específicos para formulário */
		.form-wizard {
			background: white;
			border-radius: 1rem;
			box-shadow: 0 10px 40px rgba(0, 0, 0, 0.08);
			overflow: hidden;
		}

		.wizard-header {
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			color: white;
			padding: 2rem;
			text-align: center;
			position: relative;
		}

		.wizard-header::after {
			content: '';
			position: absolute;
			bottom: -15px;
			left: 50%;
			transform: translateX(-50%);
			width: 40px;
			height: 40px;
			background: white;
			border-radius: 50%;
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3);
			z-index: 1;
		}

		.wizard-header i {
			font-size: 3rem;
			margin-bottom: 1rem;
			opacity: 0.9;
		}

		.wizard-body {
			padding: 3rem 2rem 2rem;
		}

		.form-step {
			display: none;
		}

		.form-step.active {
			display: block;
			animation: fadeInUp 0.5s ease;
		}

		.step-indicator {
			display: flex;
			justify-content: center;
			align-items: center;
			margin-bottom: 2rem;
			gap: 1rem;
		}

		.step-dot {
			width: 12px;
			height: 12px;
			border-radius: 50%;
			background: #e9ecef;
			transition: all 0.3s ease;
		}

		.step-dot.active {
			width: 40px;
			border-radius: 10px;
			background: linear-gradient(135deg, var(--orion-primary), var(--orion-primary-dark));
		}

		.alert-custom {
			background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
			border: 1px solid #ffc107;
			border-radius: 0.75rem;
			padding: 1.5rem;
			margin-top: 1rem;
			display: none;
		}

		.alert-custom img {
			width: 31px;
			height: 20px;
			margin-right: 10px;
		}

		.file-upload-area {
			border: 2px dashed var(--orion-primary);
			border-radius: 0.75rem;
			padding: 2rem;
			text-align: center;
			background: rgba(0, 158, 227, 0.02);
			transition: all 0.3s ease;
			cursor: pointer;
		}

		.file-upload-area:hover {
			background: rgba(0, 158, 227, 0.05);
			border-color: var(--orion-primary-dark);
		}

		.file-upload-area.drag-over {
			background: rgba(0, 158, 227, 0.1);
			border-color: var(--orion-primary);
			border-width: 3px;
			transform: scale(1.02);
		}

		.file-upload-area i {
			font-size: 3rem;
			color: var(--orion-primary);
			margin-bottom: 1rem;
		}

		.btn-nav-wizard {
			min-width: 120px;
		}

		/* Calculadora Consultiva */
		.calculadora-card {
			background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
			border: 2px solid var(--orion-primary);
			border-radius: 1rem;
			padding: 1.5rem;
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.1);
			position: sticky;
			top: 100px;
		}

		.calculadora-card h5 {
			color: var(--orion-primary);
			font-weight: 700;
			margin-bottom: 1rem;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}

		.calc-tabs {
			border: none;
			gap: 0;
		}

		.calc-tabs .nav-item {
			flex: 1;
		}

		.calc-tabs .nav-link {
			border: 1px solid #dee2e6;
			border-radius: 0.5rem 0.5rem 0 0;
			color: #6c757d;
			font-weight: 500;
			font-size: 0.75rem;
			padding: 0.4rem 0.3rem;
			text-align: center;
			white-space: nowrap;
			line-height: 1.2;
		}

		.calc-tabs .nav-link.active {
			background: white;
			color: var(--orion-primary);
			border-color: var(--orion-primary);
			border-bottom-color: white;
			font-weight: 600;
		}

		.calc-content {
			background: white;
			border: 1px solid var(--orion-primary);
			border-radius: 0 0.5rem 0.5rem 0.5rem;
			padding: 1.25rem;
		}

		.calc-result {
			background: linear-gradient(135deg, var(--orion-success) 0%, #027a2e 100%);
			color: white;
			padding: 1rem;
			border-radius: 0.5rem;
			text-align: center;
			margin-top: 1rem;
			font-weight: 700;
			font-size: 1.5rem;
			box-shadow: 0 4px 10px rgba(0, 129, 46, 0.3);
		}

		.calc-result small {
			display: block;
			font-size: 0.75rem;
			font-weight: 400;
			opacity: 0.9;
			margin-top: 0.25rem;
		}

		.calc-input-group {
			margin-bottom: 0.75rem;
		}

		.calc-input-group label {
			font-size: 0.85rem;
			font-weight: 600;
			color: #495057;
			margin-bottom: 0.25rem;
		}

		.calc-input-group input {
			font-size: 1.1rem;
			font-weight: 600;
			text-align: center;
		}

		@media (max-width: 991px) {
			.calculadora-card {
				position: static;
				margin-top: 2rem;
			}
		}

		/* Ajuste específico para tablets/desktops pequenos (992-1199px) */
		@media (min-width: 992px) and (max-width: 1199px) {
			.calc-tabs .nav-link {
				font-size: 0.7rem;
				padding: 0.35rem 0.25rem;
			}

			.calculadora-card h5 {
				font-size: 0.95rem;
			}

			.calculadora-card p.small {
				font-size: 0.7rem;
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
			<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
				aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
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
							<a class="nav-link" href="minhas-sugestoes.php">
								<i class="fab fa-wpforms"></i> Meus Kaizens
							</a>
						</li>
						<li class="nav-item d-lg-none">
							<a class="nav-link active" href="novo-kaizen.php">
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
				<li class="breadcrumb-item active" aria-current="page">Novo Kaizen</li>
			</ol>
		</nav>

		<!-- Mensagens -->
		<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>

		<!-- Formulário Wizard -->
		<div class="form-wizard">
			<div class="wizard-header">
				<i class="fas fa-lightbulb"></i>
				<h2 class="mb-0">Nova Sugestão Kaizen</h2>
				<p class="mb-0 mt-2 opacity-75">Compartilhe sua ideia de melhoria contínua</p>
			</div>

			<div class="wizard-body">

				<!-- Indicadores de Etapa -->
				<div class="step-indicator">
					<div class="step-dot active" data-step="1"></div>
					<div class="step-dot" data-step="2"></div>
					<div class="step-dot" data-step="3"></div>
				</div>

				<form action="app/func_cadastra_kaizen.php" method="post" enctype="multipart/form-data" id="formKaizen">

					<!-- Etapa 1: Tipo de Benefício -->
					<div class="form-step active" id="step1">
						<h4 class="mb-4 text-center"><i class="fas fa-clipboard-list text-primary"></i> Tipo de
							Benefício</h4>

						<div class="row justify-content-center">
							<div class="col-lg-7">

								<div class="mb-4">
									<label for="tipo" class="form-label fw-bold">Selecione o tipo de benefício *</label>
									<select name="tipo" required id="tipo" class="form-select form-select-lg">
										<option value="">Escolha uma opção</option>
										<?php
										$sql = sql("SELECT tipo.*, comp.nome_comp 
										            FROM tipo 
										            LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo 
										            ORDER BY tipo.numero_tipo ASC", $con);
										while ($dados = mysqli_fetch_assoc($sql)) {
											$label = $dados['nome_tipo'];
											if (!empty($dados['nome_comp'])) {
												$label .= ' - ' . $dados['nome_comp'];
											}
											?>
											<option data-complemento="<?= $dados['complemento_tipo'] ?>"
												data-add="<?= $dados['add_tipo'] ?>" value="<?= $dados['id_tipo'] ?>">
												<?= $label ?></option>
										<?php } ?>
									</select>
								</div>

								<!-- Campo adicional (Outros) -->
								<div id="campo-add-tipo" class="mb-4" style="display:none;">
									<label for="add_tipo_texto" class="form-label fw-bold">Classifique a ideia *</label>
									<input name="add_tipo_texto" type="text" id="add_tipo_texto" class="form-control"
										value="" placeholder="Descreva brevemente">
									<div class="form-text">Em caso de Categoria "Outros", especifique aqui</div>
								</div>

								<!-- Campos de Complemento (v2.1 - Entrada Direta) -->
								<?php
								$sql = sql("SELECT * FROM comp ORDER BY id_comp ASC", $con);
								while ($dados = mysqli_fetch_assoc($sql)) {
									$tipo_entrada = $dados['tipo_entrada']; // 'reais' ou 'horas'
									$is_float = ($dados['formato_comp'] == 2);
									?>
									<div class="campo-complemento campo-comp-<?= $dados['id_comp'] ?> mb-4"
										style="display:none;">
										<label for="valor_complemento_<?= $dados['id_comp'] ?>" class="form-label fw-bold">
											<?= $dados['nome_comp'] ?> *
										</label>

										<?php if ($tipo_entrada == 'reais'): ?>
											<div class="input-group input-group-lg">
												<span class="input-group-text">R$</span>
												<input name="valor_complemento_<?= $dados['id_comp'] ?>" type="text"
													id="valor_complemento_<?= $dados['id_comp'] ?>"
													class="form-control money input-complemento"
													data-comp-id="<?= $dados['id_comp'] ?>" placeholder="0,00">
											</div>
											<div class="form-text">Informe o valor anual em reais economizados</div>
										<?php elseif ($tipo_entrada == 'horas'): ?>
											<div class="input-group input-group-lg">
												<input name="valor_complemento_<?= $dados['id_comp'] ?>" type="number"
													id="valor_complemento_<?= $dados['id_comp'] ?>"
													class="form-control input-complemento"
													data-comp-id="<?= $dados['id_comp'] ?>" placeholder="0" min="0" step="1">
												<span class="input-group-text">horas</span>
											</div>
											<div class="form-text">Informe a quantidade de horas poupadas por ano</div>
										<?php endif; ?>

										<?php if ($dados['id_comp'] == 1 && !empty($alert['texto3_pg'])): ?>
											<div class="alert-custom" id="alert-custo">
												<img src="lib/img/alert.png" alt="Alerta" />
												<?= nl2br($alert['texto3_pg']) ?>
												<br><strong><a id="bt-ciente" class="text-decoration-underline"
														style="cursor:pointer;">Estou ciente</a></strong>
											</div>
										<?php endif; ?>
									</div>
								<?php } ?>

								<input type="hidden" name="complemento_ativo" id="complemento_ativo" value="0">

								<div class="d-flex justify-content-end mt-4">
									<button type="button" class="btn btn-primary btn-lg btn-nav-wizard" id="btnNext1">
										Próximo <i class="fas fa-arrow-right ms-2"></i>
									</button>
								</div>

							</div>

							<!-- Calculadora Consultiva -->
							<div class="col-lg-5">
								<div class="calculadora-card">
									<h5>
										<i class="fas fa-calculator"></i>
										Calculadora de Saving/Ganhos
									</h5>
									<p class="text-muted small mb-3">Use esta ferramenta para estimar o valor anual em
										R$.</p>

									<!-- Tabs -->
									<ul class="nav nav-tabs calc-tabs" id="calcTabs" role="tablist">
										<li class="nav-item" role="presentation">
											<button class="nav-link active" id="tab-linha-parada" data-bs-toggle="tab"
												data-bs-target="#calc-linha-parada" type="button" role="tab">
												Custo Linha Parada (anual)
											</button>
										</li>
										<li class="nav-item" role="presentation">
											<button class="nav-link" id="tab-produtividade" data-bs-toggle="tab"
												data-bs-target="#calc-produtividade" type="button" role="tab">
												Produtividade (anual)
											</button>
										</li>
									</ul>

									<!-- Tab Content -->
									<div class="tab-content calc-content">
										<!-- Aba 1: Custo Linha Parada -->
										<div class="tab-pane fade show active" id="calc-linha-parada" role="tabpanel">
											<div class="calc-input-group">
												<label for="calc-horas">Informe as horas poupadas:</label>
												<div class="input-group">
													<input type="number" class="form-control" id="calc-horas"
														placeholder="0" min="0" step="1">
													<span class="input-group-text">h/ano</span>
												</div>
											</div>
											<div class="calc-result" id="resultado-horas">
												R$ 0,00
												<small>Valor estimado anual</small>
											</div>
										</div>

										<!-- Aba 2: Produtividade -->
										<div class="tab-pane fade" id="calc-produtividade" role="tabpanel">
											<div class="calc-input-group">
												<label for="calc-kg">Informe o aumento de produção em kg de Negro de Fumo:</label>
												<div class="input-group">
													<input type="number" class="form-control" id="calc-kg"
														placeholder="0" min="0" step="0.01">
													<span class="input-group-text">kg CB/ano</span>
												</div>
											</div>
											<div class="calc-result" id="resultado-kg">
												R$ 0,00
												<small>Valor estimado anual</small>
											</div>
										</div>
									</div>

									<div class="alert alert-info mt-3 mb-0" style="font-size: 0.75rem;">
										<i class="fas fa-info-circle"></i>
										<strong>Nota:</strong> Esta calculadora é apenas consultiva. O valor NÃO será
										inserido no formulário.
									</div>
								</div>
							</div>
						</div>
					</div>

					<!-- Etapa 2: Descrição da Melhoria -->
					<div class="form-step" id="step2">
						<h4 class="mb-4 text-center"><i class="fas fa-edit text-primary"></i> Descrição da Melhoria</h4>

						<div class="row justify-content-center">
							<div class="col-lg-10">

								<div class="mb-4">
									<label for="situacao_atual" class="form-label fw-bold">Situação Atual - Descreva
										*</label>
									<textarea name="situacao_atual" required id="situacao_atual" class="form-control"
										rows="4" placeholder="Descreva a situação atual antes da melhoria"></textarea>
									<div class="form-text">Como era antes da implementação da sua ideia?</div>
								</div>

								<div class="mb-4">
									<label for="onde" class="form-label fw-bold">Objetivo da melhoria: O que foi feito e
										onde foi aplicada a ideia? *</label>
									<textarea name="onde" required id="onde" class="form-control" rows="4"
										placeholder="Descreva o que foi feito e onde foi aplicado"></textarea>
									<div class="form-text">Explique a melhoria implementada e em qual área/processo
									</div>
								</div>

								<div class="mb-4">
									<label for="resultado" class="form-label fw-bold">Resultados obtidos - Descreva
										*</label>
									<textarea name="resultado" required id="resultado" class="form-control" rows="4"
										placeholder="Quais os resultados obtidos? Descreva"></textarea>
									<div class="form-text">Quais os benefícios e ganhos após a implementação?</div>
								</div>

								<div class="d-flex justify-content-between mt-4">
									<button type="button" class="btn btn-outline-secondary btn-lg btn-nav-wizard"
										id="btnPrev2">
										<i class="fas fa-arrow-left me-2"></i> Voltar
									</button>
									<button type="button" class="btn btn-primary btn-lg btn-nav-wizard" id="btnNext2">
										Próximo <i class="fas fa-arrow-right ms-2"></i>
									</button>
								</div>

							</div>
						</div>
					</div>

					<!-- Etapa 3: Anexos e Colaboradores -->
					<div class="form-step" id="step3">
						<h4 class="mb-4 text-center"><i class="fas fa-paperclip text-primary"></i> Anexos e
							Colaboradores</h4>

						<div class="row justify-content-center">
							<div class="col-lg-10">

								<div class="mb-4">
									<label for="anexos" class="form-label fw-bold">Anexos - Base de cálculo, Fotos antes
										e depois *</label>
									<div class="file-upload-area" onclick="document.getElementById('anexos').click()">
										<i class="fas fa-cloud-upload-alt"></i>
										<p class="mb-0"><strong>Clique aqui para selecionar arquivos</strong></p>
										<small class="text-muted">JPG, PNG, PDF, Excel, Word, ZIP (máx 10MB por
											arquivo)</small>
										<p class="mt-2 mb-0" id="file-count"></p>
									</div>
									<input type="file" class="d-none" name="anexos[]" id="anexos" multiple
										accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
								</div>

								<div class="mb-4">
									<label for="outrosdep_kaizen" class="form-label fw-bold">A ideia pode ser
										implementada em outro(s) departamento(s)? *</label>
									<select name="outrosdep_kaizen" required id="outrosdep_kaizen" class="form-select">
										<option value="">Selecione</option>
										<option value="1">Sim</option>
										<option value="2">Não</option>
									</select>
								</div>

								<div class="mb-4">
									<label for="qual_kaizen" class="form-label fw-bold">Se sim, quais
										departamentos?</label>
									<input name="qual_kaizen" type="text" id="qual_kaizen" class="form-control"
										placeholder="Digite os departamentos">
								</div>

								<div class="mb-4">
									<label class="form-label fw-bold">Colaboradores que participaram do Kaizen</label>
									<p class="form-text">Selecione até 2 colaboradores diferentes que ajudaram na
										implementação</p>

									<div class="row">
										<div class="col-md-6 mb-3 mb-md-0">
											<select name="colaborador1" id="colaborador1" class="form-select">
												<option value="">Colaborador 1 (opcional)</option>
												<?php
												$sql = sql("SELECT * FROM colaborador WHERE id_colaborador != " . trata($_SESSION['usu']) . " AND status_colaborador != 2 ORDER BY nome_colaborador ASC", $con);
												while ($dados = mysqli_fetch_assoc($sql)) {
													?>
													<option value="<?= $dados['id_colaborador'] ?>">
														<?= $dados['nome_colaborador'] ?></option>
												<?php } ?>
											</select>
										</div>
										<div class="col-md-6">
											<select name="colaborador2" id="colaborador2" class="form-select">
												<option value="">Colaborador 2 (opcional)</option>
												<?php
												$sql = sql("SELECT * FROM colaborador WHERE id_colaborador != " . trata($_SESSION['usu']) . " AND status_colaborador != 2 ORDER BY nome_colaborador ASC", $con);
												while ($dados = mysqli_fetch_assoc($sql)) {
													?>
													<option value="<?= $dados['id_colaborador'] ?>">
														<?= $dados['nome_colaborador'] ?></option>
												<?php } ?>
											</select>
										</div>
									</div>
								</div>

								<div class="d-flex justify-content-between mt-4">
									<button type="button" class="btn btn-outline-secondary btn-lg btn-nav-wizard"
										id="btnPrev3">
										<i class="fas fa-arrow-left me-2"></i> Voltar
									</button>
									<button type="submit" class="btn btn-success btn-lg btn-nav-wizard">
										<i class="fas fa-paper-plane me-2"></i> Enviar Kaizen
									</button>
								</div>

							</div>
						</div>
					</div>

				</form>
			</div>
		</div>

	</main>

	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

	<script>
		// JavaScript para navegação do wizard e validações
		$(document).ready(function () {

			// Máscaras
			$('.money').maskMoney({ thousands: '.', decimal: ',', allowZero: true });
			$('.numero').inputmask('integer', { min: 0, max: 999999999 });

			// ========== CALCULADORA CONSULTIVA ==========
			// Variáveis do sistema (vem do PHP)
			const custoLinhaParada = <?= $custo_linha_parada ?>;
			const produtividadeUSD = <?= $produtividade_usd ?>;
			const cambioDolar = <?= $cambio_dolar ?>;

			// Função para formatar valor em R$
			function formatarReais(valor) {
				return 'R$ ' + valor.toLocaleString('pt-BR', { minimumFractionDigits: 2, maximumFractionDigits: 2 });
			}

			// Calculadora: Custo Linha Parada (horas)
			$('#calc-horas').on('input', function () {
				let horas = parseFloat($(this).val()) || 0;
				let valorReais = horas * custoLinhaParada;
				$('#resultado-horas').html(formatarReais(valorReais) + '<small>Valor estimado anual</small>');
			});

			// Calculadora: Produtividade (kg carbono)
			$('#calc-kg').on('input', function () {
				let kgCarbono = parseFloat($(this).val()) || 0;
				let valorReais = kgCarbono * (produtividadeUSD * cambioDolar);
				$('#resultado-kg').html(formatarReais(valorReais) + '<small>Valor estimado anual</small>');
			});
			// ========== FIM CALCULADORA CONSULTIVA ==========

			// Navegação entre etapas
			let currentStep = 1;

			function showStep(step) {
				$('.form-step').removeClass('active');
				$('#step' + step).addClass('active');

				// Atualizar indicadores
				$('.step-dot').removeClass('active');
				$('.step-dot[data-step="' + step + '"]').addClass('active');

				// Scroll to top
				$('html, body').animate({ scrollTop: 0 }, 300);
			}

			$('#btnNext1').on('click', function () {
				let tipo = $('#tipo').val();
				if (tipo == '') {
					alert('Por favor, selecione o tipo de benefício!');
					return;
				}

				// Verificar se campo adicional está visível e preenchido
				if ($('#campo-add-tipo').is(':visible')) {
					if ($('#add_tipo_texto').val() == '') {
						alert('Por favor, classifique a ideia!');
						return;
					}
				}

				// Verificar se campo complemento está visível e preenchido
				let complemento_ativo = $('#complemento_ativo').val();
				if (complemento_ativo > 0) {
					let valor_comp = $('#valor_complemento_' + complemento_ativo).val();
					if (valor_comp == '' || valor_comp == '0' || valor_comp == '0,00') {
						alert('Por favor, preencha o valor do complemento!');
						return;
					}
				}

				currentStep = 2;
				showStep(2);
			});

			$('#btnNext2').on('click', function () {
				if ($('#situacao_atual').val() == '') {
					alert('Por favor, descreva a situação atual!');
					return;
				}
				if ($('#onde').val() == '') {
					alert('Por favor, descreva o que foi feito e onde!');
					return;
				}
				if ($('#resultado').val() == '') {
					alert('Por favor, descreva os resultados obtidos!');
					return;
				}

				currentStep = 3;
				showStep(3);
			});

			$('#btnPrev2').on('click', function () {
				currentStep = 1;
				showStep(1);
			});

			$('#btnPrev3').on('click', function () {
				currentStep = 2;
				showStep(2);
			});

			// Evento ao selecionar tipo
			$('#tipo').on('change', function () {
				let complemento_id = $(this).find(':selected').data('complemento');
				let add_tipo = $(this).find(':selected').data('add');

				// Esconder todos os campos de complemento
				$('.campo-complemento').hide().find('input').prop('required', false).val('');

				// Esconder campo adicional
				$('#campo-add-tipo').hide();
				$('#add_tipo_texto').prop('required', false).val('');

				// Esconder alerta de custo
				$('#alert-custo').hide();

				// Resetar complemento ativo
				$('#complemento_ativo').val(0);

				// Se tipo tem complemento, exibir campo correspondente
				if (complemento_id > 0) {
					$('.campo-comp-' + complemento_id).show().find('input').prop('required', true);
					$('#complemento_ativo').val(complemento_id);
				}

				// Se tipo requer campo adicional (Outros)
				if (add_tipo == 1) {
					$('#campo-add-tipo').show();
					$('#add_tipo_texto').prop('required', true);
				}
			});

			// Validação de alerta de custo
			$('#valor_complemento_1').on('blur', function () {
				let valor_str = $(this).val().replace('.', '').replace(',', '.');
				let valor = parseFloat(valor_str);
				let limite = parseFloat('<?= $alert['nome3_pg']; ?>');

				if (valor > limite) {
					$('#alert-custo').slideDown();
				} else {
					$('#alert-custo').slideUp();
				}
			});

			// Botão "Estou ciente"
			$('#bt-ciente').on('click', function () {
				$('#alert-custo').slideUp();
			});

			// Contador de arquivos
			$('#anexos').on('change', function () {
				let count = this.files.length;
				if (count > 0) {
					$('#file-count').html('<span class="badge bg-success">' + count + ' arquivo(s) selecionado(s)</span>');
				} else {
					$('#file-count').html('');
				}
			});

			// Drag and Drop para anexos
			const dropArea = $('.file-upload-area');
			const fileInput = $('#anexos')[0];

			// Prevenir comportamento padrão para toda a área
			['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
				dropArea.on(eventName, function (e) {
					e.preventDefault();
					e.stopPropagation();
				});
			});

			// Highlight ao arrastar sobre a área
			['dragenter', 'dragover'].forEach(eventName => {
				dropArea.on(eventName, function () {
					$(this).addClass('drag-over');
				});
			});

			['dragleave', 'drop'].forEach(eventName => {
				dropArea.on(eventName, function () {
					$(this).removeClass('drag-over');
				});
			});

			// Processar arquivos soltos
			dropArea.on('drop', function (e) {
				const dt = e.originalEvent.dataTransfer;
				const files = dt.files;

				// Atribuir arquivos ao input
				fileInput.files = files;

				// Disparar evento change para atualizar contador
				$('#anexos').trigger('change');
			});	    // Validação do formulário
			$('form#formKaizen').on('submit', function (e) {
				let col1 = $('#colaborador1').val();
				let col2 = $('#colaborador2').val();

				if (col1 != '' && col2 != '' && col1 == col2) {
					e.preventDefault();
					alert('Os colaboradores selecionados não podem ser iguais!');
					return false;
				}

				// Validar se pelo menos 1 anexo foi selecionado
				let anexos = $('#anexos')[0].files.length;
				if (anexos == 0) {
					e.preventDefault();
					alert('Por favor, anexe pelo menos 1 arquivo (foto, documento, etc)!');
					return false;
				}
			});

		});
	</script>

</body>

</html>