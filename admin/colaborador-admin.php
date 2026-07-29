<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM colaborador
            WHERE status_colaborador <> 2 
            ORDER BY id_colaborador ASC", $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Gerenciar Colaboradores - Sistema Orion</title>
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
                <h1><i class="fas fa-users"></i> Gerenciar Colaboradores</h1>
                <p class="text-muted">Gerencie os colaboradores do sistema</p>
            </div>
            <div>
                <a href="colaborador-inserir.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Novo Colaborador
                </a>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-list"></i> Lista de Colaboradores</h5>
            </div>
            <div class="admin-card-body">
                <table class="table table-orion table-hover" id="tableColaboradores">
                    <thead>
                        <tr>
                            <th width="5%">ID</th>
                            <th width="25%">Nome</th>
                            <th width="20%">Email</th>
                            <th width="12%">Gestor Responsável</th>
                            <th width="10%">Pontos</th>
                            <th width="8%">Perfil</th>
                            <th width="20%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dados = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td><strong>#<?php echo $dados['id_colaborador']; ?></strong></td>
                            <td>
                                <?php echo $dados['nome_colaborador']; ?>
                                <?php if ($dados['lider_colaborador'] == 1) { ?>
                                <span class="badge bg-success ms-2">
                                    <i class="fas fa-star"></i> Gestor Kaizen
                                </span>
                                <?php } ?>
                            </td>
                            <td><?php echo !empty($dados['email_colaborador']) ? $dados['email_colaborador'] : '<span class="text-muted">-</span>'; ?></td>
                            <td>
                                <?php 
                                if ($dados['gestor_colaborador']) {
                                    $sqlGestor = sql("SELECT * FROM colaborador WHERE id_colaborador = '".$dados['gestor_colaborador']."' AND lider_colaborador = '1' LIMIT 1", $con);
                                    if (mysqli_num_rows($sqlGestor)) {
                                        $gestor = mysqli_fetch_array($sqlGestor);
                                        echo '<small>'.$gestor['nome_colaborador'].'</small>';
                                    } else {
                                        echo '<span class="text-muted">-</span>';
                                    }
                                } else {
                                    echo '<span class="text-muted">-</span>';
                                }
                                ?>
                            </td>
                            <td>
                                <strong class="text-warning">
                                    <i class="fas fa-star"></i> <?php echo number_format($dados['ponto_colaborador'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <?php if ($dados['lider_colaborador'] == 1) { ?>
                                <span class="badge bg-success">Gestor</span>
                                <?php } else { ?>
                                <span class="badge bg-primary">Colaborador</span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="colaborador-extrato.php?id_colaborador=<?php echo $dados['id_colaborador']; ?>" 
                                       class="btn btn-sm btn-info" title="Ver Extrato">
                                        <i class="fas fa-list-alt"></i>
                                    </a>
                                    <a href="colaborador-editar.php?id_colaborador=<?php echo $dados['id_colaborador']; ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="app/func_colaborador_apagar.php?id_colaborador=<?php echo $dados['id_colaborador']; ?>" 
                                       class="btn btn-sm btn-danger btconfirm" title="Excluir">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
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
    $('#tableColaboradores').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[1, 'asc']],
        pageLength: 25
    });

    // Confirmação de exclusão
    $('.btconfirm').click(function(e) {
        if (!confirm('Tem certeza que deseja excluir este colaborador? Esta ação não pode ser desfeita.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
