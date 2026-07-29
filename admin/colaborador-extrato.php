<?php
$sufixo = "colaborador";

include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM colaborador ORDER BY id_colaborador ASC", $con);

$id_colaborador = trata($_GET['id_colaborador']);

if (!empty($id_colaborador) && is_numeric($id_colaborador)) {
    $sql = mysqli_query($con, "SELECT * FROM colaborador WHERE id_colaborador = " . $id_colaborador . " LIMIT 1") or die(mysqli_error($con));

    if (mysqli_num_rows($sql) > 0) {
        $colaborador = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Registro não encontrado!", "colaborador-admin.php");
    }
} else {
    volta("erro", "Registro não encontrado!", "colaborador-admin.php");
}

$sql = sql("SELECT * FROM log WHERE usu_log = " . $colaborador['id_colaborador'] . " ORDER BY data_log DESC", $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Extrato de Pontos - <?php echo $colaborador['nome_colaborador']; ?> - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css">
<style>
.extrato-header {
    background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
    color: white;
    padding: 30px;
    border-radius: 12px;
    margin-bottom: 30px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.extrato-header h1 {
    margin: 0;
    font-size: 2rem;
    font-weight: 600;
}

.extrato-header .colaborador-info {
    display: flex;
    gap: 30px;
    margin-top: 15px;
    flex-wrap: wrap;
}

.extrato-header .info-item {
    display: flex;
    align-items: center;
    gap: 10px;
}

.extrato-header .info-item i {
    font-size: 1.2rem;
    opacity: 0.9;
}

.pontos-positivo {
    color: #00812e;
    font-weight: 600;
}

.pontos-negativo {
    color: #dc3545;
    font-weight: 600;
}

.badge-pontos {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #333;
    padding: 6px 12px;
    border-radius: 20px;
    font-weight: 600;
}

.dataTables_wrapper {
    padding: 25px;
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
}
</style>
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
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-file-invoice"></i> Extrato de Pontos</h1>
                <p class="text-muted">Histórico completo de movimentações</p>
            </div>
            <div>
                <a href="colaborador-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="extrato-header">
            <h1><i class="fas fa-user-circle"></i> <?php echo $colaborador['nome_colaborador']; ?></h1>
            <div class="colaborador-info">
                <div class="info-item">
                    <i class="fas fa-star"></i>
                    <span>Saldo Atual: <strong class="badge-pontos"><?php echo number_format($colaborador['ponto_colaborador'], 0, ',', '.'); ?> pontos</strong></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo $colaborador['email_colaborador']; ?></span>
                </div>
            </div>
        </div>

        <div class="content-card">
            <table class="table table-hover" id="extrato-table">
                <thead>
                    <tr>
                        <th width="15%">Data</th>
                        <th>Descrição</th>
                        <th width="12%" class="text-center">Pontos</th>
                        <th width="12%" class="text-end">Saldo</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($dados = mysqli_fetch_assoc($sql)) {
                    ?>
                    <tr>
                        <td>
                            <i class="fas fa-calendar-alt text-muted me-2"></i>
                            <?php echo date("d/m/Y H:i:s", strtotime($dados['data_log'])); ?>
                        </td>
                        <td><?php echo $dados['descricao_log']; ?></td>
                        <td class="text-center">
                            <?php if ($dados['ponto_log'] > 0) { ?>
                                <span class="pontos-positivo">
                                    <i class="fas fa-arrow-up"></i> +<?php echo number_format($dados['ponto_log'], 0, ',', '.'); ?>
                                </span>
                            <?php } else { ?>
                                <span class="pontos-negativo">
                                    <i class="fas fa-arrow-down"></i> <?php echo number_format($dados['ponto_log'], 0, ',', '.'); ?>
                                </span>
                            <?php } ?>
                        </td>
                        <td class="text-end">
                            <strong><?php echo number_format($dados['saldo_log'], 0, ',', '.'); ?></strong>
                        </td>
                    </tr>
                    <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script>
$(document).ready(function() {
    $('#extrato-table').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[0, 'desc']],
        pageLength: 25,
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>rt<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>><"row"<"col-sm-12"B>>',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-info btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3]
                }
            }
        ]
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
<!-- InstanceEnd -->
