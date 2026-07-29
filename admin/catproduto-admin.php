<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM catproduto ORDER BY numero_catproduto ASC", $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Gerenciar Categorias de Produtos - Sistema Orion</title>
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

    <aside class="admin-sidebar" id="sidebar">
        <?php include("menu.php"); ?>
    </aside>

    <main class="admin-content">
        <!-- InstanceBeginEditable name="content" -->
        <?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-tags"></i> Gerenciar Categorias de Produtos</h1>
                <p class="text-muted">Organize os produtos em categorias</p>
            </div>
            <div>
                <a href="produto-admin.php" class="btn btn-info me-2">
                    <i class="fas fa-gift"></i> Ver Produtos
                </a>
                <a href="catproduto-inserir.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Nova Categoria
                </a>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-list"></i> Lista de Categorias</h5>
            </div>
            <div class="admin-card-body">
                <table class="table table-orion table-hover" id="tableCategorias">
                    <thead>
                        <tr>
                            <th width="10%">ID</th>
                            <th width="60%">Nome</th>
                            <th width="15%">Ordem</th>
                            <th width="15%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dados = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td><strong>#<?php echo $dados['id_catproduto']; ?></strong></td>
                            <td><?php echo $dados['nome_catproduto']; ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo str_pad($dados['numero_catproduto'], 2, "0", STR_PAD_LEFT); ?>º
                                </span>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="catproduto-editar.php?id_catproduto=<?php echo $dados['id_catproduto']; ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="app/func_catproduto_apagar.php?id_catproduto=<?php echo $dados['id_catproduto']; ?>" 
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
    $('#tableCategorias').DataTable({
        language: { url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json' },
        order: [[2, 'asc']]
    });
    $('.btconfirm').click(function(e) {
        if (!confirm('Tem certeza que deseja excluir esta categoria?')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
