<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$id_pg = trata($_GET['id_pg']);

if (!empty($id_pg) && is_numeric($id_pg)) {
    $sql = mysqli_query($con, "SELECT * FROM pg WHERE id_pg = ".$id_pg." LIMIT 1") or die(mysqli_error($con));
    
    if (mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Página não encontrada!", "home.php");
    }
} else {
    volta("erro", "Página não encontrada!", "home.php");
}

$link_menu = $dados['id_pg'];
include('pg-config.php');
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Configurações - <?php echo $dados['nome1_pg']; ?> - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<style>
.config-section {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    overflow: hidden;
}

.config-section-header {
    background: linear-gradient(135deg, #565656 0%, #333 100%);
    color: white;
    padding: 15px 25px;
    border-bottom: 3px solid var(--orion-secondary);
}

.config-section-header h6 {
    margin: 0;
    font-weight: 600;
    font-size: 1rem;
}

.config-section-body {
    padding: 30px 25px;
}

.form-floating-custom {
    position: relative;
    margin-bottom: 25px;
}

.form-floating-custom .form-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
    display: flex;
    align-items: center;
}

.form-floating-custom .form-label i {
    margin-right: 8px;
    color: var(--orion-primary);
}

.form-floating-custom .form-control,
.form-floating-custom .form-select {
    border-radius: 8px;
    border: 2px solid #e0e0e0;
    padding: 12px 15px;
    transition: all 0.3s;
}

.form-floating-custom .form-control:focus,
.form-floating-custom .form-select:focus {
    border-color: var(--orion-primary);
    box-shadow: 0 0 0 0.2rem rgba(0, 158, 227, 0.15);
}

.help-text {
    font-size: 0.875rem;
    color: #666;
    margin-top: 5px;
    display: block;
}

.help-text i {
    color: var(--orion-primary);
    margin-right: 5px;
}

.config-highlight {
    background: #fff9e6;
    border-left: 4px solid var(--orion-secondary);
    padding: 15px;
    border-radius: 8px;
    margin-bottom: 20px;
}

.config-highlight strong {
    color: #333;
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
                <h1><i class="fas fa-cog"></i> Configurações</h1>
                <p class="text-muted"><?php echo $dados['nome1_pg']; ?></p>
            </div>
        </div>

        <?php if ($id_pg == 101) { ?>
        <div class="config-highlight">
            <strong><i class="fas fa-info-circle"></i> Sistema de Pontos Kaizen</strong>
            <p class="mb-0 mt-2">Configure os valores de pontos concedidos aos colaboradores e parâmetros de cálculo para os benefícios.</p>
        </div>
        <?php } ?>

        <form action="app/func_pg_editar.php" method="post" id="formulario">
            <input name="id_pg" type="hidden" value="<?php echo $dados['id_pg']; ?>">
            
            <div class="config-section">
                <div class="config-section-header">
                    <h6><i class="fas fa-sliders-h"></i> Parâmetros de Configuração</h6>
                </div>
                <div class="config-section-body">
                    <div class="row">
                        <?php
                        // Campo 1a (Título/Input)
                        if ($l1a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome1_pg">
                                    <i class="fas fa-star"></i>
                                    <?php echo !empty($l1a) ? $l1a : 'Título 1'; ?>
                                </label>
                                <input name="nome1_pg" type="text" class="form-control" id="nome1_pg" 
                                       value="<?php echo $dados['nome1_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    Pontos concedidos ao criador principal da ideia
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 1b (Texto/Textarea)
                        if ($l1b) {
                            $cl = '';
                            if ($h1 == 1 or $h1 == 2) {
                                $cl = $h1 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto1_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l1b) ? $l1b : 'Texto 1'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto1_pg" id="texto1_pg"><?php echo $dados['texto1_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 2a
                        if ($l2a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome2_pg">
                                    <i class="fas fa-users"></i>
                                    <?php echo !empty($l2a) ? $l2a : 'Título 2'; ?>
                                </label>
                                <input name="nome2_pg" type="text" class="form-control" id="nome2_pg" 
                                       value="<?php echo $dados['nome2_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    Pontos concedidos aos auxiliares/assistentes
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 2b
                        if ($l2b) {
                            $cl = '';
                            if ($h2 == 1 or $h2 == 2) {
                                $cl = $h2 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto2_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l2b) ? $l2b : 'Texto 2'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto2_pg" id="texto2_pg"><?php echo $dados['texto2_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 3a
                        if ($l3a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome3_pg">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    <?php echo !empty($l3a) ? $l3a : 'Título 3'; ?>
                                </label>
                                <input name="nome3_pg" type="text" class="form-control" id="nome3_pg" 
                                       value="<?php echo $dados['nome3_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101 && !empty($l3b)) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    <?php echo $l3b; ?>
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 3b
                        if ($l3b && empty($l3a)) {
                            $cl = '';
                            if ($h3 == 1 or $h3 == 2) {
                                $cl = $h3 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto3_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l3b) ? $l3b : 'Texto 3'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto3_pg" id="texto3_pg"><?php echo $dados['texto3_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 4a
                        if ($l4a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome4_pg">
                                    <i class="fas fa-calculator"></i>
                                    <?php echo !empty($l4a) ? $l4a : 'Título 4'; ?>
                                </label>
                                <input name="nome4_pg" type="text" class="form-control" id="nome4_pg" 
                                       value="<?php echo $dados['nome4_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101 && !empty($l4b)) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    <?php echo $l4b; ?>
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 4b
                        if ($l4b && empty($l4a)) {
                            $cl = '';
                            if ($h4 == 1 or $h4 == 2) {
                                $cl = $h4 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto4_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l4b) ? $l4b : 'Texto 4'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto4_pg" id="texto4_pg"><?php echo $dados['texto4_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 5a
                        if ($l5a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome5_pg">
                                    <i class="fas fa-dollar-sign"></i>
                                    <?php echo !empty($l5a) ? $l5a : 'Título 5'; ?>
                                </label>
                                <input name="nome5_pg" type="text" class="form-control" id="nome5_pg" 
                                       value="<?php echo $dados['nome5_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101 && !empty($l5b)) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    <?php echo $l5b; ?>
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 5b
                        if ($l5b && empty($l5a)) {
                            $cl = '';
                            if ($h5 == 1 or $h5 == 2) {
                                $cl = $h5 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto5_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l5b) ? $l5b : 'Texto 5'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto5_pg" id="texto5_pg"><?php echo $dados['texto5_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 6a
                        if ($l6a) {
                        ?>
                        <div class="col-md-6">
                            <div class="form-floating-custom">
                                <label class="form-label" for="nome6_pg">
                                    <i class="fas fa-exchange-alt"></i>
                                    <?php echo !empty($l6a) ? $l6a : 'Título 6'; ?>
                                </label>
                                <input name="nome6_pg" type="text" class="form-control" id="nome6_pg" 
                                       value="<?php echo $dados['nome6_pg']; ?>" maxlength="150">
                                <?php if ($id_pg == 101 && !empty($l6b)) { ?>
                                <small class="help-text">
                                    <i class="fas fa-info-circle"></i>
                                    <?php echo $l6b; ?>
                                </small>
                                <?php } ?>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campo 6b
                        if ($l6b && empty($l6a)) {
                            $cl = '';
                            if ($h6 == 1 or $h6 == 2) {
                                $cl = $h6 == 1 ? 'wysihtml5' : 'ckeditor';
                            }
                        ?>
                        <div class="col-md-12">
                            <div class="form-floating-custom">
                                <label class="form-label" for="texto6_pg">
                                    <i class="fas fa-align-left"></i>
                                    <?php echo !empty($l6b) ? $l6b : 'Texto 6'; ?>
                                </label>
                                <textarea class="<?php echo $cl; ?> form-control" rows="6" name="texto6_pg" id="texto6_pg"><?php echo $dados['texto6_pg']; ?></textarea>
                            </div>
                        </div>
                        <?php } ?>

                        <?php
                        // Campos 7, 8, 9 (seguem o mesmo padrão - adiciono resumido)
                        if ($l7a) {
                            echo '<div class="col-md-6"><div class="form-floating-custom">
                                  <label class="form-label" for="nome7_pg"><i class="fas fa-cog"></i> '.(!empty($l7a) ? $l7a : 'Título 7').'</label>
                                  <input name="nome7_pg" type="text" class="form-control" id="nome7_pg" value="'.$dados['nome7_pg'].'" maxlength="150">
                                  </div></div>';
                        }
                        if ($l7b && empty($l7a)) {
                            $cl = $h7 == 1 ? 'wysihtml5' : ($h7 == 2 ? 'ckeditor' : '');
                            echo '<div class="col-md-12"><div class="form-floating-custom">
                                  <label class="form-label" for="texto7_pg"><i class="fas fa-align-left"></i> '.(!empty($l7b) ? $l7b : 'Texto 7').'</label>
                                  <textarea class="'.$cl.' form-control" rows="6" name="texto7_pg" id="texto7_pg">'.$dados['texto7_pg'].'</textarea>
                                  </div></div>';
                        }
                        
                        if ($l8a) {
                            echo '<div class="col-md-6"><div class="form-floating-custom">
                                  <label class="form-label" for="nome8_pg"><i class="fas fa-cog"></i> '.(!empty($l8a) ? $l8a : 'Título 8').'</label>
                                  <input name="nome8_pg" type="text" class="form-control" id="nome8_pg" value="'.$dados['nome8_pg'].'" maxlength="150">
                                  </div></div>';
                        }
                        if ($l8b && empty($l8a)) {
                            $cl = $h8 == 1 ? 'wysihtml5' : ($h8 == 2 ? 'ckeditor' : '');
                            echo '<div class="col-md-12"><div class="form-floating-custom">
                                  <label class="form-label" for="texto8_pg"><i class="fas fa-align-left"></i> '.(!empty($l8b) ? $l8b : 'Texto 8').'</label>
                                  <textarea class="'.$cl.' form-control" rows="6" name="texto8_pg" id="texto8_pg">'.$dados['texto8_pg'].'</textarea>
                                  </div></div>';
                        }
                        
                        if ($l9a) {
                            echo '<div class="col-md-6"><div class="form-floating-custom">
                                  <label class="form-label" for="nome9_pg"><i class="fas fa-cog"></i> '.(!empty($l9a) ? $l9a : 'Título 9').'</label>
                                  <input name="nome9_pg" type="text" class="form-control" id="nome9_pg" value="'.$dados['nome9_pg'].'" maxlength="150">
                                  </div></div>';
                        }
                        if ($l9b && empty($l9a)) {
                            $cl = $h9 == 1 ? 'wysihtml5' : ($h9 == 2 ? 'ckeditor' : '');
                            echo '<div class="col-md-12"><div class="form-floating-custom">
                                  <label class="form-label" for="texto9_pg"><i class="fas fa-align-left"></i> '.(!empty($l9b) ? $l9b : 'Texto 9').'</label>
                                  <textarea class="'.$cl.' form-control" rows="6" name="texto9_pg" id="texto9_pg">'.$dados['texto9_pg'].'</textarea>
                                  </div></div>';
                        }
                        ?>
                    </div>
                </div>

                <div class="admin-card-footer">
                    <button type="submit" class="btn btn-orion-primary btn-lg">
                        <i class="fas fa-save"></i> Salvar Configurações
                    </button>
                    <a href="home.php" class="btn btn-secondary btn-lg">
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
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // Validação básica do formulário
    $('#formulario').submit(function(e) {
        let hasError = false;
        
        // Validar campos numéricos se for id_pg=101
        <?php if ($id_pg == 101) { ?>
        const numericFields = ['nome1_pg', 'nome2_pg', 'nome3_pg', 'nome4_pg', 'nome5_pg', 'nome6_pg'];
        numericFields.forEach(field => {
            const val = $('#' + field).val().trim();
            if (val && isNaN(val.replace(',', '.'))) {
                alert('O campo deve conter apenas números.');
                $('#' + field).focus();
                hasError = true;
                return false;
            }
        });
        <?php } ?>
        
        if (hasError) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
