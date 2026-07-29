<?php
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$link_menu = 2;

$sql = sql("SELECT kaizen.*, tipo.nome_tipo, comp.nome_comp, colaborador.nome_colaborador FROM kaizen
LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
ORDER BY datacadastro_kaizen DESC", $con);

$sqlAlert = sql("SELECT * FROM pg WHERE id_pg = 101 LIMIT 1", $con);
$alert = mysqli_fetch_array($sqlAlert);	
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Gerenciar Kaizens | Painel Administrativo Orion</title>
	
	<meta name="description" content="Painel Administrativo - Sistema Kaizen">
	<link rel="shortcut icon" href="favicon.ico"/>
	
	<!-- Bootstrap 5.3.2 -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
	
	<!-- Google Fonts -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
	
	<!-- DataTables Bootstrap 5 -->
	<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css">
	<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
	
	<!-- Admin Custom CSS -->
	<link rel="stylesheet" href="../lib/css/admin-orion.css">
	<style>
		.label-success {
			background: #00812e;
			color: white;
			padding: 0.25rem 0.5rem;
			border-radius: 0.25rem;
			font-size: 0.85rem;
		}
		
		.label-danger {
			background: #dc3545;
			color: white;
			padding: 0.25rem 0.5rem;
			border-radius: 0.25rem;
			font-size: 0.85rem;
		}
		
		.action-buttons {
			display: flex;
			flex-wrap: wrap;
			gap: 0.25rem;
		}
		
		.action-buttons .btn {
			padding: 0.25rem 0.5rem;
			font-size: 0.85rem;
		}
		
		/* Responsivo para tabela */
		@media (max-width: 992px) {
			.table-responsive {
				font-size: 0.85rem;
			}
			
			.action-buttons {
				flex-direction: column;
			}
		}
	</style>
</head>

<body>
	<!-- Loading Overlay -->
	<div id="preloader" style="display: none;">
		<div id="status"></div>
	</div>
	
	<!-- Header -->
	<header class="admin-header">
		<button class="sidebar-toggle" id="sidebarToggle">
			<i class="fas fa-bars"></i>
		</button>
		
		<a href="../" class="logo">
			<img src="assets/admin/layout/img/logo.png" alt="Orion Kaizen" onerror="this.style.display='none'">
			<span>ORION KAIZEN</span>
		</a>
		
		<div class="header-right">
			<a href="app/logout.php" class="btn-logout">
				<i class="fas fa-sign-out-alt"></i>
				<span>Sair</span>
			</a>
		</div>
	</header>
	
	<!-- Sidebar -->
	<aside class="admin-sidebar" id="adminSidebar">
		<?php include("menu.php"); ?>
	</aside>
	
	<!-- Main Content -->
	<main class="admin-content">
		<!-- Page Header -->
		<div class="page-header">
			<h1><i class="fas fa-lightbulb"></i> Kaizens</h1>
			<p class="subtitle">Administrar todos os Kaizens do sistema</p>
		</div>
		
		<?php resposta($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
		
		<!-- Tabela de Kaizens -->
		<div class="admin-card">
			<div class="admin-card-header">
				<i class="fas fa-table"></i> Lista Completa de Kaizens
			</div>
			<div class="admin-card-body">
				<div class="table-responsive">
					<table class="table table-striped table-hover" id="tabelaKaizens">
						<thead>
							<tr>
								<th width="5%">#</th>
								<th width="12%">Tipo</th>
								<th width="12%">Status</th>
								<th width="10%">Data</th>
								<th width="10%">Benefício R$</th>
								<th width="8%">Alta Redução?<br><small>(acima R$ <?= money_print($alert['nome3_pg']) ?>)</small></th>
								<th width="15%">Colaborador</th>
								<th width="18%">Ações</th>
							</tr>
						</thead>
						<tbody>
							<?php while ($dados = mysqli_fetch_assoc($sql)): ?>
							<tr>
								<td><strong><?= $dados['id_kaizen'] ?></strong></td>
								<td>
									<?= $dados['nome_tipo'] ?>
									<?php if (!empty($dados['nome_comp'])): ?>
										<br><small class="text-muted"><?= $dados['nome_comp'] ?></small>
									<?php endif; ?>
								</td>
								<td>
									<?php 
									$status = $dados['status_kaizen'];
									include("../_status.php"); 
									?>
								</td>
								<td>
									<?php data_print($dados['datacadastro_kaizen']); ?>
									<?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00'): ?>
										<br><small class="text-muted">Concluído em: <?php data_print($dados['dataconclusao_kaizen']); ?></small>
									<?php endif; ?>
								</td>
								<td>
									<?php 
									// Exibir benefício v2.1 (entrada direta)
									if (!empty($dados['valor_original_kaizen']) && $dados['valor_original_kaizen'] > 0 && !empty($dados['tipo_complemento_kaizen'])){ 
										echo '<strong>' . formatarValorComplemento($dados['valor_original_kaizen'], $dados['tipo_complemento_kaizen']) . '</strong>';
									// Fallback v1: custo_kaizen
									} elseif ($dados['custo_kaizen']){ 
										echo 'R$ ' . number_format($dados['custo_kaizen'], 2, ',', '.');
									} else { 
										echo "-"; 
									} 
									?>
								</td>
								<td class="text-center">
									<?php 
									// Verificar se é alta redução (apenas para valores em R$)
									$valor_beneficio = 0;
									if(!empty($dados['valor_original_kaizen']) && in_array($dados['tipo_complemento_kaizen'], [1, 2])){
										$valor_beneficio = $dados['valor_original_kaizen'];
									} elseif($dados['custo_kaizen'] > 0){
										$valor_beneficio = $dados['custo_kaizen'];
									}
									
									if ($valor_beneficio >= $alert['nome3_pg']): ?> 
										<span class="label-success">SIM</span>
									<?php else: ?> 
										<span class="label-danger">NÃO</span> 
									<?php endif; ?>
								</td>
								<td><?= $dados['nome_colaborador'] ?></td>
								<td>
									<div class="action-buttons">
										<a href="kaizen-editar.php?kaizen=<?= $dados['id_kaizen'] ?>" 
										   class="btn btn-warning btn-sm">
											<i class="fas fa-eye"></i> Analisar
										</a>
										<a href="kaizen-indicar.php?kaizen=<?= $dados['id_kaizen'] ?>" 
										   class="btn btn-primary btn-sm">
											<i class="fas fa-edit"></i> Completar
										</a>
										<a href="app/func_kaizen_apagar.php?kaizen=<?= $dados['id_kaizen'] ?>" 
										   class="btn btn-danger btn-sm btconfirm">
											<i class="fas fa-trash"></i> Excluir
										</a>
									</div>
								</td>
							</tr>
							<?php endwhile; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	</main>
	
	<!-- Footer -->
	<footer class="admin-footer">
		<p>&copy; <?= date('Y') ?> Sistema Orion Kaizen - Desenvolvido por catenacom.com</p>
	</footer>
	
	<!-- jQuery (necessário para compatibilidade) -->
	<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
	
	<!-- DataTables -->
	<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
	<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
	<script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
	
	<!-- DataTables Buttons (Export) -->
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
	<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
	<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
	
	<!-- Admin Base Scripts -->
	<script src="../lib/js/admin-base.js"></script>
	
	<script>
		$(document).ready(function() {
			// Initialize DataTable com exportação
			$('#tabelaKaizens').DataTable({
				responsive: true,
				language: {
					url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
				},
				pageLength: 25,
				order: [[0, 'desc']],
				dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
					 '<"row"<"col-sm-12"B>>' +
					 '<"row"<"col-sm-12"tr>>' +
					 '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
				buttons: [
					{
						extend: 'excelHtml5',
						text: '<i class="fas fa-file-excel"></i> Excel',
						className: 'btn btn-success btn-sm',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6] // Excluir coluna de ações
						}
					},
					{
						extend: 'pdfHtml5',
						text: '<i class="fas fa-file-pdf"></i> PDF',
						className: 'btn btn-danger btn-sm',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					},
					{
						extend: 'print',
						text: '<i class="fas fa-print"></i> Imprimir',
						className: 'btn btn-info btn-sm',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						}
					}
				]
			});
			
			// Confirmação de exclusão
			$(document).on('click', '.btconfirm', function(e) {
				if (!confirm('Tem certeza que deseja EXCLUIR este Kaizen? Esta ação não pode ser desfeita!')) {
					e.preventDefault();
					return false;
				}
			});
		});
	</script>
</body>
</html>
