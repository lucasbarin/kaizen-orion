<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");

$id_kaizen = trata($_GET['kaizen']);

if (!empty($id_kaizen) && is_numeric($id_kaizen)){
	$sql = mysqli_query($con, "SELECT * FROM kaizen
	LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
	LEFT JOIN categoria ON categoria.id_categoria = kaizen.categoria_kaizen
	LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
	LEFT JOIN setor ON setor.id_setor = colaborador.setor_colaborador
	LEFT JOIN unidade ON unidade.id_unidade = colaborador.unidade_colaborador
	WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die(mysqli_error($con));
	 
	if(mysqli_num_rows($sql) > 0){
		$kaizen = mysqli_fetch_array($sql);
	} else {
		volta("erro", "Registro não encontrado!", "home.php");
	} 
} else {
	volta("erro", "Registro não encontrado!", "home.php");
}

if ($kaizen['colaborador_kaizen'] <> $_SESSION['usu']){
	volta("erro", "Este formulário pertence a outro usuário. Você não tem permissão para acessá-lo!", "home.php");	
}

if($kaizen['status_kaizen'] <> 4){
	volta("erro", "Apenas um Kaizen devolvido pode ser editado. Este já foi finalizado!", "home.php");	
}

// Buscar informações do tipo e complemento (v2)
$sql_tipo = sql("SELECT tipo.*, comp.nome_comp, comp.formato_comp, comp.calculo_comp 
                 FROM tipo 
                 LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo 
                 WHERE tipo.id_tipo = ".$kaizen['tipo_kaizen']." LIMIT 1", $con);
$tipo_info = mysqli_fetch_array($sql_tipo);
?>
<!DOCTYPE html>
<html lang="pt-br"><!-- InstanceBegin template="/Templates/kaizen-bs5.dwt.php" codeOutsideHTMLIsLocked="false" -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<!-- InstanceBeginEditable name="doctitle" -->
	<title>Editar Kaizen #<?= $kaizen['id_kaizen'] ?> | Sistema Orion Kaizen</title>
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
	<script src="lib/js/jquery.inputmask.bundle.min.js"></script> 
	<script src="lib/js/jquery.maskMoney.js"></script>
	
	<!-- InstanceBeginEditable name="head" -->
	<style>
		.edit-header {
			background: linear-gradient(135deg, #ff9800 0%, #f57c00 100%);
			color: white;
			padding: 2rem;
			border-radius: 1rem;
			margin-bottom: 2rem;
			box-shadow: 0 4px 20px rgba(255, 152, 0, 0.2);
		}
		
	.edit-header h1 {
		font-size: 2rem;
		font-weight: 700;
		margin: 0;
		color: white;
	}		.form-section {
			background: white;
			border-radius: 0.75rem;
			padding: 2rem;
			margin-bottom: 2rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
		}
		
		.form-section h5 {
			color: var(--orion-primary);
			font-weight: 600;
			margin-bottom: 1.5rem;
			padding-bottom: 0.75rem;
			border-bottom: 2px solid #f0f0f0;
			display: flex;
			align-items: center;
			gap: 0.5rem;
		}
		
		.campo-complemento {
			display: none;
		}
		
		.campo-complemento.show {
			display: block;
		}
		
		.current-image {
			max-width: 100%;
			border-radius: 0.5rem;
			box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
			margin: 1rem 0;
		}
		
		.file-upload-label {
			display: block;
			padding: 1rem;
			background: #f8f9fa;
			border: 2px dashed #dee2e6;
			border-radius: 0.5rem;
			text-align: center;
			cursor: pointer;
			transition: all 0.3s ease;
		}
		
		.file-upload-label:hover {
			background: #e9ecef;
			border-color: var(--orion-primary);
		}
		
		.file-upload-label i {
			font-size: 2rem;
			color: var(--orion-primary);
			display: block;
			margin-bottom: 0.5rem;
		}
		
		input[type="file"] {
			display: none;
		}
		
		.limpa-input {
			display: none;
			margin-top: 0.5rem;
		}
		
		.alert-warning-custom {
			background: linear-gradient(135deg, #fff3cd 0%, #ffe69c 100%);
			border: 2px solid #ffc107;
			border-radius: 0.75rem;
			padding: 1.5rem;
			margin-bottom: 2rem;
		}
		
		/* Anexos */
		.file-upload-area {
			border: 3px dashed #dee2e6;
			border-radius: 1rem;
			padding: 3rem 2rem;
			text-align: center;
			cursor: pointer;
			transition: all 0.3s ease;
			background: #f8f9fa;
		}
		
	.file-upload-area:hover {
		border-color: var(--orion-primary);
		background: #e7f3ff;
	}
	
	.file-upload-area.drag-over {
		border-color: var(--orion-primary);
		background: #cfe8ff;
		border-width: 4px;
		transform: scale(1.02);
	}
	
	.file-upload-area i {
		font-size: 3rem;
		color: var(--orion-primary);
		margin-bottom: 1rem;
		display: block;
	}		.anexos-atuais {
			display: grid;
			grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
			gap: 1rem;
			margin-top: 1.5rem;
		}
		
		.anexo-atual-item {
			background: #f8f9fa;
			border: 2px solid #e9ecef;
			border-radius: 0.75rem;
			padding: 1rem;
			display: flex;
			align-items: center;
			gap: 1rem;
		}
		
		.anexo-atual-icon {
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
		
		.anexo-atual-icon.pdf { color: #dc3545; }
		.anexo-atual-icon.doc { color: #2b579a; }
		.anexo-atual-icon.xls { color: #107c41; }
		.anexo-atual-icon.zip { color: #6c757d; }
		.anexo-atual-icon.img { color: #0dcaf0; }
		
		.anexo-atual-info {
			flex: 1;
			min-width: 0;
		}
		
		.anexo-atual-nome {
			font-weight: 600;
			color: var(--orion-text);
			margin-bottom: 0.25rem;
			word-break: break-word;
			font-size: 0.9rem;
		}
		
		.anexo-atual-meta {
			font-size: 0.8rem;
			color: var(--orion-text-light);
		}
		
		.anexo-atual-item.deletado {
			opacity: 0.5;
			border-color: #dc3545;
			background: #f8d7da;
		}
		
		@media (max-width: 768px) {
			.edit-header h1 {
				font-size: 1.5rem;
			}
			
			.form-section {
				padding: 1.5rem;
			}
			
			.anexos-atuais {
				grid-template-columns: 1fr;
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
				<li class="breadcrumb-item"><a href="minhas-sugestoes.php">Meus Kaizens</a></li>
				<li class="breadcrumb-item active" aria-current="page">Editar Kaizen #<?= $kaizen['id_kaizen'] ?></li>
			</ol>
		</nav>
		
		<!-- Header -->
		<div class="edit-header">
			<h1><i class="fas fa-edit"></i> Editar Kaizen Devolvido #<?= $kaizen['id_kaizen'] ?></h1>
		</div>
		
		<!-- Motivo da Devolução -->
		<?php if ($kaizen['obs_kaizen']): ?>
		<div class="alert-warning-custom">
			<h5 class="mb-3"><i class="fas fa-exclamation-triangle"></i> Motivo da Devolução:</h5>
			<p class="mb-0" style="line-height: 1.8;"><?= nl2br($kaizen['obs_kaizen']) ?></p>
		</div>
		<?php endif; ?>
		
		<!-- Formulário -->
		<form action="app/func_edita_kaizen.php" method="post" enctype="multipart/form-data" id="formKaizen">
			<input name="id_kaizen" type="hidden" value="<?= $kaizen['id_kaizen'] ?>">
			
			<!-- Tipo de Benefício -->
			<div class="form-section">
				<h5><i class="fas fa-tag"></i> Tipo de Benefício</h5>
				
				<div class="mb-3">
					<label for="tipo" class="form-label">Tipo de Benefício: *</label>
					<select name="tipo" id="tipo" class="form-select" required>
						<option value="">Selecione o tipo</option>
						<?php
						$sql = sql("SELECT tipo.*, comp.nome_comp 
						            FROM tipo 
						            LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo 
						            ORDER BY tipo.numero_tipo ASC", $con);	
						while ($dados = mysqli_fetch_assoc($sql)){
						    $label = $dados['nome_tipo'];
						    if(!empty($dados['nome_comp'])){
						        $label .= ' - ' . $dados['nome_comp'];
						    }
						?>
						<option <?php if ($kaizen['tipo_kaizen'] == $dados['id_tipo']){ echo 'selected="selected"'; } ?> 
								data-complemento="<?= $dados['complemento_tipo'] ?>" 
								data-add="<?= $dados['add_tipo'] ?>" 
								value="<?= $dados['id_tipo'] ?>">
							<?= $label ?>
						</option>
						<?php } ?>
					</select>
				</div>
				
				<!-- Campo adicional (Outros) -->
				<div id="campo-add-tipo" class="mb-3" style="<?= ($tipo_info['add_tipo'] == 1) ? '' : 'display:none;' ?>">
					<label for="add_tipo_texto" class="form-label fw-bold">Classifique a ideia *</label>
					<input name="add_tipo_texto" type="text" id="add_tipo_texto" class="form-control" value="<?= $kaizen['add_tipo_texto'] ?>" placeholder="Descreva brevemente">
					<div class="form-text">Em caso de Categoria "Outros", especifique aqui</div>
				</div>
				
				<!-- Campos de Complemento v2.1 - Entrada Direta -->
				<?php
				$sql = sql("SELECT * FROM comp ORDER BY id_comp ASC", $con);	
				while ($dados = mysqli_fetch_assoc($sql)){
					// Determinar se este complemento está ativo
					$is_active = ($kaizen['tipo_complemento_kaizen'] == $dados['id_comp']);
					$valor_atual = ($is_active && !empty($kaizen['valor_original_kaizen'])) ? $kaizen['valor_original_kaizen'] : '';
					
					$tipo_entrada = $dados['tipo_entrada']; // 'reais' ou 'horas'
					
					// Formatar valor
					if(!empty($valor_atual)){
						if($tipo_entrada == 'reais'){
							$valor_atual = number_format($valor_atual, 2, ",", "");
						} else {
							$valor_atual = number_format($valor_atual, 0, ",", "");
						}
					}
				?>
				<div class="campo-complemento campo-comp-<?= $dados['id_comp'] ?> mb-3" style="<?= $is_active ? '' : 'display:none;' ?>">
					<label for="valor_complemento_<?= $dados['id_comp'] ?>" class="form-label fw-bold">
						<?= $dados['nome_comp'] ?> *
					</label>
					
					<?php if($tipo_entrada == 'reais'): ?>
						<div class="input-group input-group-lg">
							<span class="input-group-text">R$</span>
							<input name="valor_complemento_<?= $dados['id_comp'] ?>" 
								   type="text" 
								   id="valor_complemento_<?= $dados['id_comp'] ?>"
								   class="form-control money input-complemento" 
								   data-comp-id="<?= $dados['id_comp'] ?>" 
								   value="<?= $valor_atual ?>"
								   placeholder="0,00">
						</div>
						<div class="form-text">Informe o valor anual em reais economizados</div>
					<?php elseif($tipo_entrada == 'horas'): ?>
						<div class="input-group input-group-lg">
							<input name="valor_complemento_<?= $dados['id_comp'] ?>" 
								   type="number" 
								   id="valor_complemento_<?= $dados['id_comp'] ?>"
								   class="form-control input-complemento" 
								   data-comp-id="<?= $dados['id_comp'] ?>" 
								   value="<?= $valor_atual ?>"
								   placeholder="0"
								   min="0"
								   step="1">
							<span class="input-group-text">horas</span>
						</div>
						<div class="form-text">Informe a quantidade de horas poupadas por ano</div>
					<?php endif; ?>
				</div>
				<?php } ?>
				
				<input type="hidden" name="complemento_ativo" id="complemento_ativo" value="<?= $kaizen['tipo_complemento_kaizen'] ?>">
			</div>
			
			<!-- Descrição -->
			<div class="form-section">
				<h5><i class="fas fa-clipboard-list"></i> Descrição do Kaizen</h5>
				
				<div class="mb-3">
					<label for="situacao_atual" class="form-label">Situação Atual - Descreva *</label>
					<textarea name="situacao_atual" id="situacao_atual" class="form-control" rows="4" 
							  placeholder="Descreva a situação atual antes da melhoria" required><?= $kaizen['situacao_atual'] ?></textarea>
					<div class="form-text">Como era antes da implementação da sua ideia?</div>
				</div>
				
				<div class="mb-3">
					<label for="onde" class="form-label">Objetivo da melhoria: O que foi feito e onde foi aplicada a ideia? *</label>
					<textarea name="onde" id="onde" class="form-control" rows="4" 
							  placeholder="Descreva o que foi feito e onde foi aplicado" required><?= $kaizen['onde_kaizen'] ?></textarea>
					<div class="form-text">Explique a melhoria implementada e em qual área/processo</div>
				</div>
				
				<div class="mb-3">
					<label for="resultado" class="form-label">Resultados obtidos - Descreva *</label>
					<textarea name="resultado" id="resultado" class="form-control" rows="4" 
							  placeholder="Quais os resultados obtidos? Descreva" required><?= $kaizen['resultado_kaizen'] ?></textarea>
					<div class="form-text">Quais os benefícios e ganhos após a implementação?</div>
				</div>
			</div>
			
			<!-- Anexos -->
			<div class="form-section">
				<h5><i class="fas fa-paperclip"></i> Arquivos Anexos</h5>
				<p class="text-muted">Adicione arquivos que comprovem a melhoria implementada (fotos antes e depois, planilhas de cálculo, documentos, etc.)</p>
				
				<!-- Anexos Atuais -->
				<?php 
				$anexos_atuais = listarAnexos($kaizen['id_kaizen'], $con);
				if (!empty($anexos_atuais) && count($anexos_atuais) > 0): 
				?>
				<div class="mb-4">
					<label class="form-label fw-bold">Anexos já cadastrados:</label>
					<div class="anexos-atuais" id="anexos-atuais">
						<?php foreach ($anexos_atuais as $anexo): 
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
							
							$tamanho = $anexo['tamanho_arquivo'];
							if ($tamanho < 1024) {
								$tamanho_formatado = $tamanho . ' B';
							} elseif ($tamanho < 1048576) {
								$tamanho_formatado = round($tamanho / 1024, 2) . ' KB';
							} else {
								$tamanho_formatado = round($tamanho / 1048576, 2) . ' MB';
							}
						?>
						<div class="anexo-atual-item" id="anexo-<?= $anexo['id_anexo'] ?>">
							<div class="anexo-atual-icon <?= $icon_color ?>">
								<i class="fas <?= $icon_class ?>"></i>
							</div>
							<div class="anexo-atual-info">
								<div class="anexo-atual-nome"><?= htmlspecialchars($anexo['nome_original']) ?></div>
								<div class="anexo-atual-meta">
									<i class="fas fa-hdd"></i> <?= $tamanho_formatado ?>
								</div>
							</div>
							<div class="btn-group-vertical">
								<a href="imgs/kaizen/<?= $anexo['nome_arquivo'] ?>" 
								   class="btn btn-sm btn-outline-primary" 
								   download="<?= htmlspecialchars($anexo['nome_original']) ?>"
								   target="_blank"
								   title="Baixar">
									<i class="fas fa-download"></i>
								</a>
								<button type="button" 
										class="btn btn-sm btn-outline-danger btn-excluir-anexo" 
										data-anexo-id="<?= $anexo['id_anexo'] ?>"
										data-anexo-nome="<?= htmlspecialchars($anexo['nome_original']) ?>"
										title="Excluir">
									<i class="fas fa-trash"></i>
								</button>
							</div>
						</div>
						<?php endforeach; ?>
					</div>
				</div>
				<?php else: ?>
				<p class="text-muted fst-italic mb-4">Nenhum anexo cadastrado ainda.</p>
				<?php endif; ?>
				
				<!-- Upload de Novos Anexos -->
				<div class="mb-3">
					<label for="novos_anexos" class="form-label fw-bold">Adicionar novos anexos:</label>
					<div class="file-upload-area" onclick="document.getElementById('novos_anexos').click()">
						<i class="fas fa-cloud-upload-alt"></i>
						<p class="mb-0"><strong>Clique aqui para selecionar arquivos</strong></p>
						<small class="text-muted">JPG, PNG, PDF, Excel, Word, ZIP (máx 10MB por arquivo)</small>
						<p class="mt-2 mb-0" id="file-count-novos"></p>
					</div>
					<input type="file" 
						   class="d-none" 
						   name="novos_anexos[]" 
						   id="novos_anexos" 
						   multiple 
						   accept=".jpg,.jpeg,.png,.gif,.pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
				</div>
				
				<input type="hidden" name="anexos_excluir" id="anexos_excluir" value="">
			</div>
			
			<!-- Implementação -->
			<div class="form-section">
				<h5><i class="fas fa-sitemap"></i> Aplicação em Outros Departamentos</h5>
				
				<div class="mb-3">
					<label for="outrosdep_kaizen" class="form-label">
						A ideia pode ser implementada em outro(s) departamento(s)?
					</label>
					<select name="outrosdep_kaizen" id="outrosdep_kaizen" class="form-select">
						<option value="">Selecione</option>
						<option value="1" <?php if ($kaizen['outrosdep_kaizen'] == 1){ echo 'selected="selected"'; } ?>>Sim</option>
						<option value="2" <?php if ($kaizen['outrosdep_kaizen'] <> 1){ echo 'selected="selected"'; } ?>>Não</option>
					</select>
				</div>
				
				<div id="qual-container" <?php if ($kaizen['outrosdep_kaizen'] != 1){ echo 'style="display: none;"'; } ?>>
					<label for="qual" class="form-label">Quais departamentos?</label>
					<input name="qual_kaizen" type="text" id="qual" class="form-control" 
						   placeholder="Especifique os departamentos..." value="<?= $kaizen['qual_kaizen'] ?>">
				</div>
			</div>
			
			<!-- Colaboradores -->
			<div class="form-section">
				<h5><i class="fas fa-users"></i> Colaboradores Participantes</h5>
				<p class="text-muted">Selecione até 2 colaboradores que participaram deste Kaizen:</p>
				
				<div class="mb-3">
					<label for="colaborador1" class="form-label">Colaborador 1:</label>
					<select name="colaborador1" id="colaborador1" class="form-select">
						<option value="">Selecione</option>
						<?php
						$sql = sql("SELECT * FROM colaborador WHERE id_colaborador != ".trata($_SESSION['usu'])." AND status_colaborador <> 2 ORDER BY nome_colaborador ASC", $con);	
						while ($dados = mysqli_fetch_assoc($sql)){
						?>
						<option <?php if ($dados['id_colaborador'] == $kaizen['colaborador1_kaizen']){ echo 'selected="selected"'; } ?> 
								value="<?= $dados['id_colaborador'] ?>">
							<?= $dados['nome_colaborador'] ?>
						</option>
						<?php } ?>
					</select>
				</div>
				
				<div class="mb-3">
					<label for="colaborador2" class="form-label">Colaborador 2:</label>
					<select name="colaborador2" id="colaborador2" class="form-select">
						<option value="">Selecione</option>
						<?php
						$sql = sql("SELECT * FROM colaborador WHERE id_colaborador != ".trata($_SESSION['usu'])." AND status_colaborador <> 2 ORDER BY nome_colaborador ASC", $con);	
						while ($dados = mysqli_fetch_assoc($sql)){
						?>
						<option <?php if ($dados['id_colaborador'] == $kaizen['colaborador2_kaizen']){ echo 'selected="selected"'; } ?> 
								value="<?= $dados['id_colaborador'] ?>">
							<?= $dados['nome_colaborador'] ?>
						</option>
						<?php } ?>
					</select>
				</div>
			</div>
			
			<!-- Botões -->
			<div class="text-center mb-4">
				<button type="submit" name="enviar" class="btn btn-primary btn-lg px-5">
					<i class="fas fa-save"></i> Salvar Alterações
				</button>
				<a href="minhas-sugestoes.php" class="btn btn-outline-secondary btn-lg px-5 ms-2">
					<i class="fas fa-times"></i> Cancelar
				</a>
			</div>
		</form>
		
		<!-- InstanceEndEditable -->
	</main>

	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<script>
	$(document).ready(function(){
		// Máscaras
		$('.money').maskMoney({
			decimal: ',',
			thousands: '.',
			allowZero: true
		});
		
		$('.numero').inputmask({
			alias: 'decimal',
			digits: 2,
			digitsOptional: true,
			radixPoint: ',',
			groupSeparator: '.',
			autoGroup: true,
			rightAlign: false
		});
		
		// Lógica de tipo/complemento v2
		$('#tipo').on('change', function(){
			var complemento = $(this).find(':selected').data('complemento');
			var add = $(this).find(':selected').data('add');
			
			// Ocultar todos os campos de complemento
			$('.campo-complemento').hide().find('input').prop('required', false);
			
			// Mostrar campo específico se houver complemento
			if (complemento && complemento > 0) {
				$('.campo-comp-' + complemento).show().find('input').prop('required', true);
				$('#complemento_ativo').val(complemento);
			} else {
				$('#complemento_ativo').val(0);
			}
			
			// Mostrar/ocultar campo adicional (Outros)
			if (add == 1) {
				$('#campo-add-tipo').show().find('input').prop('required', true);
			} else {
				$('#campo-add-tipo').hide().find('input').prop('required', false);
			}
		}).trigger('change');
		
		// Mostrar/ocultar campo "Quais departamentos"
		$('#outrosdep_kaizen').on('change', function(){
			if ($(this).val() == '1') {
				$('#qual-container').slideDown();
			} else {
				$('#qual-container').slideUp();
				$('#qual').val('');
			}
		});
		
		// Upload de arquivo - mostrar nome e botão remover
		$('input[type="file"]').on('change', function(){
			var targetId = $(this).attr('id');
			var fileName = this.files[0] ? this.files[0].name : '';
			
			if (fileName) {
				$(this).next('.file-upload-label').find('span').text(fileName);
				$('.limpa-input[data-target="' + targetId + '"]').show();
			}
		});
		
		// Remover arquivo selecionado
		$('.limpa-input').on('click', function(){
			var target = $(this).data('target');
			$('#' + target).val('');
			$('label[for="' + target + '"]').find('span').text('Nova imagem (' + (target === 'imagem1' ? 'antes' : 'depois') + ')');
			$(this).hide();
		});
		
	
	// Contador de novos anexos
	$('#novos_anexos').on('change', function(){
		var fileCount = this.files.length;
		if (fileCount > 0) {
			$('#file-count-novos').html('<span class="badge bg-success">' + fileCount + ' arquivo(s) selecionado(s)</span>');
		} else {
			$('#file-count-novos').html('');
		}
	});
	
	// Drag and Drop para novos anexos
	const dropAreaEdit = $('.file-upload-area');
	const fileInputEdit = $('#novos_anexos')[0];
	
	// Prevenir comportamento padrão
	['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
		dropAreaEdit.on(eventName, function(e) {
			e.preventDefault();
			e.stopPropagation();
		});
	});
	
	// Highlight ao arrastar
	['dragenter', 'dragover'].forEach(eventName => {
		dropAreaEdit.on(eventName, function() {
			$(this).addClass('drag-over');
		});
	});
	
	['dragleave', 'drop'].forEach(eventName => {
		dropAreaEdit.on(eventName, function() {
			$(this).removeClass('drag-over');
		});
	});
	
	// Processar arquivos soltos
	dropAreaEdit.on('drop', function(e) {
		const dt = e.originalEvent.dataTransfer;
		const files = dt.files;
		
		// Atribuir arquivos ao input
		fileInputEdit.files = files;
		
		// Disparar evento change
		$('#novos_anexos').trigger('change');
	});
	
	// Excluir anexo
	var anexosParaExcluir = [];
			$('.btn-excluir-anexo').on('click', function(){
			var anexoId = $(this).data('anexo-id');
			var anexoNome = $(this).data('anexo-nome');
			
			if (confirm('Deseja realmente excluir o anexo "' + anexoNome + '"?\nEsta ação não pode ser desfeita.')) {
				// Adicionar à lista de exclusão
				anexosParaExcluir.push(anexoId);
				$('#anexos_excluir').val(anexosParaExcluir.join(','));
				
				// Marcar visualmente como deletado
				$('#anexo-' + anexoId).addClass('deletado').find('.btn-excluir-anexo').prop('disabled', true).html('<i class="fas fa-check"></i>');
				$('#anexo-' + anexoId).find('.btn-outline-primary').addClass('disabled');
			}
		});
		
		// Validação do formulário
		$('#formKaizen').on('submit', function(e){
			var tipo = $('#tipo').val();
			if (!tipo) {
				e.preventDefault();
				alert('Por favor, selecione o tipo de benefício.');
				return false;
			}
			
			var situacao = $('#situacao_atual').val().trim();
			var onde = $('#onde').val().trim();
			var resultado = $('#resultado').val().trim();
			
			if (!situacao || !onde || !resultado) {
				e.preventDefault();
				alert('Por favor, preencha todos os campos obrigatórios de descrição.');
				return false;
			}
			
			return true;
		});
	});
	</script>
</body>
<!-- InstanceEnd --></html>
