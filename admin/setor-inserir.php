<?php
$sufixo = "setor";

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
<title>Novo Setor - Sistema Orion</title>
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
        
        <div class="page-header">
            <div>
                <h1><i class="fas fa-sitemap"></i> Novo Setor</h1>
                <p class="text-muted">Cadastre um novo setor</p>
            </div>
            <div>
                <a href="setor-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="content-card">
            <form action="app/func_setor_inserir.php" method="post" id="formulario">
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Nome do setor<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <input name="nome_setor" type="text" required class="form-control" id="nome_setor" maxlength="100" placeholder="Nome do setor"/>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Unidade</label>
                    <div class="col-md-6">
                        <select class="form-select" name="unidade_setor" id="unidade_setor">
                            <option value="">Selecione</option>
                            <?php
                            $sqlUnidade = sql("SELECT * FROM unidade ORDER BY nome_unidade ASC", $con);
                            while($unidade = mysqli_fetch_assoc($sqlUnidade)) {
                            ?>
                                <option value="<?php echo $unidade['id_unidade']; ?>">
                                    <?php echo $unidade['nome_unidade']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="btsubmit">
                            <i class="fas fa-save"></i> Cadastrar Setor
                        </button>
                        <a href="setor-admin.php" class="btn btn-secondary btn-lg">
                            <i class="fas fa-times"></i> Cancelar
                        </a>
                    </div>
                </div>
                
            </form>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script>
$(document).ready(function() {
    $('#unidade_setor').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Selecione uma unidade'
    });
    
    $('#formulario').on('submit', function() {
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cadastrando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
<!-- InstanceEnd -->
