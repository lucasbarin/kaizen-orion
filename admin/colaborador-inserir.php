<?php
$sufixo = "colaborador";

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
<title>Novo Colaborador - Sistema Orion</title>
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
                <h1><i class="fas fa-user-plus"></i> Novo Colaborador</h1>
                <p class="text-muted">Cadastre um novo colaborador no sistema</p>
            </div>
            <div>
                <a href="colaborador-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="content-card">
            <form action="app/func_colaborador_inserir.php" method="post" enctype="multipart/form-data" id="formulario">
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Nome do colaborador<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <input name="nome_colaborador" type="text" required class="form-control" id="nome_colaborador" maxlength="100" placeholder="Nome completo"/>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">E-mail</label>
                    <div class="col-md-6">
                        <input name="email_colaborador" type="email" class="form-control" id="email_colaborador" maxlength="100" placeholder="email@exemplo.com"/>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Usuário<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <input name="usuario_colaborador" type="text" required class="form-control" id="usuario_colaborador" maxlength="20" placeholder="usuario.login"/>
                        <small class="form-text text-muted">Utilize apenas letras, números, _ e .</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Senha<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <input name="senha_colaborador" type="text" required class="form-control" id="senha_colaborador" maxlength="20"/>
                        <small class="form-text text-muted">Utilize apenas letras, números, _ e .</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Setor</label>
                    <div class="col-md-6">
                        <select class="form-select" name="setor_colaborador" id="setor_colaborador">
                            <option value="">Selecione</option>
                            <?php
                            $sqloption = sql("SELECT * FROM setor 
                            LEFT JOIN unidade ON unidade.id_unidade = setor.unidade_setor
                            ORDER BY nome_unidade ASC, nome_setor ASC", $con);
                            while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                            ?>
                                <option value="<?php echo $dadosopt['id_setor']; ?>">
                                    <?php echo $dadosopt['nome_unidade']; ?> &gt; <?php echo $dadosopt['nome_setor']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Gestor Kaizen?</label>
                    <div class="col-md-6">
                        <select class="form-select" name="lider_colaborador" id="lider_colaborador">
                            <?php
                            $sqloption = sql("SELECT * FROM opcao ORDER BY nome_opcao", $con);
                            if (mysqli_num_rows($sqloption)) {
                            ?>
                                <option value="">Selecione</option>
                                <?php
                                while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                ?>
                                    <option value="<?php echo $dadosopt['id_opcao']; ?>">
                                        <?php echo $dadosopt['nome_opcao']; ?>
                                    </option>
                                <?php
                                }
                            } else {
                            ?>
                                <option value="">Selecione</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Gestor responsável</label>
                    <div class="col-md-6">
                        <select class="form-select" name="gestor_colaborador" id="gestor_colaborador">
                            <?php
                            $sqloption = sql("SELECT * FROM colaborador
                            WHERE lider_colaborador = 1
                            ORDER BY nome_colaborador ASC", $con);
                            if (mysqli_num_rows($sqloption)) {
                            ?>
                                <option value="">Selecione</option>
                                <?php
                                while ($dadosopt = mysqli_fetch_assoc($sqloption)) {
                                ?>
                                    <option value="<?php echo $dadosopt['id_colaborador']; ?>">
                                        <?php echo $dadosopt['nome_colaborador']; ?>
                                    </option>
                                <?php
                                }
                            } else {
                            ?>
                                <option value="">Não há gestores cadastrados</option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="btsubmit">
                            <i class="fas fa-save"></i> Cadastrar Colaborador
                        </button>
                        <a href="colaborador-admin.php" class="btn btn-secondary btn-lg">
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
    // Select2 para melhor UX nos dropdowns
    $('#setor_colaborador, #lider_colaborador, #gestor_colaborador').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Selecione uma opção'
    });
    
    // Validação do formulário
    $('#formulario').on('submit', function(e) {
        var usuario = $('#usuario_colaborador').val();
        var senha = $('#senha_colaborador').val();
        var regex = /^[a-zA-Z0-9_.]+$/;
        
        if (!regex.test(usuario)) {
            e.preventDefault();
            alert('O usuário deve conter apenas letras, números, _ e .');
            $('#usuario_colaborador').focus();
            return false;
        }
        
        if (!regex.test(senha)) {
            e.preventDefault();
            alert('A senha deve conter apenas letras, números, _ e .');
            $('#senha_colaborador').focus();
            return false;
        }
        
        // Desabilita botão para evitar duplo envio
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Cadastrando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
<!-- InstanceEnd -->
