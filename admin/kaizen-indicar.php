<?php
$sufixo = "kaizen";

include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM kaizen ORDER BY id_kaizen ASC", $con);

$id_kaizen = trata($_GET['kaizen']);

if (!empty($id_kaizen) && is_numeric($id_kaizen)) {
    $sql = mysqli_query($con, "SELECT * FROM kaizen WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die(mysqli_error($con));
    
    if(mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Registro não encontrado!", "kaizen-admin.php");
    }
} else {
    volta("erro", "Registro não encontrado!", "kaizen-admin.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Completar Kaizen #<?php echo $id_kaizen; ?> - Sistema Orion</title>
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
                <h1><i class="fas fa-edit"></i> Completar Kaizen #<?php echo $id_kaizen; ?></h1>
                <p class="text-muted">Preencha os dados complementares do Kaizen</p>
            </div>
            <div>
                <a href="kaizen-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <div class="content-card">
            <form action="app/func_kaizen_indicar.php" method="post" enctype="multipart/form-data" id="formulario">
                <input name="id_kaizen" type="hidden" id="id_kaizen" value="<?php echo $dados['id_kaizen']; ?>">
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Tipo de Benefício<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <select class="form-select" name="tipo_kaizen" id="tipo_kaizen" required>
                            <option value="">Selecione</option>
                            <?php
                            $sqltipo = sql("SELECT tipo.*, comp.nome_comp FROM tipo 
                                           LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
                                           ORDER BY nome_tipo ASC", $con);
                            while($tipo = mysqli_fetch_assoc($sqltipo)) {
                                $label = $tipo['nome_tipo'];
                                if(!empty($tipo['nome_comp'])) { 
                                    $label .= ' - '.$tipo['nome_comp']; 
                                }
                            ?>
                                <option value="<?php echo $tipo['id_tipo']; ?>" <?php if ($dados['tipo_kaizen'] == $tipo['id_tipo']) echo 'selected'; ?>>
                                    <?php echo $label; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Autor Principal<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <select class="form-select" name="colaborador_kaizen" id="colaborador_kaizen" required>
                            <option value="">Selecione</option>
                            <?php
                            $sqlcolaborador = sql("SELECT * FROM colaborador ORDER BY nome_colaborador ASC", $con);
                            while($colaborador = mysqli_fetch_assoc($sqlcolaborador)) {
                            ?>
                                <option value="<?php echo $colaborador['id_colaborador']; ?>" <?php if ($dados['colaborador_kaizen'] == $colaborador['id_colaborador']) echo 'selected'; ?>>
                                    <?php echo $colaborador['nome_colaborador']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Colaborador que criou a ideia</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Colaborador Auxiliar 1</label>
                    <div class="col-md-6">
                        <select class="form-select" name="colaborador1_kaizen" id="colaborador1_kaizen">
                            <option value="">Nenhum</option>
                            <?php
                            $sqlcolaborador = sql("SELECT * FROM colaborador ORDER BY nome_colaborador ASC", $con);
                            while($colaborador = mysqli_fetch_assoc($sqlcolaborador)) {
                            ?>
                                <option value="<?php echo $colaborador['id_colaborador']; ?>" <?php if ($dados['colaborador1_kaizen'] == $colaborador['id_colaborador']) echo 'selected'; ?>>
                                    <?php echo $colaborador['nome_colaborador']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Opcional: colaborador que auxiliou na implementação</small>
                    </div>
                </div>
                
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Colaborador Auxiliar 2</label>
                    <div class="col-md-6">
                        <select class="form-select" name="colaborador2_kaizen" id="colaborador2_kaizen">
                            <option value="">Nenhum</option>
                            <?php
                            $sqlcolaborador = sql("SELECT * FROM colaborador ORDER BY nome_colaborador ASC", $con);
                            while($colaborador = mysqli_fetch_assoc($sqlcolaborador)) {
                            ?>
                                <option value="<?php echo $colaborador['id_colaborador']; ?>" <?php if ($dados['colaborador2_kaizen'] == $colaborador['id_colaborador']) echo 'selected'; ?>>
                                    <?php echo $colaborador['nome_colaborador']; ?>
                                </option>
                            <?php
                            }
                            ?>
                        </select>
                        <small class="form-text text-muted">Opcional: segundo colaborador que auxiliou na implementação</small>
                    </div>
                </div>
                
                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-success btn-lg" id="btsubmit">
                            <i class="fas fa-check"></i> Completar Kaizen
                        </button>
                        <a href="kaizen-admin.php" class="btn btn-secondary btn-lg">
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
    $('#tipo_kaizen, #colaborador_kaizen, #colaborador1_kaizen, #colaborador2_kaizen').select2({
        theme: 'bootstrap-5',
        width: '100%',
        placeholder: function() {
            return $(this).attr('id') === 'tipo_kaizen' || $(this).attr('id') === 'colaborador_kaizen' ? 'Selecione' : 'Nenhum';
        }
    });
    
    // Validação do formulário
    $('#formulario').on('submit', function() {
        $('#btsubmit').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Salvando...');
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
<!-- InstanceEnd -->
