<?php
ob_start();
include("app/sessao.php");
include("../app/conexao8.php");
include("../app/funcoes8.php");

$link_menu = 2;

// Inicializar variável $filtro
$filtro = "";

if (($_GET['clear'] ?? 0) == 1) {
    unset($_SESSION['tipo_kaizen'], $_SESSION['status_kaizen'], $_SESSION['destaque_kaizen'], $_SESSION['colaborador_kaizen'], $_SESSION['lider_kaizen'], $_SESSION['unidade_kaizen'], $_SESSION['setor_kaizen'], $_SESSION['data_cadastro_ini'], $_SESSION['data_cadastro_fim']);
} else {
    if (!empty($_GET) or ($_SESSION['tipo_kaizen'] ?? false) or ($_SESSION['status_kaizen'] ?? false) or ($_SESSION['destaque_kaizen'] ?? false) or ($_SESSION['colaborador_kaizen'] ?? false) or ($_SESSION['lider_kaizen'] ?? false) or ($_SESSION['unidade_kaizen'] ?? false) or ($_SESSION['setor_kaizen'] ?? false) or ($_SESSION['data_cadastro_ini'] ?? false) or ($_SESSION['data_cadastro_fim'] ?? false)) {

        if (!empty($_GET)) {
            foreach ($_GET as $k => $v) {
                $$k = trata($v);
            }
        } else {
            foreach ($_SESSION as $k => $v) {
                $$k = trata($v);
            }
        }

        $filtro = "";

        if ($tipo_kaizen ?? false) {
            $filtro .= " AND tipo_kaizen = " . intval($tipo_kaizen) . " ";
            $_SESSION['tipo_kaizen'] = $tipo_kaizen;
        } else {
            $_SESSION['tipo_kaizen'] = '';
        }

        if ($status_kaizen ?? false) {
            $filtro .= " AND status_kaizen = " . intval($status_kaizen) . " ";
            $_SESSION['status_kaizen'] = $status_kaizen;
        } else {
            $_SESSION['status_kaizen'] = '';
        }

        if ($destaque_kaizen ?? false) {
            if ($destaque_kaizen == 1) {
                $filtro .= " AND destaque_kaizen = 1 ";
            } else {
                $filtro .= " AND destaque_kaizen != 1 ";
            }
            $_SESSION['destaque_kaizen'] = $destaque_kaizen;
        } else {
            $_SESSION['destaque_kaizen'] = '';
        }

        if ($colaborador_kaizen ?? false) {
            $filtro .= " AND colaborador_kaizen = " . intval($colaborador_kaizen) . " ";
            $_SESSION['colaborador_kaizen'] = $colaborador_kaizen;
        } else {
            $_SESSION['colaborador_kaizen'] = '';
        }

        if ($lider_kaizen ?? false) {
            $filtro .= " AND admin_kaizen = " . intval($lider_kaizen) . " ";
            $_SESSION['lider_kaizen'] = $lider_kaizen;
        } else {
            $_SESSION['lider_kaizen'] = '';
        }

        if ($unidade_kaizen ?? false) {
            $filtro .= " AND unidade_kaizen = " . intval($unidade_kaizen) . " ";
            $_SESSION['unidade_kaizen'] = $unidade_kaizen;
        } else {
            $_SESSION['unidade_kaizen'] = '';
        }

        if ($setor_kaizen ?? false) {
            $filtro .= " AND setor_kaizen = " . intval($setor_kaizen) . " ";
            $_SESSION['setor_kaizen'] = $setor_kaizen;
        } else {
            $_SESSION['setor_kaizen'] = '';
        }

        if (($data_cadastro_ini ?? false) or ($data_cadastro_fim ?? false)) {
            $data_ini = data_sql($data_cadastro_ini ?? '');
            $_SESSION['data_cadastro_ini'] = $data_cadastro_ini ?? '';
            $data_fim = data_sql($data_cadastro_fim ?? '');
            $_SESSION['data_cadastro_fim'] = $data_cadastro_fim ?? '';

            if (($data_cadastro_ini ?? false) && ($data_cadastro_fim ?? false)) {
                $filtro .= " AND datacadastro_kaizen BETWEEN ('" . $data_ini . "') AND ('" . $data_fim . "') ";
            } else {
                if ($data_cadastro_ini ?? false) {
                    $filtro .= " AND datacadastro_kaizen  >= '" . $data_ini . "' ";
                } else {
                    $filtro .= " AND datacadastro_kaizen  <= '" . $data_fim . "' ";
                }
            }
        } else {
            $_SESSION['data_cadastro_ini'] = '';
            $_SESSION['data_cadastro_fim'] = '';
        }
    }
}

// Query principal
$sql = sql("SELECT kaizen.*, tipo.*, comp.nome_comp, colaborador.nome_colaborador, setor.nome_setor 
            FROM kaizen
            LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
            LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
            LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
            LEFT JOIN setor ON setor.id_setor = kaizen.setor_kaizen
            WHERE id_kaizen > 0
            " . $filtro . "
            ORDER BY datacadastro_kaizen DESC", $con);

// Somas agregadas (v2.1 - Corrigido: tipo 1 usa valor_original direto, tipos 2/3 usam complemento calculado)
$sqlSoma = sql("SELECT 
                SUM(custo_kaizen) AS 'redcusto',
                SUM(tempo_kaizen) AS 'redtempo',
                SUM(incidente_kaizen) AS 'redincidente',
                SUM(CASE WHEN tipo_complemento_kaizen = 1 THEN valor_original_kaizen WHEN tipo_complemento_kaizen IN (2,3) THEN complemento_kaizen ELSE 0 END) AS 'beneficio_reais',
                SUM(CASE WHEN tipo_complemento_kaizen = 3 THEN valor_original_kaizen ELSE 0 END) AS 'beneficio_horas',
                SUM(pontos_kaizen) AS 'pontos_total'
                FROM kaizen
                LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
                LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
                LEFT JOIN setor ON setor.id_setor = kaizen.setor_kaizen
                WHERE id_kaizen > 0
                " . $filtro, $con);
$soma = mysqli_fetch_array($sqlSoma);

$gTotal = mysqli_num_rows($sql);

// Dados para gráfico de tipos
$gSqlTipo = sql("SELECT tipo.*, comp.nome_comp FROM tipo LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo", $con);
$tipoLabels = [];
$tipoQuantidades = [];
$tipoCores = ['#009ee3', '#fcd700', '#00812e', '#e74c3c', '#9b59b6', '#3498db', '#e67e22', '#1abc9c', '#34495e'];
$tipoIndex = 0;

while ($gTipo = mysqli_fetch_assoc($gSqlTipo)) {
    $sqlGtipo = sql("SELECT * FROM kaizen
                     LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
                     WHERE id_kaizen > 0
                     " . $filtro . " AND tipo_kaizen = " . $gTipo['id_tipo'], $con);

    $quant = mysqli_num_rows($sqlGtipo);
    if ($quant > 0) {
        $tipoLabel = $gTipo['nome_tipo'];
        if (!empty($gTipo['nome_comp'])) {
            $tipoLabel .= ' - ' . $gTipo['nome_comp'];
        }
        $tipoLabels[] = $tipoLabel;
        $tipoQuantidades[] = $quant;
        $tipoIndex++;
    }
}

// Dados para gráfico de setores
$gSqlSetor = sql("SELECT * FROM setor", $con);
$setorLabels = [];
$setorQuantidades = [];

while ($gSetor = mysqli_fetch_assoc($gSqlSetor)) {
    $sqlGsetor = sql("SELECT * FROM kaizen
                      WHERE id_kaizen > 0
                      " . $filtro . " AND setor_kaizen = " . $gSetor['id_setor'], $con);

    $quant = mysqli_num_rows($sqlGsetor);
    if ($quant > 0) {
        $setorLabels[] = $gSetor['nome_setor'];
        $setorQuantidades[] = $quant;
    }
}

// Dados para gráfico de status
$sqlStatus = [];
$sqlStatus[1] = sql("SELECT * FROM kaizen WHERE id_kaizen > 0 " . $filtro . " AND status_kaizen = 1", $con);
$sqlStatus[2] = sql("SELECT * FROM kaizen WHERE id_kaizen > 0 " . $filtro . " AND status_kaizen = 2", $con);
$sqlStatus[3] = sql("SELECT * FROM kaizen WHERE id_kaizen > 0 " . $filtro . " AND status_kaizen = 3", $con);

$statusQuantidades = [
    mysqli_num_rows($sqlStatus[1]),
    mysqli_num_rows($sqlStatus[2]),
    mysqli_num_rows($sqlStatus[3])
];

// Top 10 colaboradores (por quantidade de kaizens)
$sqlTopColab = sql("SELECT colaborador.nome_colaborador, COUNT(*) as total, SUM(pontos_kaizen) as pontos_total
                    FROM kaizen
                    LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
                    WHERE id_kaizen > 0 " . $filtro . "
                    GROUP BY colaborador_kaizen
                    ORDER BY total DESC
                    LIMIT 10", $con);

$topColabNomes = [];
$topColabQuantidades = [];
while ($topColab = mysqli_fetch_assoc($sqlTopColab)) {
    $topColabNomes[] = $topColab['nome_colaborador'];
    $topColabQuantidades[] = $topColab['total'];
}

// Kaizens por mês (últimos 12 meses)
$sqlMensal = sql("SELECT 
                  DATE_FORMAT(datacadastro_kaizen, '%Y-%m') as mes,
                  COUNT(*) as total
                  FROM kaizen
                  WHERE id_kaizen > 0 " . $filtro . "
                  AND datacadastro_kaizen >= DATE_SUB(NOW(), INTERVAL 12 MONTH)
                  GROUP BY mes
                  ORDER BY mes ASC", $con);

$mesesLabels = [];
$mesesQuantidades = [];
while ($mensal = mysqli_fetch_assoc($sqlMensal)) {
    $data = explode('-', $mensal['mes']);
    $meses = ['Jan', 'Fev', 'Mar', 'Abr', 'Mai', 'Jun', 'Jul', 'Ago', 'Set', 'Out', 'Nov', 'Dez'];
    $mesesLabels[] = $meses[intval($data[1]) - 1] . '/' . $data[0];
    $mesesQuantidades[] = $mensal['total'];
}

// Benefícios em R$ por tipo (v2.1 - Novos gráficos)
$sqlBenefReais = sql("SELECT tipo.nome_tipo, comp.nome_comp,
                      SUM(CASE WHEN tipo_complemento_kaizen = 1 THEN valor_original_kaizen WHEN tipo_complemento_kaizen IN (2,3) THEN complemento_kaizen ELSE 0 END) as total_reais
                      FROM kaizen
                      LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
                      LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
                      WHERE id_kaizen > 0 " . $filtro . "
                      AND tipo_complemento_kaizen IN (1,2,3)
                      GROUP BY tipo_kaizen
                      HAVING total_reais > 0
                      ORDER BY total_reais DESC", $con);

$benefReaisLabels = [];
$benefReaisValores = [];
while ($benefReais = mysqli_fetch_assoc($sqlBenefReais)) {
    $label = $benefReais['nome_tipo'];
    if (!empty($benefReais['nome_comp'])) {
        $label .= ' - ' . $benefReais['nome_comp'];
    }
    $benefReaisLabels[] = $label;
    $benefReaisValores[] = round($benefReais['total_reais'], 2);
}

// Horas economizadas por tipo (v2.1)
$sqlBenefHoras = sql("SELECT tipo.nome_tipo, comp.nome_comp,
                      SUM(CASE WHEN tipo_complemento_kaizen = 3 THEN valor_original_kaizen ELSE 0 END) as total_horas
                      FROM kaizen
                      LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
                      LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo
                      WHERE id_kaizen > 0 " . $filtro . "
                      AND tipo_complemento_kaizen = 3
                      GROUP BY tipo_kaizen
                      HAVING total_horas > 0
                      ORDER BY total_horas DESC", $con);

$benefHorasLabels = [];
$benefHorasValores = [];
while ($benefHoras = mysqli_fetch_assoc($sqlBenefHoras)) {
    $label = $benefHoras['nome_tipo'];
    if (!empty($benefHoras['nome_comp'])) {
        $label .= ' - ' . $benefHoras['nome_comp'];
    }
    $benefHorasLabels[] = $label;
    $benefHorasValores[] = round($benefHoras['total_horas'], 2);
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<!-- InstanceBegin template="/Templates/admin-bs5.dwt.php" -->
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!-- InstanceBeginEditable name="doctitle" -->
<title>Relatórios de Kaizens - Sistema Orion</title>
<!-- InstanceEndEditable -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.7/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.bootstrap5.min.css" rel="stylesheet">
<link href="../lib/css/admin-orion.css" rel="stylesheet">
<link href="assets/css/kaizen-busca-print.css" rel="stylesheet" media="print">
<!-- InstanceBeginEditable name="head" -->
<style>
.admin-header .logo {
    color: white;
    font-size: 1.3rem;
    font-weight: 700;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0 1rem;
}

.admin-header .logo img {
    height: 35px;
}

.sidebar-toggle {
    background: none;
    border: none;
    color: white;
    font-size: 1.3rem;
    cursor: pointer;
    padding: 0.5rem;
    margin-right: 1rem;
}

.sidebar-toggle:hover {
    opacity: 0.8;
}

.header-right .btn-logout {
    background: rgba(255, 255, 255, 0.2);
    border: none;
    color: white;
    padding: 0.5rem 1rem;
    border-radius: 0.5rem;
    text-decoration: none;
    display: flex;
    align-items: center;
    gap: 0.5rem;
    font-size: 0.9rem;
    transition: all 0.3s ease;
}

.header-right .btn-logout:hover {
    background: rgba(255, 255, 255, 0.3);
}

.filter-badge {
    display: inline-block;
    background: var(--orion-primary);
    color: white;
    padding: 6px 12px;
    border-radius: 20px;
    font-size: 0.9rem;
    margin-right: 8px;
    margin-bottom: 8px;
}

.chart-container {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    margin-bottom: 25px;
    position: relative;
    height: 520px;
}

/* Tooltip para botão PDF */
.btn[title] {
    position: relative;
}

.chart-title {
    font-size: 1.2rem;
    font-weight: 600;
    color: #333;
    margin-bottom: 20px;
    padding-bottom: 12px;
    border-bottom: 2px solid var(--orion-secondary);
}

.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card-report {
    background: white;
    border-radius: 12px;
    padding: 25px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    border-left: 4px solid var(--orion-primary);
    transition: transform 0.3s, box-shadow 0.3s;
}

.stat-card-report:hover {
    transform: translateY(-5px);
    box-shadow: 0 4px 16px rgba(0,0,0,0.12);
}

.stat-card-report.success {
    border-left-color: var(--orion-success);
}

.stat-card-report.warning {
    border-left-color: #ffc107;
}

.stat-card-report.info {
    border-left-color: var(--orion-primary);
}

.stat-card-report.secondary {
    border-left-color: var(--orion-secondary);
}

.stat-icon {
    font-size: 2.5rem;
    margin-bottom: 15px;
    opacity: 0.8;
}

.stat-value {
    font-size: 2rem;
    font-weight: 700;
    color: #333;
    margin-bottom: 5px;
}

.stat-label {
    font-size: 0.95rem;
    color: #666;
    text-transform: uppercase;
    letter-spacing: 0.5px;
}

.charts-row {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(500px, 1fr));
    gap: 25px;
    margin-bottom: 30px;
}

@media (max-width: 768px) {
    .charts-row {
        grid-template-columns: 1fr;
    }
    
    .chart-container {
        height: 400px;
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
                <h1><i class="fas fa-chart-bar"></i> Relatórios de Kaizens</h1>
                <p class="text-muted mb-2">Visualize estatísticas e filtre os dados</p>
                <small class="text-info d-block mb-3">
                    <i class="fas fa-info-circle"></i> 
                    <strong>Dica:</strong> Ao clicar em "Exportar PDF", selecione "Salvar como PDF" na janela de impressão para gerar o arquivo
                </small>
            </div>
            <div class="d-flex gap-2 flex-wrap">
                <button onclick="window.print()" class="btn btn-primary no-print">
                    <i class="fas fa-print"></i> Imprimir
                </button>
                <a href="kaizen-filtrar.php" class="btn btn-orion-secondary no-print">
                    <i class="fas fa-filter"></i> Filtrar
                </a>
                <?php if (($_SESSION['tipo_kaizen'] ?? false) or ($_SESSION['status_kaizen'] ?? false) or ($_SESSION['colaborador_kaizen'] ?? false) or ($_SESSION['lider_kaizen'] ?? false) or ($_SESSION['unidade_kaizen'] ?? false) or ($_SESSION['setor_kaizen'] ?? false) or ($_SESSION['data_cadastro_ini'] ?? false) or ($_SESSION['data_cadastro_fim'] ?? false)) { ?>
                <a href="kaizen-busca.php?clear=1" class="btn btn-danger no-print">
                    <i class="fas fa-times"></i> Limpar Filtros
                </a>
                <?php } ?>
                <a href="kaizen-exportar.php" class="btn btn-success no-print">
                    <i class="fas fa-file-excel"></i> Exportar XLS
                </a>
                <button onclick="exportarPDF()" class="btn btn-danger no-print" title="Usar 'Salvar como PDF' ou 'Microsoft Print to PDF' na janela de impressão">
                    <i class="fas fa-file-pdf"></i> Exportar PDF
                </button>
            </div>
        </div>

        <!-- Filtros Ativos -->
        <?php if (($_SESSION['tipo_kaizen'] ?? false) or ($_SESSION['status_kaizen'] ?? false) or ($_SESSION['colaborador_kaizen'] ?? false) or ($_SESSION['lider_kaizen'] ?? false) or ($_SESSION['unidade_kaizen'] ?? false) or ($_SESSION['setor_kaizen'] ?? false) or ($_SESSION['data_cadastro_ini'] ?? false) or ($_SESSION['data_cadastro_fim'] ?? false)) { ?>
        <div class="alert alert-info mb-4">
            <strong><i class="fas fa-filter"></i> Filtros Ativos:</strong><br>
            <div class="mt-2">
                <?php
                if ($_SESSION['tipo_kaizen'] ?? false) {
                    $sqlTipoAtivo = sql("SELECT tipo.nome_tipo, comp.nome_comp FROM tipo LEFT JOIN comp ON comp.id_comp = tipo.complemento_tipo WHERE id_tipo = " . $_SESSION['tipo_kaizen'], $con);
                    $tipoAtivo = mysqli_fetch_array($sqlTipoAtivo);
                    echo '<span class="filter-badge">Tipo: ' . $tipoAtivo['nome_tipo'];
                    if (!empty($tipoAtivo['nome_comp'])) echo ' - ' . $tipoAtivo['nome_comp'];
                    echo '</span>';
                }
                if ($_SESSION['status_kaizen'] ?? false) {
                    $statusNomes = [1 => 'Aguardando análise', 2 => 'Aprovado', 3 => 'Recusado'];
                    echo '<span class="filter-badge">Status: ' . $statusNomes[$_SESSION['status_kaizen']] . '</span>';
                }
                if ($_SESSION['colaborador_kaizen'] ?? false) {
                    $sqlColabAtivo = sql("SELECT nome_colaborador FROM colaborador WHERE id_colaborador = " . $_SESSION['colaborador_kaizen'], $con);
                    $colabAtivo = mysqli_fetch_array($sqlColabAtivo);
                    echo '<span class="filter-badge">Colaborador: ' . $colabAtivo['nome_colaborador'] . '</span>';
                }
                if ($_SESSION['unidade_kaizen'] ?? false) {
                    $sqlUnidadeAtiva = sql("SELECT nome_unidade FROM unidade WHERE id_unidade = " . $_SESSION['unidade_kaizen'], $con);
                    $unidadeAtiva = mysqli_fetch_array($sqlUnidadeAtiva);
                    echo '<span class="filter-badge">Unidade: ' . $unidadeAtiva['nome_unidade'] . '</span>';
                }
                if ($_SESSION['setor_kaizen'] ?? false) {
                    $sqlSetorAtivo = sql("SELECT nome_setor FROM setor WHERE id_setor = " . $_SESSION['setor_kaizen'], $con);
                    $setorAtivo = mysqli_fetch_array($sqlSetorAtivo);
                    echo '<span class="filter-badge">Setor: ' . $setorAtivo['nome_setor'] . '</span>';
                }
                if (($_SESSION['data_cadastro_ini'] ?? false) or ($_SESSION['data_cadastro_fim'] ?? false)) {
                    echo '<span class="filter-badge">Período: ';
                    if ($_SESSION['data_cadastro_ini'] ?? false) echo $_SESSION['data_cadastro_ini'];
                    echo ' até ';
                    if ($_SESSION['data_cadastro_fim'] ?? false) echo $_SESSION['data_cadastro_fim'];
                    echo '</span>';
                }
                ?>
            </div>
        </div>
        <?php } ?>

        <!-- Cards de Estatísticas -->
        <div class="stats-grid">
            <div class="stat-card-report info">
                <div class="stat-icon text-primary">
                    <i class="fas fa-lightbulb"></i>
                </div>
                <div class="stat-value"><?php echo number_format($gTotal, 0, ',', '.'); ?></div>
                <div class="stat-label">Total de Kaizens</div>
            </div>

            <div class="stat-card-report success">
                <div class="stat-icon text-success">
                    <i class="fas fa-dollar-sign"></i>
                </div>
                <div class="stat-value">R$ <?php echo number_format($soma['beneficio_reais'] ?? 0, 2, ',', '.'); ?></div>
                <div class="stat-label">Benefício Total Anual (R$)</div>
            </div>

            <div class="stat-card-report" style="border-left-color: #667eea;">
                <div class="stat-icon" style="color: #667eea;">
                    <i class="fas fa-clock"></i>
                </div>
                <div class="stat-value"><?php echo number_format($soma['beneficio_horas'] ?? 0, 1, ',', '.'); ?> h</div>
                <div class="stat-label">Horas Economizadas</div>
            </div>

            <div class="stat-card-report warning">
                <div class="stat-icon text-warning">
                    <i class="fas fa-star"></i>
                </div>
                <div class="stat-value"><?php echo number_format($soma['pontos_total'] ?? 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Pontos Concedidos</div>
            </div>

            <div class="stat-card-report secondary">
                <div class="stat-icon" style="color: var(--orion-secondary);">
                    <i class="fas fa-exclamation-triangle"></i>
                </div>
                <div class="stat-value"><?php echo number_format($soma['redincidente'] ?? 0, 0, ',', '.'); ?></div>
                <div class="stat-label">Incidentes Evitados</div>
            </div>
        </div>

        <!-- Gráficos - Linha 1 -->
        <div class="charts-row">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Kaizens por Tipo de Benefício</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartTipos"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-pie"></i> Kaizens por Status</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartStatus"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráficos - Linha 2 -->
        <div class="charts-row">
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-bar"></i> Kaizens por Setor</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartSetores"></canvas>
                </div>
            </div>

            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-chart-line"></i> Evolução Mensal (Últimos 12 Meses)</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartMensal"></canvas>
                </div>
            </div>
        </div>

        <!-- Gráfico - Linha 3 (Full Width) -->
        <div class="chart-container" style="height: 520px;">
            <div class="chart-title"><i class="fas fa-trophy"></i> Top 10 Colaboradores (Quantidade de Kaizens)</div>
            <div style="height: 380px; position: relative;">
                <canvas id="chartTopColaboradores"></canvas>
            </div>
        </div>

        <!-- Gráficos - Linha 4 (Benefícios - v2.1) -->
        <?php if (!empty($benefReaisLabels) || !empty($benefHorasLabels)) { ?>
        <div class="charts-row">
            <?php if (!empty($benefReaisLabels)) { ?>
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-money-bill-wave"></i> Benefícios em R$ por Tipo</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartBenefReais"></canvas>
                </div>
            </div>
            <?php } ?>

            <?php if (!empty($benefHorasLabels)) { ?>
            <div class="chart-container">
                <div class="chart-title"><i class="fas fa-hourglass-half"></i> Horas Economizadas por Tipo</div>
                <div style="height: 380px; position: relative;">
                    <canvas id="chartBenefHoras"></canvas>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php } ?>

        <!-- Tabela de Dados -->
        <div class="admin-card mt-4">
            <div class="admin-card-header">
                <h5><i class="fas fa-table"></i> Listagem Detalhada</h5>
            </div>
            <div class="admin-card-body">
                <table class="table table-orion table-hover" id="tableKaizens">
                    <thead>
                        <tr>
                            <th width="8%">Número</th>
                            <th width="18%">Tipo</th>
                            <th width="12%">Status</th>
                            <th width="15%">Setor</th>
                            <th width="12%">Data</th>
                            <th width="18%">Colaborador</th>
                            <th width="10%">Benefício</th>
                            <th width="7%">Ação</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        mysqli_data_seek($sql, 0); // Reset pointer
                        while ($dados = mysqli_fetch_assoc($sql)) {
                        ?>
                        <tr>
                            <td><strong>#<?php echo $dados['id_kaizen']; ?></strong></td>
                            <td>
                                <?php 
                                echo $dados['nome_tipo']; 
                                if (!empty($dados['nome_comp'])) { 
                                    echo '<br><small class="text-muted">' . $dados['nome_comp'] . '</small>'; 
                                } 
                                ?>
                            </td>
                            <td><?php $status = $dados['status_kaizen']; include("../_status.php"); ?></td>
                            <td><?php echo $dados['nome_setor']; ?></td>
                            <td>
                                <?php echo date('d/m/Y', strtotime($dados['datacadastro_kaizen'])); ?>
                                <?php if (!empty($dados['dataconclusao_kaizen']) && $dados['dataconclusao_kaizen'] != '0000-00-00') { ?>
                                <br><small class="text-muted">Concluído: <?php echo date('d/m/Y', strtotime($dados['dataconclusao_kaizen'])); ?></small>
                                <?php } ?>
                            </td>
                            <td><?php echo $dados['nome_colaborador']; ?></td>
                            <td>
                                <?php if (!empty($dados['valor_original_kaizen']) && $dados['valor_original_kaizen'] > 0 && !empty($dados['tipo_complemento_kaizen'])) { ?>
                                <strong class="text-success"><?= formatarValorComplemento($dados['valor_original_kaizen'], $dados['tipo_complemento_kaizen']) ?></strong>
                                <?php } elseif($dados['custo_kaizen'] > 0) { ?>
                                <strong class="text-success">R$ <?php echo number_format($dados['custo_kaizen'], 2, ',', '.'); ?></strong>
                                <?php } else { ?>
                                <span class="text-muted">-</span>
                                <?php } ?>
                            </td>
                            <td>
                                <a href="kaizen-editar.php?kaizen=<?php echo $dados['id_kaizen']; ?>" class="btn btn-sm btn-warning">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- InstanceEndEditable -->
    </main>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.7/js/dataTables.bootstrap5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.bootstrap5.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script src="../lib/js/admin-base.js"></script>
<!-- InstanceBeginEditable name="scripts" -->
<script>
$(document).ready(function() {
    // DataTable
    $('#tableKaizens').DataTable({
        language: {
            url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/pt-BR.json'
        },
        order: [[4, 'desc']], // Ordenar por data
        pageLength: 25,
        dom: 'Bfrtip',
        buttons: [
            {
                extend: 'excel',
                text: '<i class="fas fa-file-excel"></i> Excel',
                className: 'btn btn-success btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'pdf',
                text: '<i class="fas fa-file-pdf"></i> PDF',
                className: 'btn btn-danger btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            },
            {
                extend: 'print',
                text: '<i class="fas fa-print"></i> Imprimir',
                className: 'btn btn-primary btn-sm',
                exportOptions: {
                    columns: [0, 1, 2, 3, 4, 5, 6]
                }
            }
        ]
    });

    // Chart.js - Configurações globais
    Chart.defaults.font.family = 'Inter, sans-serif';
    Chart.defaults.color = '#565656';

    // Gráfico 1: Tipos (Pizza)
    const ctxTipos = document.getElementById('chartTipos').getContext('2d');
    new Chart(ctxTipos, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($tipoLabels); ?>,
            datasets: [{
                data: <?php echo json_encode($tipoQuantidades); ?>,
                backgroundColor: [
                    '#009ee3', '#fcd700', '#00812e', '#e74c3c', '#9b59b6', 
                    '#3498db', '#e67e22', '#1abc9c', '#34495e'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 12,
                        font: {
                            size: 11
                        },
                        boxWidth: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percent = ((value / total) * 100).toFixed(1);
                            return label + ': ' + value + ' (' + percent + '%)';
                        }
                    }
                }
            }
        }
    });

    // Gráfico 2: Status (Pizza)
    const ctxStatus = document.getElementById('chartStatus').getContext('2d');
    new Chart(ctxStatus, {
        type: 'pie',
        data: {
            labels: ['Aguardando Análise', 'Aprovado', 'Recusado'],
            datasets: [{
                data: <?php echo json_encode($statusQuantidades); ?>,
                backgroundColor: ['#ffc107', '#00812e', '#e74c3c'],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 12,
                        font: {
                            size: 11
                        },
                        boxWidth: 12
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percent = total > 0 ? ((value / total) * 100).toFixed(1) : 0;
                            return label + ': ' + value + ' (' + percent + '%)';
                        }
                    }
                }
            }
        }
    });

    // Gráfico 3: Setores (Barras Horizontais)
    const ctxSetores = document.getElementById('chartSetores').getContext('2d');
    new Chart(ctxSetores, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($setorLabels); ?>,
            datasets: [{
                label: 'Quantidade de Kaizens',
                data: <?php echo json_encode($setorQuantidades); ?>,
                backgroundColor: '#009ee3',
                borderColor: '#0088c7',
                borderWidth: 1
            }]
        },
        options: {
            indexAxis: 'y',
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Kaizens: ' + context.parsed.x;
                        }
                    }
                }
            },
            scales: {
                x: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico 4: Evolução Mensal (Linha)
    const ctxMensal = document.getElementById('chartMensal').getContext('2d');
    new Chart(ctxMensal, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($mesesLabels); ?>,
            datasets: [{
                label: 'Kaizens Cadastrados',
                data: <?php echo json_encode($mesesQuantidades); ?>,
                borderColor: '#009ee3',
                backgroundColor: 'rgba(0, 158, 227, 0.1)',
                borderWidth: 3,
                fill: true,
                tension: 0.4,
                pointRadius: 5,
                pointHoverRadius: 7,
                pointBackgroundColor: '#009ee3',
                pointBorderColor: '#fff',
                pointBorderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    mode: 'index',
                    intersect: false
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });

    // Gráfico 5: Top Colaboradores (Barras Verticais)
    const ctxTopColab = document.getElementById('chartTopColaboradores').getContext('2d');
    new Chart(ctxTopColab, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($topColabNomes); ?>,
            datasets: [{
                label: 'Quantidade de Kaizens',
                data: <?php echo json_encode($topColabQuantidades); ?>,
                backgroundColor: [
                    '#fcd700', // 1º lugar - ouro
                    '#c0c0c0', // 2º lugar - prata
                    '#cd7f32', // 3º lugar - bronze
                    '#009ee3', '#009ee3', '#009ee3', '#009ee3', '#009ee3', '#009ee3', '#009ee3'
                ],
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Kaizens: ' + context.parsed.y;
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });

    // Gráfico 6: Benefícios em R$ por Tipo (v2.1)
    <?php if (!empty($benefReaisLabels)) { ?>
    const ctxBenefReais = document.getElementById('chartBenefReais').getContext('2d');
    new Chart(ctxBenefReais, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($benefReaisLabels); ?>,
            datasets: [{
                label: 'Benefício em R$',
                data: <?php echo json_encode($benefReaisValores); ?>,
                backgroundColor: '#00812e',
                borderColor: '#006622',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'R$ ' + context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 2, maximumFractionDigits: 2});
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return 'R$ ' + value.toLocaleString('pt-BR');
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
    <?php } ?>

    // Gráfico 7: Horas Economizadas por Tipo (v2.1)
    <?php if (!empty($benefHorasLabels)) { ?>
    const ctxBenefHoras = document.getElementById('chartBenefHoras').getContext('2d');
    new Chart(ctxBenefHoras, {
        type: 'bar',
        data: {
            labels: <?php echo json_encode($benefHorasLabels); ?>,
            datasets: [{
                label: 'Horas Economizadas',
                data: <?php echo json_encode($benefHorasValores); ?>,
                backgroundColor: '#667eea',
                borderColor: '#5568d3',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.parsed.y.toLocaleString('pt-BR', {minimumFractionDigits: 1, maximumFractionDigits: 1}) + ' horas';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        callback: function(value) {
                            return value.toLocaleString('pt-BR') + ' h';
                        }
                    }
                },
                x: {
                    ticks: {
                        autoSkip: false,
                        maxRotation: 45,
                        minRotation: 45
                    }
                }
            }
        }
    });
    <?php } ?>
});

// Função para exportar PDF
function exportarPDF() {
    // Verificar se há suporte para beforeprint
    if (window.matchMedia) {
        const mediaQueryList = window.matchMedia('print');
        
        // Adicionar listener temporário
        function handlePrintStart() {
            document.title = 'Relatorio_Kaizens_' + new Date().toISOString().split('T')[0];
        }
        
        mediaQueryList.addListener(handlePrintStart);
        
        // Abrir janela de impressão
        window.print();
        
        // Remover listener após impressão
        setTimeout(() => {
            mediaQueryList.removeListener(handlePrintStart);
            document.title = 'Relatórios de Kaizens - Sistema Orion';
        }, 1000);
    } else {
        window.print();
    }
}
</script>
<!-- InstanceEndEditable -->
</body>
</html>
