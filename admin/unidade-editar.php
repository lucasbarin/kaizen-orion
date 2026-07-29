<?php
$sufixo = "unidade";

include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$sql = sql("SELECT * FROM unidade ORDER BY id_unidade ASC", $con);

$id_unidade = trata($_GET['id_unidade']);

if (!empty($id_unidade) && is_numeric($id_unidade)) {
    $sql = mysqli_query($con, "SELECT * FROM unidade WHERE id_unidade = ".$id_unidade." LIMIT 1") or die(mysqli_error($con));
    
    if(mysqli_num_rows($sql) > 0) {
        $dados = mysqli_fetch_array($sql);
    } else {
        volta("erro", "Registro não encontrado!", "unidade-admin.php");
    }
} else {
    volta("erro", "Registro não encontrado!", "unidade-admin.php");
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Editar Unidade - Sistema Orion</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
</head>
<body>
<div id="preloader"><div class="loader"></div></div>
<div class="admin-wrapper">
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
    <aside class="admin-sidebar" id="sidebar"><?php include("menu.php"); ?></aside>
    <main class="admin-content">
        <div class="page-header">
            <div><h1><i class="fas fa-building"></i> Editar Unidade</h1><p class="text-muted">Altere os dados da unidade</p></div>
            <div><a href="unidade-admin.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Voltar</a></div>
        </div>
        <div class="content-card">
            <form action="app/func_unidade_editar.php" method="post" id="formulario">
                <input name="id_unidade" type="hidden" value="<?php echo $dados['id_unidade']; ?>">
                <div class="row mb-4">
                    <label class="col-md-3 col-form-label">Nome da Unidade<span class="text-danger"> *</span></label>
                    <div class="col-md-6">
                        <input name="nome_unidade" type="text" required class="form-control" value="<?php echo $dados['nome_unidade']; ?>" maxlength="100"/>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-9 offset-md-3">
                        <button type="submit" class="btn btn-primary btn-lg" id="btsubmit"><i class="fas fa-save"></i> Salvar Alterações</button>
                        <a href="unidade-admin.php" class="btn btn-secondary btn-lg"><i class="fas fa-times"></i> Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </main>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<script>$(document).ready(function() { $('#formulario').on('submit', function() { $('#btsubmit').prop('disabled', true).html('<i class=\"fas fa-spinner fa-spin\"></i> Salvando...'); }); });</script>
</body>
</html>
