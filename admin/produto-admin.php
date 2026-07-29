<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM produto 
            LEFT JOIN catproduto ON catproduto.id_catproduto = produto.catproduto_produto
            WHERE status_produto != 2 
            ORDER BY nome_catproduto ASC, numero_produto ASC", $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Gerenciar Produtos - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<style>
.product-thumb {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 8px;
    border: 2px solid #e0e0e0;
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
        <?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-gift"></i> Gerenciar Produtos</h1>
                <p class="text-muted">Catálogo de produtos para resgate de pontos</p>
            </div>
            <div>
                <a href="catproduto-admin.php" class="btn btn-info me-2">
                    <i class="fas fa-tags"></i> Categorias
                </a>
                <a href="produto-inserir.php" class="btn btn-success">
                    <i class="fas fa-plus"></i> Novo Produto
                </a>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-list"></i> Lista de Produtos</h5>
            </div>
            <div class="admin-card-body">
                <table class="table table-orion table-hover" id="tableProdutos">
                    <thead>
                        <tr>
                            <th width="8%">Imagem</th>
                            <th width="25%">Nome</th>
                            <th width="15%">Categoria</th>
                            <th width="8%">Ordem</th>
                            <th width="10%">Pontos</th>
                            <th width="12%">Estoque</th>
                            <th width="10%">Status</th>
                            <th width="12%">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($dados = mysqli_fetch_assoc($sql)) { ?>
                        <tr>
                            <td>
                                <?php if (!empty($dados['imagem_produto'])) { ?>
                                <img src="../imgs/produto/<?php echo $dados['imagem_produto']; ?>" 
                                     alt="<?php echo $dados['nome_produto']; ?>" class="product-thumb">
                                <?php } else { ?>
                                <div class="product-thumb bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-image text-white"></i>
                                </div>
                                <?php } ?>
                            </td>
                            <td>
                                <strong><?php echo $dados['nome_produto']; ?></strong>
                                <?php if (!empty($dados['site_produto'])) { ?>
                                <br>
                                <a href="<?php echo $dados['site_produto']; ?>" target="_blank" class="text-primary text-decoration-none">
                                    <small><i class="fas fa-external-link-alt"></i> Ver na loja</small>
                                </a>
                                <?php } ?>
                            </td>
                            <td><?php echo $dados['nome_catproduto']; ?></td>
                            <td>
                                <span class="badge bg-secondary">
                                    <?php echo str_pad($dados['numero_produto'], 2, "0", STR_PAD_LEFT); ?>º
                                </span>
                            </td>
                            <td>
                                <strong class="text-warning">
                                    <i class="fas fa-star"></i> <?php echo number_format($dados['pontos_produto'], 0, ',', '.'); ?>
                                </strong>
                            </td>
                            <td>
                                <?php if (isset($dados['estoque_produto'])) { ?>
                                    <?php if ($dados['estoque_produto'] > 0) { ?>
                                    <span class="badge bg-success"><?php echo $dados['estoque_produto']; ?> unidades</span>
                                    <?php } else { ?>
                                    <span class="badge bg-danger">Esgotado</span>
                                    <?php } ?>
                                <?php } else { ?>
                                <span class="text-muted">-</span>
                                <?php } ?>
                            </td>
                            <td>
                                <?php if ($dados['status_produto'] == 1) { ?>
                                <span class="badge bg-success">Ativo</span>
                                <?php } else { ?>
                                <span class="badge bg-secondary">Inativo</span>
                                <?php } ?>
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="produto-editar.php?id_produto=<?php echo $dados['id_produto']; ?>" 
                                       class="btn btn-sm btn-warning" title="Editar">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="app/func_produto_apagar.php?id_produto=<?php echo $dados['id_produto']; ?>" 
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
    $('#tableProdutos').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[2, 'asc'], [3, 'asc']], // Categoria + Ordem
        pageLength: 25
    });

    // Confirmação de exclusão
    $('.btconfirm').click(function(e) {
        if (!confirm('Tem certeza que deseja excluir este produto? Esta ação não pode ser desfeita.')) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
