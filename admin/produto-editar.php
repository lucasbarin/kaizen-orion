<?php
$sufixo = "produto";

include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM produto ORDER BY id_produto ASC", $con);

$id_produto = trata($_GET['id_produto']);

if (!empty($id_produto) && is_numeric($id_produto)) {
    $sql = mysqli_query($con, "SELECT * FROM produto WHERE id_produto = ".$id_produto." LIMIT 1") or die(mysqli_error($con));
    
    if(mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Registro não encontrado!", "produto-admin.php");
    }
} else {
    volta("erro", "Registro não encontrado!", "produto-admin.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Editar Produto - Sistema Orion</title>
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
                        <i class="fas fa-edit text-primary"></i> Editar Produto
                    </h1>
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="home.php"><i class="fas fa-home"></i> Início</a></li>
                            <li class="breadcrumb-item"><a href="produto-admin.php">Produtos</a></li>
                            <li class="breadcrumb-item active">Editar</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>

        <div class="admin-card">
            <div class="admin-card-header">
                <h5><i class="fas fa-form"></i> Formulário de Edição</h5>
            </div>
            <div class="admin-card-body">
                <form action="app/func_produto_editar.php" method="post" enctype="multipart/form-data" id="formulario">
                    <input name="id_produto" type="hidden" value="<?php echo $dados['id_produto']; ?>">

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
                                       value="<?php echo $dados['nome_produto']; ?>" 
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
                                        $selected = ($dados['catproduto_produto'] == $dadosopt['id_catproduto']) ? 'selected' : '';
                                        echo '<option value="'.$dadosopt['id_catproduto'].'" '.$selected.'>'.$dadosopt['nome_catproduto'].'</option>';
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
                                       value="<?php echo $dados['numero_produto']; ?>" 
                                       min="1" 
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
                                       value="<?php echo $dados['pontos_produto']; ?>" 
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
                                          maxlength="130"><?php echo $dados['texto1_produto']; ?></textarea>
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
                                       value="<?php echo $dados['site_produto']; ?>" 
                                       maxlength="300"
                                       placeholder="https://">
                                <div class="form-text">Link para compra do produto (visível apenas para administradores)</div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <input name="imagem1_antigo" type="hidden" value="<?php echo $dados['imagem1_produto']; ?>">
                            <input name="altera_imagem1" type="hidden" id="altera_imagem1" value="1">
                            
                            <div class="mb-3" id="imgmanter">
                                <label class="form-label">Imagem Ilustrativa</label>
                                <div class="d-flex align-items-center gap-3">
                                    <?php if (!empty($dados['imagem1_produto']) && file_exists("../imgs/".$dados['imagem1_produto'])) { ?>
                                        <img src="../imgs/<?php echo $dados['imagem1_produto']; ?>" 
                                             alt="Preview" 
                                             class="img-thumbnail" 
                                             style="max-height: 150px;">
                                        <div>
                                            <p class="mb-2"><strong><?php echo $dados['imagem1_produto']; ?></strong></p>
                                            <button type="button" class="btn btn-warning btn-sm" id="btmudar">
                                                <i class="fas fa-exchange-alt"></i> Alterar Imagem
                                            </button>
                                        </div>
                                    <?php } else { ?>
                                        <div class="alert alert-warning">
                                            <i class="fas fa-exclamation-triangle"></i> Nenhuma imagem enviada
                                        </div>
                                        <button type="button" class="btn btn-warning btn-sm" id="btmudar">
                                            <i class="fas fa-upload"></i> Enviar Imagem
                                        </button>
                                    <?php } ?>
                                </div>
                            </div>

                            <div class="mb-3" id="imgtrocar" style="display: none;">
                                <label for="imagem1_produto" class="form-label">Nova Imagem Ilustrativa</label>
                                <input type="file" 
                                       class="form-control" 
                                       id="imagem1_produto" 
                                       name="imagem1_produto" 
                                       accept="image/jpeg,image/jpg">
                                <div class="form-text">
                                    Imagens JPG com máximo 1000px de largura
                                </div>
                                <button type="button" class="btn btn-secondary btn-sm mt-2" id="btmanter">
                                    <i class="fas fa-undo"></i> Manter imagem atual
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <a href="produto-admin.php" class="btn btn-secondary">
                            <i class="fas fa-arrow-left"></i> Voltar
                        </a>
                        <button type="submit" class="btn btn-primary" id="btsubmit">
                            <i class="fas fa-save"></i> Salvar Alterações
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

    // Toggle imagem
    $('#btmudar').click(function() {
        $('#imgmanter').hide();
        $('#imgtrocar').show();
        $('#altera_imagem1').val('0');
    });

    $('#btmanter').click(function() {
        $('#imgtrocar').hide();
        $('#imgmanter').show();
        $('#altera_imagem1').val('1');
    });

    // Loading no submit
    $('#formulario').on('submit', function() {
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
<!-- InstanceEnd -->
</html>
