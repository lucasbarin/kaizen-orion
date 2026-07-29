<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM logtroca 
            LEFT JOIN colaborador ON colaborador.id_colaborador = logtroca.id_colaborador
            LEFT JOIN produto ON produto.id_produto = logtroca.id_produto
            ORDER BY 
            CASE 
                WHEN logtroca.status_logtroca = 1 THEN 1
                WHEN logtroca.status_logtroca = 2 THEN 2
                WHEN logtroca.status_logtroca = 3 THEN 3
            END,
            logtroca.data_logtroca DESC", $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Gerenciar Trocas - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>
<body>
<div id="preloader">
    <div class="loader"></div>
</div>

<div class="admin-wrapper">
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
    <aside class="admin-sidebar" id="sidebar">
        <?php include("menu.php"); ?>
    </aside>

    <!-- Main Content -->
    <main class="admin-content">
        <!-- InstanceBeginEditable name="content" -->
        <?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-exchange-alt"></i> Gerenciar Trocas</h1>
                <p class="text-muted">Processar resgates de produtos pelos colaboradores</p>
            </div>
        </div>

        <!-- Cards de Estatísticas -->
        <?php
        $sqlStats = sql("SELECT 
                        SUM(CASE WHEN status_logtroca = 1 THEN 1 ELSE 0 END) as pendentes,
                        SUM(CASE WHEN status_logtroca = 2 THEN 1 ELSE 0 END) as finalizadas,
                        SUM(CASE WHEN status_logtroca = 3 THEN 1 ELSE 0 END) as canceladas,
                        SUM(CASE WHEN status_logtroca = 1 THEN ponto_logtroca ELSE 0 END) as pontos_pendentes
                        FROM logtroca", $con);
        $stats = mysqli_fetch_array($sqlStats);
        ?>
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon text-warning">
                        <i class="fas fa-clock"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['pendentes']; ?></div>
                    <div class="stat-label">Aguardando</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon text-success">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['finalizadas']; ?></div>
                    <div class="stat-label">Finalizadas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon text-danger">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <div class="stat-value"><?php echo $stats['canceladas']; ?></div>
                    <div class="stat-label">Canceladas</div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <div class="stat-icon text-info">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="stat-value"><?php echo number_format($stats['pontos_pendentes'], 0, ',', '.'); ?></div>
                    <div class="stat-label">Pontos Pendentes</div>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-list"></i> Lista de Trocas</h5>
            </div>
            <div class="admin-card-body">
                <table class="table table-orion table-hover" id="tableTrocas">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="20%">Colaborador</th>
                            <th width="15%">Data Solicitação</th>
                            <th width="25%">Produto</th>
                            <th width="10%">Pontos</th>
                            <th width="10%">Status</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dados = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td><strong>#<?php echo $dados['id_logtroca']; ?></strong></td>
                            <td><?php echo $dados['nome_colaborador']; ?></td>
                            <td>
                                <span class="d-none"><?php echo date("YmdHis", strtotime($dados['data_logtroca'])); ?></span>
                                <?php echo date("d/m/Y H:i", strtotime($dados['data_logtroca'])); ?>
                            </td>
                            <td>
                                <strong><?php echo $dados['nome_logtroca']; ?></strong>
                                <?php if (!empty($dados['observacao_logtroca'])) { ?>
                                <br><small class="text-muted"><?php echo $dados['observacao_logtroca']; ?></small>
                                <?php } ?>
                            </td>
                            <td>
                                <strong class="text-warning">
                                    <i class="fas fa-star"></i> <?php echo number_format($dados['ponto_logtroca'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <?php if ($dados['status_logtroca'] == 2) { ?>
                                <span class="badge bg-success">
                                    <i class="fas fa-check"></i> Finalizado
                                </span>
                                <?php } elseif ($dados['status_logtroca'] == 3) { ?>
                                <span class="badge bg-danger">
                                    <i class="fas fa-times"></i> Cancelado
                                </span>
                                <?php } else { ?>
                                <span class="badge bg-warning">
                                    <i class="fas fa-clock"></i> Aguardando
                                </span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($dados['status_logtroca'] != 2 && $dados['status_logtroca'] != 3) { ?>
                                <div class="btn-group" role="group">
                                    <a href="app/func_troca.php?id_logtroca=<?php echo $dados['id_logtroca']; ?>&acao=2" 
                                       class="btn btn-sm btn-success btconfirm-finalizar" title="Finalizar">
                                        <i class="fas fa-check"></i>
                                    </a>
                                    <a href="app/func_troca.php?id_logtroca=<?php echo $dados['id_logtroca']; ?>&acao=3" 
                                       class="btn btn-sm btn-danger btconfirm-devolver" title="Devolver Pontos">
                                        <i class="fas fa-undo"></i>
                                    </a>
                                </div>
                                <?php } else { ?>
                                <span class="text-muted">-</span>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // DataTable
    $('#tableTrocas').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[2, 'desc']], // Ordenar por data
        pageLength: 25
    });

    // Confirmação de finalização
    $('.btconfirm-finalizar').click(function(e) {
        if (!confirm('Confirmar FINALIZAÇÃO desta troca?\n\nO produto será marcado como entregue ao colaborador.')) {
            e.preventDefault();
            return false;
        }
    });

    // Confirmação de devolução
    $('.btconfirm-devolver').click(function(e) {
        if (!confirm('Confirmar CANCELAMENTO e DEVOLUÇÃO dos pontos?\n\nOs pontos serão devolvidos ao saldo do colaborador.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
