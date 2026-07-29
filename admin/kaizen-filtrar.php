<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

if (isset($_GET['clear']) && $_GET['clear'] == 1) {
    unset($_SESSION['tipo_kaizen'], $_SESSION['status_kaizen'], $_SESSION['destaque_kaizen'], $_SESSION['colaborador_kaizen'], $_SESSION['lider_kaizen'], $_SESSION['unidade_kaizen'], $_SESSION['setor_kaizen'], $_SESSION['data_cadastro_ini'], $_SESSION['data_cadastro_fim']);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Filtrar Kaizens - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/css/bootstrap-datepicker3.min.css" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<style>
.filter-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

.filter-header {
    background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
    color: white;
    padding: 20px 25px;
    border-bottom: 3px solid var(--orion-secondary);
}

.filter-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.2rem;
}

.filter-body {
    padding: 30px 25px;
}

.form-group {
    margin-bottom: 25px;
}

.form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.select2-container--bootstrap-5 .select2-selection {
    min-height: 45px;
    padding: 8px 12px;
    border-radius: 8px;
    border: 1px solid #ced4da;
}

.select2-container--bootstrap-5 .select2-selection--single .select2-selection__rendered {
    line-height: 28px;
}

.input-group-datepicker {
    border-radius: 8px;
    overflow: hidden;
}

.input-group-datepicker .form-control {
    border-right: none;
}

.input-group-datepicker .btn {
    border-left: none;
    background: white;
    border-color: #ced4da;
    color: var(--orion-primary);
}

.input-group-datepicker .btn:hover {
    background: #f8f9fa;
    color: var(--orion-primary-dark);
}

.filter-actions {
    display: flex;
    gap: 15px;
    padding: 25px;
    background: #f8f9fa;
    border-top: 1px solid #dee2e6;
}

@media (max-width: 768px) {
    .filter-actions {
        flex-direction: column;
    }
    
    .filter-actions .btn {
        width: 100%;
    }
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
                <h1><i class="fas fa-filter"></i> Filtrar Kaizens</h1>
                <p class="text-muted">Configure os filtros para gerar relatórios personalizados</p>
            </div>
            <div>
                <?php if (!empty($_SESSION['tipo_kaizen']) or !empty($_SESSION['status_kaizen']) or !empty($_SESSION['colaborador_kaizen']) or !empty($_SESSION['lider_kaizen']) or !empty($_SESSION['unidade_kaizen']) or !empty($_SESSION['setor_kaizen']) or !empty($_SESSION['data_cadastro_ini']) or !empty($_SESSION['data_cadastro_fim'])) { ?>
                <a href="kaizen-filtrar.php?clear=1" class="btn btn-danger">
                    <i class="fas fa-times"></i> Limpar Filtros
                </a>
                <?php } ?>
            </div>
        </div>

        <form action="kaizen-busca.php" method="get" id="formularioFiltro">
            <div class="filter-card">
                <div class="filter-header">
                    <h5><i class="fas fa-sliders-h"></i> Configuração de Filtros</h5>
                </div>
                <div class="filter-body">
                    <div class="row">
                        <!-- Tipo -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="tipo_kaizen">
                                <i class="fas fa-tag text-primary me-2"></i>Tipo de Benefício
                            </label>
                            <select class="form-select select2" name="tipo_kaizen" id="tipo_kaizen">
                                <option <?php if (($_SESSION['tipo_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODOS OS TIPOS</option>
                                <?php
                                $sql = sql("SELECT tipo.*, comp.nome_comp FROM tipo 
                                           LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
                                           ORDER BY numero_tipo ASC", $con);
                                while ($data = mysqli_fetch_assoc($sql)) {
                                    $label = $data['nome_tipo'];
                                    if (!empty($data['nome_comp'])) {
                                        $label .= ' - ' . $data['nome_comp'];
                                    }
                                ?>
                                <option <?php if (($_SESSION['tipo_kaizen'] ?? '') == $data['id_tipo']) { echo 'selected="selected"'; } ?> value="<?php echo $data['id_tipo']; ?>"><?php echo $label; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Status -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="status_kaizen">
                                <i class="fas fa-flag text-warning me-2"></i>Status
                            </label>
                            <select class="form-select select2" name="status_kaizen" id="status_kaizen">
                                <option <?php if (($_SESSION['status_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODOS OS STATUS</option>
                                <option <?php if (($_SESSION['status_kaizen'] ?? '') == 2) { echo 'selected="selected"'; } ?> value="2">Aprovado</option>
                                <option <?php if (($_SESSION['status_kaizen'] ?? '') == 3) { echo 'selected="selected"'; } ?> value="3">Recusado</option>
                                <option <?php if (($_SESSION['status_kaizen'] ?? '') == 1) { echo 'selected="selected"'; } ?> value="1">Aguardando análise</option>
                            </select>
                        </div>

                        <!-- Colaborador -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="colaborador_kaizen">
                                <i class="fas fa-user text-info me-2"></i>Colaborador
                            </label>
                            <select class="form-select select2" name="colaborador_kaizen" id="colaborador_kaizen">
                                <option <?php if (($_SESSION['colaborador_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODOS OS COLABORADORES</option>
                                <?php
                                $sql = sql("SELECT * FROM colaborador WHERE id_colaborador > 1 ORDER BY nome_colaborador ASC", $con);
                                while ($data = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option <?php if (($_SESSION['colaborador_kaizen'] ?? '') == $data['id_colaborador']) { echo 'selected="selected"'; } ?> value="<?php echo $data['id_colaborador']; ?>"><?php echo $data['nome_colaborador']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Gestor Kaizen -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="lider_kaizen">
                                <i class="fas fa-user-tie text-success me-2"></i>Gestor Kaizen (Analisou)
                            </label>
                            <select class="form-select select2" name="lider_kaizen" id="lider_kaizen">
                                <option <?php if (($_SESSION['lider_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODOS OS GESTORES</option>
                                <?php
                                $sql = sql("SELECT * FROM colaborador WHERE lider_colaborador = 1 ORDER BY nome_colaborador ASC", $con);
                                while ($data = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option <?php if (($_SESSION['lider_kaizen'] ?? '') == $data['id_colaborador']) { echo 'selected="selected"'; } ?> value="<?php echo $data['id_colaborador']; ?>"><?php echo $data['nome_colaborador']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Unidade -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="unidade_kaizen">
                                <i class="fas fa-building text-secondary me-2"></i>Unidade
                            </label>
                            <select class="form-select select2" name="unidade_kaizen" id="unidade_kaizen">
                                <option <?php if (($_SESSION['unidade_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODAS AS UNIDADES</option>
                                <?php
                                $sql = sql("SELECT * FROM unidade ORDER BY nome_unidade ASC", $con);
                                while ($data = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option <?php if (($_SESSION['unidade_kaizen'] ?? '') == $data['id_unidade']) { echo 'selected="selected"'; } ?> value="<?php echo $data['id_unidade']; ?>"><?php echo $data['nome_unidade']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Setor -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="setor_kaizen">
                                <i class="fas fa-sitemap text-danger me-2"></i>Setor
                            </label>
                            <select class="form-select select2" name="setor_kaizen" id="setor_kaizen">
                                <option <?php if (($_SESSION['setor_kaizen'] ?? '') == "") { echo 'selected="selected"'; } ?> value="">TODOS OS SETORES</option>
                                <?php
                                $sql = sql("SELECT * FROM setor ORDER BY nome_setor ASC", $con);
                                while ($data = mysqli_fetch_assoc($sql)) {
                                ?>
                                <option <?php if (($_SESSION['setor_kaizen'] ?? '') == $data['id_setor']) { echo 'selected="selected"'; } ?> value="<?php echo $data['id_setor']; ?>"><?php echo $data['nome_setor']; ?></option>
                                <?php } ?>
                            </select>
                        </div>

                        <!-- Data Início -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="data_cadastro_ini">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Data Cadastro (Início)
                            </label>
                            <div class="input-group input-group-datepicker">
                                <input type="text" class="form-control datepicker" name="data_cadastro_ini" id="data_cadastro_ini" 
                                       value="<?php if (($_SESSION['data_cadastro_ini'] ?? '') != '0000-00-00' && ($_SESSION['data_cadastro_ini'] ?? '') != '') { echo $_SESSION['data_cadastro_ini']; } ?>" 
                                       placeholder="dd/mm/aaaa" autocomplete="off">
                                <button class="btn" type="button" onclick="$('#data_cadastro_ini').focus();">
                                    <i class="fas fa-calendar"></i>
                                </button>
                            </div>
                            <small class="text-muted">Deixe em branco para não filtrar</small>
                        </div>

                        <!-- Data Fim -->
                        <div class="col-md-6 form-group">
                            <label class="form-label" for="data_cadastro_fim">
                                <i class="fas fa-calendar-alt text-primary me-2"></i>Data Cadastro (Fim)
                            </label>
                            <div class="input-group input-group-datepicker">
                                <input type="text" class="form-control datepicker" name="data_cadastro_fim" id="data_cadastro_fim" 
                                       value="<?php if (($_SESSION['data_cadastro_fim'] ?? '') != '0000-00-00' && ($_SESSION['data_cadastro_fim'] ?? '') != '') { echo $_SESSION['data_cadastro_fim']; } ?>" 
                                       placeholder="dd/mm/aaaa" autocomplete="off">
                                <button class="btn" type="button" onclick="$('#data_cadastro_fim').focus();">
                                    <i class="fas fa-calendar"></i>
                                </button>
                            </div>
                            <small class="text-muted">Deixe em branco para não filtrar</small>
                        </div>
                    </div>
                </div>

                <div class="filter-actions">
                    <button type="submit" class="btn btn-orion-primary btn-lg">
                        <i class="fas fa-search"></i> Gerar Relatório
                    </button>
                    <a href="kaizen-admin.php" class="btn btn-secondary btn-lg">
                        <i class="fas fa-arrow-left"></i> Voltar
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
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap-datepicker@1.10.0/dist/locales/bootstrap-datepicker.pt-BR.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // Select2
    $('.select2').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: 'Selecione...',
        allowClear: true
    });

    // Datepicker
    $('.datepicker').datepicker({
        format: 'dd/mm/yyyy',
        language: 'pt-BR',
        autoclose: true,
        todayHighlight: true,
        orientation: 'bottom auto'
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
