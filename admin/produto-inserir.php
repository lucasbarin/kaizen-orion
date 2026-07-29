<?php
$sufixo = "produto";

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
<title>Novo Produto - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet" />
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
                        <i class="fas fa-plus-circle text-success"></i> Novo Produto
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
                            <li class="breadcrumb-item"><a href="produto-admin.php">Produtos</a></li>
                            <li class="breadcrumb-item active">Novo Produto</li>
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
                <form action="app/func_produto_inserir.php" method="post" enctype="multipart/form-data" id="formulario">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="nome_produto" class="form-label">
                                    Nome do Produto <span class="text-danger">*</span>
                                </label>
                                <input type="text" 
                                       class="form-control" 
                                       id="nome_produto" 
                                       name="nome_produto" 
                                       maxlength="100" 
                                       required>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="catproduto_produto" class="form-label">
                                    Categoria <span class="text-danger">*</span>
                                </label>
                                <select class="form-select select2" name="catproduto_produto" id="catproduto_produto" required>
                                    <option value="">Selecione</option>
                                    <?php
                                    $sqloption = sql("SELECT * FROM catproduto ORDER BY nome_catproduto", $con);
                                    while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                        echo '<option value="'.$dadosopt['id_catproduto'].'">'.$dadosopt['nome_catproduto'].'</option>';
                                    }
                                    ?>
                                </select>
                            </div>
                        </div>

                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="numero_produto" class="form-label">
                                    Ordem <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="numero_produto" 
                                       name="numero_produto" 
                                       min="1"
                                       value="1" 
                                       required>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="pontos_produto" class="form-label">
                                    Pontos (Custo) <span class="text-danger">*</span>
                                </label>
                                <input type="number" 
                                       class="form-control" 
                                       id="pontos_produto" 
                                       name="pontos_produto" 
                                       min="0" 
                                       required>
                            </div>
                        </div>

                        <div class="col-md-9">
                            <div class="mb-3">
                                <label for="texto1_produto" class="form-label">Descrição</label>
                                <textarea class="form-control" 
                                          id="texto1_produto" 
                                          name="texto1_produto" 
                                          rows="2" 
                                          maxlength="130"></textarea>
                                <div class="form-text">Máximo 130 caracteres</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="site_produto" class="form-label">URL da Loja (Link Externo)</label>
                                <input type="url" 
                                       class="form-control" 
                                       id="site_produto" 
                                       name="site_produto" 
                                       maxlength="300"
                                       placeholder="https://">
                                <div class="form-text">Link para compra do produto (visível apenas para administradores)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="mb-3">
                                <label for="imagem1_produto" class="form-label">
                                    Imagem Ilustrativa <span class="text-danger">*</span>
                                </label>
                                <input type="file" 
                                       class="form-control" 
                                       id="imagem1_produto" 
                                       name="imagem1_produto" 
                                       accept="image/jpeg,image/jpg"
                                       required>
                                <div class="form-text">Imagens JPG com máximo 1000px de largura</div>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="produto-admin.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-success" id="btsubmit">
                            <i class="fas fa-check"></i> Cadastrar Produto
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
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%'
    });

    // Loading no submit
    $('#formulario').on('submit', function() {
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cadastrando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
