<?php
$sufixo = "catproduto";

include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Nova Categoria de Produto - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
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
            <div class="page-header-content">
                <div>
                    <h1 class="page-title">
                        <i class="fas fa-plus-circle text-success"></i> Nova Categoria de Produto
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
                            <li class="breadcrumb-item"><a href="catproduto-admin.php">Categorias de Produtos</a></li>
                            <li class="breadcrumb-item active">Nova Categoria</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-form"></i> Formulário de Cadastro</h5>
            </div>
            <div class="admin-card-body">
                <form action="app/func_catproduto_inserir.php" method="post" id="formulario">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="nome_catproduto" class="form-label">
                                    Nome da Categoria <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome_catproduto" 
                                       name="nome_catproduto" 
                                       maxlength="100" 
                                       required>
                                <div class="form-text">Nome que identificará a categoria no sistema</div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="numero_catproduto" class="form-label">
                                    Ordem de Exibição <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="numero_catproduto" 
                                       name="numero_catproduto" 
                                       min="1" 
                                       value="1"
                                       required>
                                <div class="form-text">Ordem que aparece no catálogo</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="catproduto-admin.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success" id="btsubmit">
                            <i class="fas fa-check"></i> Cadastrar Categoria
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    $('#formulario').on('submit', function() {
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cadastrando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
