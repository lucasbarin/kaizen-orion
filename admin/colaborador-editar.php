<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$id_colaborador = trata($_GET['id_colaborador']);

if (!empty($id_colaborador) && is_numeric($id_colaborador)) {
    $sql = mysqli_query($con, "SELECT * FROM colaborador WHERE id_colaborador = ".$id_colaborador." LIMIT 1") or die(mysqli_error($con));
    
    if (mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Registro não encontrado!", "colaborador-admin.php");
    }
} else {
    volta("erro", "Registro não encontrado!", "colaborador-admin.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Editar Colaborador - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
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
                <h1><i class="fas fa-user-edit"></i> Editar Colaborador</h1>
                <p class="text-muted">Edite os dados do colaborador</p>
            </div>
            <div>
                <a href="colaborador-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <form action="app/func_colaborador_editar.php" method="post" id="formulario">
            <input name="id_colaborador" type="hidden" value="<?php echo $dados['id_colaborador']; ?>">
            
            <div class="admin-card">
                <div class="admin-card-header">
                    <h5><i class="fas fa-user"></i> Dados do Colaborador</h5>
                </div>
                <div class="admin-card-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="nome_colaborador">
                                Nome Completo <span class="text-danger">*</span>
                            </label>
                            <input name="nome_colaborador" type="text" required class="form-control" 
                                   id="nome_colaborador" value="<?php echo $dados['nome_colaborador']; ?>" maxlength="100">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="email_colaborador">E-mail</label>
                            <input name="email_colaborador" type="email" class="form-control" 
                                   id="email_colaborador" value="<?php echo $dados['email_colaborador']; ?>" maxlength="100">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="usuario_colaborador">
                                Usuário <span class="text-danger">*</span>
                            </label>
                            <input name="usuario_colaborador" type="text" required class="form-control" 
                                   id="usuario_colaborador" value="<?php echo $dados['usuario_colaborador']; ?>" maxlength="20">
                            <small class="text-muted">Use apenas letras, números, _ e .</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="senha_colaborador">
                                Senha <span class="text-danger">*</span>
                            </label>
                            <input name="senha_colaborador" type="text" required class="form-control" 
                                   id="senha_colaborador" value="<?php echo $dados['senha_colaborador']; ?>" maxlength="20">
                            <small class="text-muted">Use apenas letras, números, _ e .</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="setor_colaborador">Setor</label>
                            <select class="form-select select2" name="setor_colaborador" id="setor_colaborador">
                                <option <?php if ($dados['setor_colaborador'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
                                <?php
                                $sqloption = sql("SELECT * FROM setor 
                                                 LEFT JOIN unidade ON unidade.id_unidade = setor.unidade_setor
                                                 ORDER BY nome_unidade ASC, nome_setor ASC", $con);
                                while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                ?>
                                <option <?php if ($dados['setor_colaborador'] == $dadosopt['id_setor']) { echo 'selected="selected"'; } ?> 
                                        value="<?php echo $dadosopt['id_setor']; ?>">
                                    <?php echo $dadosopt['nome_unidade']; ?> > <?php echo $dadosopt['nome_setor']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label class="form-label" for="lider_colaborador">É Gestor Kaizen?</label>
                            <select class="form-select select2" name="lider_colaborador" id="lider_colaborador">
                                <option <?php if ($dados['lider_colaborador'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
                                <?php
                                $sqloption = sql("SELECT * FROM opcao ORDER BY nome_opcao", $con);
                                while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                ?>
                                <option <?php if ($dados['lider_colaborador'] == $dadosopt['id_opcao']) { echo 'selected="selected"'; } ?> 
                                        value="<?php echo $dadosopt['id_opcao']; ?>">
                                    <?php echo $dadosopt['nome_opcao']; ?>
                                </option>
                                <?php } ?>
                            </select>
                        </div>

                        <div class="col-md-12 mb-3">
                            <label class="form-label" for="gestor_colaborador">Gestor Responsável por este Colaborador</label>
                            <select class="form-select select2" name="gestor_colaborador" id="gestor_colaborador">
                                <option <?php if ($dados['gestor_colaborador'] == "") { echo 'selected="selected"'; } ?> value="">Selecione</option>
                                <?php
                                $sqloption = sql("SELECT * FROM colaborador
                                                 WHERE lider_colaborador = 1
                                                 ORDER BY nome_colaborador ASC", $con);
                                if (mysqli_num_rows($sqloption)) {
                                    while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                ?>
                                <option <?php if ($dados['gestor_colaborador'] == $dadosopt['id_colaborador']) { echo 'selected="selected"'; } ?> 
                                        value="<?php echo $dadosopt['id_colaborador']; ?>">
                                    <?php echo $dadosopt['nome_colaborador']; ?>
                                </option>
                                <?php 
                                    }
                                } else { 
                                ?>
                                <option value="">Não há gestores cadastrados</option>
                                <?php } ?>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="admin-card-footer">
                    <button type="submit" class="btn btn-orion-primary">
                        <i class="fas fa-save"></i> Salvar Alterações
                    </button>
                    <a href="colaborador-admin.php" class="btn btn-secondary">
                        <i class="fas fa-times"></i> Cancelar
                    </a>
                </div>
            </div>
        </form>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
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

    // Validação do formulário
    $('#formulario').submit(function(e) {
        const nome = $('#nome_colaborador').val().trim();
        const usuario = $('#usuario_colaborador').val().trim();
        const senha = $('#senha_colaborador').val().trim();

        if (!nome || !usuario || !senha) {
            alert('Por favor, preencha todos os campos obrigatórios.');
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
