<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");
$id_kaizen = trata($_GET['kaizen']);

if (!is_numeric($id_kaizen)) {
    volta("erro", "Kaizen inválido", "kaizen-admin.php");
    exit;
}

// Query principal com todos os dados
$sql = sql("SELECT k.*, t.nome_tipo, t.complemento_tipo, comp.nome_comp, comp.calculo_comp, 
            c.nome_colaborador, s.nome_setor, u.nome_unidade,
            c2.nome_colaborador as nome_colaborador1,
            c3.nome_colaborador as nome_colaborador2,
            s2.nome_setor as nome_setor1,
            s3.nome_setor as nome_setor2,
            u2.nome_unidade as nome_unidade1,
            u3.nome_unidade as nome_unidade2
            FROM kaizen k
            LEFT JOIN tipo t ON t.id_tipo = k.tipo_kaizen
            LEFT JOIN comp ON comp.id_comp = t.complemento_tipo
            LEFT JOIN colaborador c ON c.id_colaborador = k.colaborador_kaizen
            LEFT JOIN setor s ON s.id_setor = k.setor_kaizen
            LEFT JOIN unidade u ON u.id_unidade = k.unidade_kaizen
            LEFT JOIN colaborador c2 ON c2.id_colaborador = k.colaborador1_kaizen
            LEFT JOIN setor s2 ON s2.id_setor = c2.setor_colaborador
            LEFT JOIN unidade u2 ON u2.id_unidade = c2.unidade_colaborador
            LEFT JOIN colaborador c3 ON c3.id_colaborador = k.colaborador2_kaizen
            LEFT JOIN setor s3 ON s3.id_setor = c3.setor_colaborador
            LEFT JOIN unidade u3 ON u3.id_unidade = c3.unidade_colaborador
            WHERE k.id_kaizen = $id_kaizen LIMIT 1", $con);

if (mysqli_num_rows($sql) == 0) {
    volta("erro", "Kaizen não encontrado", "kaizen-admin.php");
    exit;
}

$dados = mysqli_fetch_array($sql);

// Detectar tipo do kaizen (custo, tempo ou redução)
$tipo_kaizen_tipo = "";
if ($dados['tipo_kaizen'] == 1) {
    $tipo_kaizen_tipo = "custo";
} elseif ($dados['tipo_kaizen'] == 2) {
    $tipo_kaizen_tipo = "tempo";
} else {
    // Buscar da tabela tipo
    $sql_tipo = sql("SELECT nome_tipo FROM tipo WHERE id_tipo = ".$dados['tipo_kaizen'], $con);
    if (mysqli_num_rows($sql_tipo) > 0) {
        $tipo_info = mysqli_fetch_array($sql_tipo);
        $tipo_kaizen_tipo = $tipo_info['nome_tipo'];
    }
}

// Buscar nome do admin que analisou (se houver)
$admin_nome = "";
if (!empty($dados['admin_kaizen'])) {
    $sql_admin = sql("SELECT nome_colaborador FROM colaborador WHERE id_colaborador = ".$dados['admin_kaizen']." LIMIT 1", $con);
    if (mysqli_num_rows($sql_admin) > 0) {
        $admin_data = mysqli_fetch_array($sql_admin);
        $admin_nome = $admin_data['nome_colaborador'];
    }
}

// Buscar dados do complemento para unidade
$unidade_complemento = "R$";
if (!empty($dados['tipo_complemento_kaizen'])) {
    $sql_comp = sql("SELECT * FROM comp WHERE id_comp = ".$dados['tipo_complemento_kaizen']." LIMIT 1", $con);
    if (mysqli_num_rows($sql_comp) > 0) {
        $comp_info = mysqli_fetch_array($sql_comp);
        if ($comp_info['calculo_comp'] == 2) {
            $unidade_complemento = "horas";
        } elseif ($comp_info['calculo_comp'] == 1) {
            $unidade_complemento = "kg de carbono";
        }
    }
}

// Buscar anexos v2
$anexos = listarAnexos($dados['id_kaizen'], $con);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Analisar Kaizen - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<!-- InstanceBeginEditable name="head" -->
<style>
.kaizen-detail-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    overflow: hidden;
}

.kaizen-detail-header {
    background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
    color: white;
    padding: 20px 25px;
    border-bottom: 3px solid var(--orion-secondary);
}

.kaizen-detail-header h5 {
    margin: 0;
    font-weight: 600;
    font-size: 1.1rem;
}

.kaizen-detail-body {
    padding: 25px;
}

.info-row {
    display: flex;
    margin-bottom: 20px;
    padding-bottom: 20px;
    border-bottom: 1px solid #eee;
}

.info-row:last-child {
    margin-bottom: 0;
    padding-bottom: 0;
    border-bottom: none;
}

.info-label {
    font-weight: 600;
    color: #565656;
    min-width: 180px;
    margin-right: 15px;
}

.info-value {
    color: #333;
    flex: 1;
}

.badge-complemento {
    background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
    color: white;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 1.1rem;
    font-weight: 600;
    display: inline-block;
    margin: 10px 0;
}

.badge-pontos {
    background: linear-gradient(135deg, #ffd700 0%, #ffed4e 100%);
    color: #333;
    padding: 8px 16px;
    border-radius: 20px;
    font-size: 1.1rem;
    font-weight: 600;
    display: inline-block;
    margin: 10px 0;
}

.gallery-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
    gap: 15px;
    margin-top: 15px;
}

.gallery-item {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
}

.gallery-item img {
    width: 100%;
    height: 150px;
    object-fit: cover;
    transition: transform 0.3s;
}

.gallery-item:hover img {
    transform: scale(1.05);
}

.file-item {
    display: flex;
    align-items: center;
    padding: 12px;
    background: #f8f9fa;
    border-radius: 8px;
    margin-bottom: 10px;
    transition: background 0.3s;
}

.file-item:hover {
    background: #e9ecef;
}

.file-icon {
    font-size: 2rem;
    color: var(--orion-primary);
    margin-right: 15px;
}

.file-info {
    flex: 1;
}

.file-name {
    font-weight: 600;
    color: #333;
    margin-bottom: 3px;
}

.file-meta {
    font-size: 0.85rem;
    color: #666;
}

.action-buttons {
    display: flex;
    gap: 15px;
    margin-top: 30px;
    flex-wrap: wrap;
}

@media (max-width: 768px) {
    .info-row {
        flex-direction: column;
    }
    
    .info-label {
        margin-bottom: 8px;
    }
    
    .action-buttons {
        flex-direction: column;
    }
    
    .action-buttons .btn {
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
                <h1><i class="fas fa-file-alt"></i> Analisar Kaizen</h1>
                <p class="text-muted">Visualize os detalhes e aprove/reprove o Kaizen</p>
            </div>
            <div>
                <a href="kaizen-admin.php" class="btn btn-secondary">
                    <i class="fas fa-arrow-left"></i> Voltar
                </a>
            </div>
        </div>

        <!-- Status -->
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-info-circle"></i> Status Atual</h5>
            </div>
            <div class="kaizen-detail-body">
                <div class="info-row">
                    <div class="info-label">Status:</div>
                    <div class="info-value">
                        <?php $status = $dados['status_kaizen']; include("../_status.php"); ?>
                    </div>
                </div>
                
                <?php 
                if (!empty($dados['dataconclusao_kaizen']) 
                    && $dados['dataconclusao_kaizen'] != '0000-00-00 00:00:00' 
                    && $dados['dataconclusao_kaizen'] != '0000-00-00'
                    && strtotime($dados['dataconclusao_kaizen']) > 0) { 
                ?>
                <div class="info-row">
                    <div class="info-label">Data de Conclusão:</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($dados['dataconclusao_kaizen'])); ?></div>
                </div>
                <?php } ?>
                
                <?php if (!empty($admin_nome)) { ?>
                <div class="info-row">
                    <div class="info-label">Analisado por:</div>
                    <div class="info-value"><?php echo $admin_nome; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Dados do Colaborador -->
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-user"></i> Dados do Colaborador</h5>
            </div>
            <div class="kaizen-detail-body">
                <div class="info-row">
                    <div class="info-label">Nome:</div>
                    <div class="info-value"><?php echo $dados['nome_colaborador']; ?></div>
                </div>
                
                <div class="info-row">
                    <div class="info-label">Data de Cadastro:</div>
                    <div class="info-value"><?php echo date('d/m/Y H:i', strtotime($dados['datacadastro_kaizen'])); ?></div>
                </div>
                
                <?php if (!empty($dados['nome_unidade'])) { ?>
                <div class="info-row">
                    <div class="info-label">Unidade:</div>
                    <div class="info-value"><?php echo $dados['nome_unidade']; ?></div>
                </div>
                <?php } ?>
                
                <?php if (!empty($dados['nome_setor'])) { ?>
                <div class="info-row">
                    <div class="info-label">Setor:</div>
                    <div class="info-value"><?php echo $dados['nome_setor']; ?></div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Benefício do Kaizen -->
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-star"></i> Benefício do Kaizen</h5>
            </div>
            <div class="kaizen-detail-body">
                <div class="info-row">
                    <div class="info-label">Tipo de Benefício:</div>
                    <div class="info-value">
                        <strong><?php echo $dados['nome_tipo']; ?></strong>
                        <?php if (!empty($dados['nome_comp'])) { ?>
                        <br><small class="text-muted"><?php echo $dados['nome_comp']; ?></small>
                        <?php } ?>
                    </div>
                </div>
                
                <?php if (!empty($dados['valor_original_kaizen']) && $dados['valor_original_kaizen'] > 0) { ?>
                <div class="info-row">
                    <div class="info-label">Valor Original:</div>
                    <div class="info-value">
                        <span class="badge-complemento">
                            <?php 
                            if ($unidade_complemento == "R$") {
                                echo "R$ " . number_format($dados['valor_original_kaizen'], 2, ',', '.');
                            } else {
                                echo number_format($dados['valor_original_kaizen'], 2, ',', '.') . " " . $unidade_complemento;
                            }
                            ?>
                        </span>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (!empty($dados['valor_original_kaizen']) && $dados['valor_original_kaizen'] > 0 && !empty($dados['tipo_complemento_kaizen'])) { ?>
                <div class="info-row">
                    <div class="info-label">Benefício Anual:</div>
                    <div class="info-value">
                        <span class="badge-complemento">
                            <?= formatarValorComplemento($dados['valor_original_kaizen'], $dados['tipo_complemento_kaizen']) ?>
                        </span>
                    </div>
                </div>
                <?php } ?>
                
                <?php if (!empty($dados['pontos_kaizen']) && $dados['pontos_kaizen'] > 0) { ?>
                <div class="info-row">
                    <div class="info-label">Pontos Concedidos:</div>
                    <div class="info-value">
                        <span class="badge-pontos">
                            <i class="fas fa-star"></i> <?php echo number_format($dados['pontos_kaizen'], 0, ',', '.'); ?> pontos
                        </span>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Descrição do Kaizen -->
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-align-left"></i> Descrição do Kaizen</h5>
            </div>
            <div class="kaizen-detail-body">
                <?php if (!empty($dados['situacao_atual'])) { ?>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-exclamation-triangle text-warning"></i> Situação Atual:</div>
                    <div class="info-value"><?php echo nl2br($dados['situacao_atual']); ?></div>
                </div>
                <?php } ?>
                
                <?php if (!empty($dados['onde_kaizen'])) { ?>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-lightbulb text-primary"></i> Objetivo da Melhoria:</div>
                    <div class="info-value"><?php echo nl2br($dados['onde_kaizen']); ?></div>
                </div>
                <?php } ?>
                
                <?php if (!empty($dados['resultado_kaizen'])) { ?>
                <div class="info-row">
                    <div class="info-label"><i class="fas fa-check-circle text-success"></i> Resultados Obtidos:</div>
                    <div class="info-value"><?php echo nl2br($dados['resultado_kaizen']); ?></div>
                </div>
                <?php } ?>
            </div>
        </div>

        <!-- Anexos -->
        <?php 
        $tem_anexos = false;
        $imagens = [];
        $arquivos = [];
        
        if (!empty($anexos) && count($anexos) > 0) {
            foreach ($anexos as $anexo) {
                $ext = strtolower(pathinfo($anexo['nome_arquivo'], PATHINFO_EXTENSION));
                if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $imagens[] = $anexo;
                } else {
                    $arquivos[] = $anexo;
                }
            }
            $tem_anexos = true;
        } elseif (!empty($dados['imagem1_kaizen']) || !empty($dados['imagem2_kaizen'])) {
            // Fallback para v1
            if (!empty($dados['imagem1_kaizen'])) {
                $imagens[] = ['nome_arquivo' => $dados['imagem1_kaizen'], 'nome_original' => null, 'data_upload' => null, 'tamanho_arquivo' => null];
            }
            if (!empty($dados['imagem2_kaizen'])) {
                $imagens[] = ['nome_arquivo' => $dados['imagem2_kaizen'], 'nome_original' => null, 'data_upload' => null, 'tamanho_arquivo' => null];
            }
            $tem_anexos = true;
        }
        
        if ($tem_anexos) {
        ?>
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-paperclip"></i> Anexos</h5>
            </div>
            <div class="kaizen-detail-body">
                <?php if (count($imagens) > 0) { ?>
                <h6 class="mb-3">Imagens</h6>
                <div class="gallery-grid">
                    <?php foreach ($imagens as $img) { ?>
                    <a href="../imgs/kaizen/<?php echo $img['nome_arquivo']; ?>" target="_blank" class="gallery-item">
                        <img src="../imgs/kaizen/<?php echo $img['nome_arquivo']; ?>" alt="Imagem">
                    </a>
                    <?php } ?>
                </div>
                <?php } ?>
                
                <?php if (count($arquivos) > 0) { ?>
                <h6 class="mb-3 mt-4">Arquivos</h6>
                <?php foreach ($arquivos as $arq) { 
                    $nome_exibir = !empty($arq['nome_original']) ? $arq['nome_original'] : $arq['nome_arquivo'];
                    $ext = strtolower(pathinfo($arq['nome_arquivo'], PATHINFO_EXTENSION));
                    $icon = 'fa-file';
                    $tipo = strtoupper($ext);
                    
                    if ($ext == 'pdf') { $icon = 'fa-file-pdf'; $tipo = 'PDF'; }
                    elseif (in_array($ext, ['doc', 'docx'])) { $icon = 'fa-file-word'; $tipo = 'Word'; }
                    elseif (in_array($ext, ['xls', 'xlsx'])) { $icon = 'fa-file-excel'; $tipo = 'Excel'; }
                    elseif (in_array($ext, ['zip', 'rar'])) { $icon = 'fa-file-archive'; $tipo = 'Compactado'; }
                ?>
                <a href="../imgs/kaizen/<?php echo $arq['nome_arquivo']; ?>" target="_blank" class="file-item text-decoration-none">
                    <div class="file-icon">
                        <i class="fas <?php echo $icon; ?>"></i>
                    </div>
                    <div class="file-info">
                        <div class="file-name"><?php echo $nome_exibir; ?></div>
                        <div class="file-meta">
                            <span class="badge bg-secondary"><?php echo $tipo; ?></span>
                            <?php 
                            if (!empty($arq['tamanho_arquivo'])) {
                                echo ' • ' . number_format($arq['tamanho_arquivo'] / 1024, 0) . ' KB';
                            }
                            if (!empty($arq['data_upload'])) {
                                echo ' • ' . date('d/m/Y', strtotime($arq['data_upload']));
                            }
                            ?>
                        </div>
                    </div>
                </a>
                <?php } ?>
                <?php } ?>
            </div>
        </div>
        <?php } ?>

        <!-- Departamentos Impactados -->
        <?php if ($dados['outrosdep_kaizen'] == 1 && !empty($dados['qual_kaizen'])) { ?>
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-sitemap"></i> Departamentos Impactados</h5>
            </div>
            <div class="kaizen-detail-body">
                <div class="alert alert-info mb-0">
                    <i class="fas fa-building me-2"></i>
                    <?php echo nl2br($dados['qual_kaizen']); ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Colaboradores Participantes -->
        <?php 
        $colaboradores_aux = [];
        if (!empty($dados['colaborador1_kaizen']) && !empty($dados['nome_colaborador1'])) {
            $colaboradores_aux[] = [
                'nome' => $dados['nome_colaborador1'],
                'setor' => $dados['nome_setor1'] ?? '',
                'unidade' => $dados['nome_unidade1'] ?? ''
            ];
        }
        if (!empty($dados['colaborador2_kaizen']) && !empty($dados['nome_colaborador2'])) {
            $colaboradores_aux[] = [
                'nome' => $dados['nome_colaborador2'],
                'setor' => $dados['nome_setor2'] ?? '',
                'unidade' => $dados['nome_unidade2'] ?? ''
            ];
        }
        
        if (count($colaboradores_aux) > 0) {
        ?>
        <div class="kaizen-detail-card">
            <div class="kaizen-detail-header">
                <h5><i class="fas fa-users"></i> Colaboradores Auxiliares</h5>
            </div>
            <div class="kaizen-detail-body">
                <div class="row">
                    <?php foreach ($colaboradores_aux as $colab) { ?>
                    <div class="col-md-6 mb-3">
                        <div class="border rounded p-3">
                            <h6 class="mb-2"><i class="fas fa-user text-primary me-2"></i><?php echo $colab['nome']; ?></h6>
                            <?php if (!empty($colab['unidade'])) { ?>
                            <small class="text-muted d-block"><i class="fas fa-building me-1"></i><?php echo $colab['unidade']; ?></small>
                            <?php } ?>
                            <?php if (!empty($colab['setor'])) { ?>
                            <small class="text-muted d-block"><i class="fas fa-sitemap me-1"></i><?php echo $colab['setor']; ?></small>
                            <?php } ?>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
        <?php } ?>

        <!-- Botões de Ação -->
        <div class="action-buttons">
            <?php if ($dados['status_kaizen'] != 2) { ?>
            <a href="app/func_kaizen_analisar.php?kaizen=<?php echo $dados['id_kaizen']; ?>&amp;analise=2" 
               class="btn btn-success btn-lg botaoconfirma">
                <i class="fas fa-check"></i> Aprovar Kaizen
            </a>
            <?php } ?>
            
            <?php if ($dados['status_kaizen'] != 3) { ?>
            <a href="app/func_kaizen_analisar.php?kaizen=<?php echo $dados['id_kaizen']; ?>&amp;analise=3" 
               class="btn btn-danger btn-lg botaoconfirma">
                <i class="fas fa-times"></i> Reprovar Kaizen
            </a>
            <?php } ?>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // Confirmação para aprovação/reprovação
    $('.botaoconfirma').click(function(e) {
        var acao = $(this).hasClass('btn-success') ? 'aprovar' : 'reprovar';
        var mensagem = acao == 'aprovar' ? 
            'Tem certeza que deseja APROVAR este Kaizen? Os pontos serão concedidos ao colaborador.' :
            'Tem certeza que deseja REPROVAR este Kaizen?';
        
        if (!confirm(mensagem)) {
            e.preventDefault();
            return false;
        }
    });
});
</script>
<!-- InstanceEndEditable -->
</body>
</html>
