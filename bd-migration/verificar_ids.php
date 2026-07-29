<?php
/**
 * Verificação de integridade de IDs - Migração V1 → V2
 */

// Conexões
$v1 = mysqli_connect('orionkaizen.mysql.dbaas.com.br', 'orionkaizen', 'sdgbj@CGH5bhj_', 'orionkaizen');
$v2 = mysqli_connect('orionv226.mysql.dbaas.com.br', 'orionv226', 'frn6XjyY#zCdMV', 'orionv226');

if (!$v1 || !$v2) die("Erro de conexão");

mysqli_set_charset($v1, 'utf8mb4');
mysqli_set_charset($v2, 'utf8mb4');

echo "\n╔══════════════════════════════════════════════════════════════════╗\n";
echo "║     VERIFICAÇÃO DE INTEGRIDADE DE IDs - V1 vs V2                 ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

// ============================================================================
// 1. VERIFICAR COLABORADOR - IDs e dados
// ============================================================================

echo "═══ COLABORADOR ═══\n\n";

// Pegar alguns IDs específicos para comparar
$ids_teste = [1, 101, 1038, 1080, 1186];

echo "Comparando IDs específicos:\n";
echo str_repeat("-", 80) . "\n";
printf("%-8s | %-30s | %-30s | %s\n", "ID", "NOME V1", "NOME V2", "STATUS");
echo str_repeat("-", 80) . "\n";

foreach ($ids_teste as $id) {
    $r1 = mysqli_query($v1, "SELECT id_colaborador, nome_colaborador FROM colaborador WHERE id_colaborador = $id");
    $r2 = mysqli_query($v2, "SELECT id_colaborador, nome_colaborador FROM colaborador WHERE id_colaborador = $id");
    
    $d1 = mysqli_fetch_assoc($r1);
    $d2 = mysqli_fetch_assoc($r2);
    
    $nome1 = $d1 ? substr($d1['nome_colaborador'], 0, 28) : '(não existe)';
    $nome2 = $d2 ? substr($d2['nome_colaborador'], 0, 28) : '(não existe)';
    $status = ($d1 && $d2 && $d1['nome_colaborador'] === $d2['nome_colaborador']) ? '✓ OK' : '✗ ERRO';
    
    printf("%-8s | %-30s | %-30s | %s\n", $id, $nome1, $nome2, $status);
}

// Verificar MIN e MAX IDs
$r1 = mysqli_query($v1, "SELECT MIN(id_colaborador) as min_id, MAX(id_colaborador) as max_id FROM colaborador");
$r2 = mysqli_query($v2, "SELECT MIN(id_colaborador) as min_id, MAX(id_colaborador) as max_id FROM colaborador");
$d1 = mysqli_fetch_assoc($r1);
$d2 = mysqli_fetch_assoc($r2);

echo "\nRange de IDs:\n";
echo "  V1: MIN={$d1['min_id']}, MAX={$d1['max_id']}\n";
echo "  V2: MIN={$d2['min_id']}, MAX={$d2['max_id']}\n";
echo "  Status: " . (($d1['min_id'] == $d2['min_id'] && $d1['max_id'] == $d2['max_id']) ? "✓ IDÊNTICO" : "✗ DIFERENTE") . "\n";

// ============================================================================
// 2. VERIFICAR KAIZEN - IDs e relacionamentos
// ============================================================================

echo "\n═══ KAIZEN ═══\n\n";

// Pegar alguns kaizens para comparar IDs e colaborador_kaizen (FK)
$ids_kaizen = [1, 100, 500, 1000, 1500];

echo "Comparando IDs e relacionamentos (colaborador_kaizen):\n";
echo str_repeat("-", 90) . "\n";
printf("%-8s | %-12s | %-12s | %-40s | %s\n", "ID", "COLAB V1", "COLAB V2", "RESULTADO V1 (trecho)", "STATUS");
echo str_repeat("-", 90) . "\n";

foreach ($ids_kaizen as $id) {
    $r1 = mysqli_query($v1, "SELECT id_kaizen, colaborador_kaizen, resultado_kaizen FROM kaizen WHERE id_kaizen = $id");
    $r2 = mysqli_query($v2, "SELECT id_kaizen, colaborador_kaizen, resultado_kaizen FROM kaizen WHERE id_kaizen = $id");
    
    $d1 = mysqli_fetch_assoc($r1);
    $d2 = mysqli_fetch_assoc($r2);
    
    if ($d1 && $d2) {
        $colab1 = $d1['colaborador_kaizen'];
        $colab2 = $d2['colaborador_kaizen'];
        $resultado = substr($d1['resultado_kaizen'], 0, 38);
        $status = ($colab1 == $colab2) ? '✓ OK' : '✗ ERRO';
        printf("%-8s | %-12s | %-12s | %-40s | %s\n", $id, $colab1, $colab2, $resultado, $status);
    } else {
        printf("%-8s | %-12s | %-12s | %-40s | %s\n", $id, 
            $d1 ? $d1['colaborador_kaizen'] : '-', 
            $d2 ? $d2['colaborador_kaizen'] : '-',
            '(registro não encontrado)',
            '⚠ VERIFICAR');
    }
}

// Verificar MIN e MAX IDs
$r1 = mysqli_query($v1, "SELECT MIN(id_kaizen) as min_id, MAX(id_kaizen) as max_id FROM kaizen");
$r2 = mysqli_query($v2, "SELECT MIN(id_kaizen) as min_id, MAX(id_kaizen) as max_id FROM kaizen");
$d1 = mysqli_fetch_assoc($r1);
$d2 = mysqli_fetch_assoc($r2);

echo "\nRange de IDs:\n";
echo "  V1: MIN={$d1['min_id']}, MAX={$d1['max_id']}\n";
echo "  V2: MIN={$d2['min_id']}, MAX={$d2['max_id']}\n";
echo "  Status: " . (($d1['min_id'] == $d2['min_id'] && $d1['max_id'] == $d2['max_id']) ? "✓ IDÊNTICO" : "✗ DIFERENTE") . "\n";

// ============================================================================
// 3. VERIFICAR LOG - IDs e relacionamento com usuário
// ============================================================================

echo "\n═══ LOG (auditoria de pontos) ═══\n\n";

$ids_log = [1, 100, 500, 1000, 2000];

echo "Comparando IDs e relacionamentos (usu_log → colaborador):\n";
echo str_repeat("-", 80) . "\n";
printf("%-8s | %-10s | %-10s | %-35s | %s\n", "ID", "USU V1", "USU V2", "DESCRIÇÃO", "STATUS");
echo str_repeat("-", 80) . "\n";

foreach ($ids_log as $id) {
    $r1 = mysqli_query($v1, "SELECT id_log, usu_log, descricao_log FROM log WHERE id_log = $id");
    $r2 = mysqli_query($v2, "SELECT id_log, usu_log, descricao_log FROM log WHERE id_log = $id");
    
    $d1 = mysqli_fetch_assoc($r1);
    $d2 = mysqli_fetch_assoc($r2);
    
    if ($d1 && $d2) {
        $usu1 = $d1['usu_log'];
        $usu2 = $d2['usu_log'];
        $desc = substr($d1['descricao_log'], 0, 33);
        $status = ($usu1 == $usu2 && $d1['descricao_log'] === $d2['descricao_log']) ? '✓ OK' : '✗ ERRO';
        printf("%-8s | %-10s | %-10s | %-35s | %s\n", $id, $usu1, $usu2, $desc, $status);
    }
}

// ============================================================================
// 4. VERIFICAR LOGTROCA - IDs e relacionamentos
// ============================================================================

echo "\n═══ LOGTROCA (trocas de pontos) ═══\n\n";

$ids_troca = [1, 50, 100, 150, 176];

echo "Comparando IDs e relacionamentos:\n";
echo str_repeat("-", 90) . "\n";
printf("%-6s | %-10s | %-10s | %-10s | %-10s | %-30s | %s\n", 
    "ID", "COLAB V1", "COLAB V2", "PROD V1", "PROD V2", "PRODUTO", "STATUS");
echo str_repeat("-", 90) . "\n";

foreach ($ids_troca as $id) {
    $r1 = mysqli_query($v1, "SELECT * FROM logtroca WHERE id_logtroca = $id");
    $r2 = mysqli_query($v2, "SELECT * FROM logtroca WHERE id_logtroca = $id");
    
    $d1 = mysqli_fetch_assoc($r1);
    $d2 = mysqli_fetch_assoc($r2);
    
    if ($d1 && $d2) {
        $status = ($d1['id_colaborador'] == $d2['id_colaborador'] && 
                   $d1['id_produto'] == $d2['id_produto']) ? '✓ OK' : '✗ ERRO';
        printf("%-6s | %-10s | %-10s | %-10s | %-10s | %-30s | %s\n", 
            $id, 
            $d1['id_colaborador'], $d2['id_colaborador'],
            $d1['id_produto'], $d2['id_produto'],
            substr($d1['nome_logtroca'], 0, 28),
            $status);
    }
}

// ============================================================================
// 5. VERIFICAÇÃO DE INTEGRIDADE REFERENCIAL
// ============================================================================

echo "\n═══ INTEGRIDADE REFERENCIAL NO V2 ═══\n\n";

// Kaizens apontando para colaboradores que existem
$r = mysqli_query($v2, "
    SELECT COUNT(*) as total FROM kaizen k 
    WHERE k.colaborador_kaizen > 0 
    AND NOT EXISTS (SELECT 1 FROM colaborador c WHERE c.id_colaborador = k.colaborador_kaizen)
");
$d = mysqli_fetch_assoc($r);
echo "Kaizens com colaborador_kaizen inválido: {$d['total']} " . ($d['total'] == 0 ? "✓" : "✗") . "\n";

// Logs apontando para colaboradores que existem
$r = mysqli_query($v2, "
    SELECT COUNT(*) as total FROM log l 
    WHERE l.usu_log > 0 
    AND NOT EXISTS (SELECT 1 FROM colaborador c WHERE c.id_colaborador = l.usu_log)
");
$d = mysqli_fetch_assoc($r);
echo "Logs com usu_log inválido: {$d['total']} " . ($d['total'] == 0 ? "✓" : "✗") . "\n";

// Logtrocas apontando para colaboradores que existem
$r = mysqli_query($v2, "
    SELECT COUNT(*) as total FROM logtroca lt 
    WHERE lt.id_colaborador > 0 
    AND NOT EXISTS (SELECT 1 FROM colaborador c WHERE c.id_colaborador = lt.id_colaborador)
");
$d = mysqli_fetch_assoc($r);
echo "Logtrocas com id_colaborador inválido: {$d['total']} " . ($d['total'] == 0 ? "✓" : "✗") . "\n";

// Logtrocas apontando para produtos que existem
$r = mysqli_query($v2, "
    SELECT COUNT(*) as total FROM logtroca lt 
    WHERE lt.id_produto > 0 
    AND NOT EXISTS (SELECT 1 FROM produto p WHERE p.id_produto = lt.id_produto)
");
$d = mysqli_fetch_assoc($r);
echo "Logtrocas com id_produto inválido: {$d['total']} " . ($d['total'] == 0 ? "✓" : "✗") . "\n";

// ============================================================================
// RESUMO FINAL
// ============================================================================

echo "\n╔══════════════════════════════════════════════════════════════════╗\n";
echo "║                    RESUMO DA VERIFICAÇÃO                         ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

$tabelas = [
    'colaborador' => 'id_colaborador',
    'kaizen' => 'id_kaizen', 
    'log' => 'id_log',
    'logtroca' => 'id_logtroca',
    'produto' => 'id_produto'
];

echo "Verificação de todos os IDs (comparação completa):\n\n";

foreach ($tabelas as $tabela => $campo) {
    // Contar registros com IDs diferentes
    $sql = "SELECT COUNT(*) as diff FROM (
        SELECT $campo FROM $tabela
    ) v2_ids WHERE $campo NOT IN (SELECT $campo FROM $tabela)";
    
    // Comparar todos os IDs
    $r1 = mysqli_query($v1, "SELECT GROUP_CONCAT($campo ORDER BY $campo) as ids FROM $tabela");
    $r2 = mysqli_query($v2, "SELECT GROUP_CONCAT($campo ORDER BY $campo) as ids FROM $tabela");
    
    // Para tabelas grandes, comparar apenas contagem e range
    $r1_count = mysqli_query($v1, "SELECT COUNT(*) as c, MIN($campo) as min_id, MAX($campo) as max_id FROM $tabela");
    $r2_count = mysqli_query($v2, "SELECT COUNT(*) as c, MIN($campo) as min_id, MAX($campo) as max_id FROM $tabela");
    
    $d1 = mysqli_fetch_assoc($r1_count);
    $d2 = mysqli_fetch_assoc($r2_count);
    
    $match = ($d1['c'] == $d2['c'] && $d1['min_id'] == $d2['min_id'] && $d1['max_id'] == $d2['max_id']);
    
    printf("%-12s: V1=%4d registros (IDs %d-%d) | V2=%4d registros (IDs %d-%d) | %s\n",
        $tabela,
        $d1['c'], $d1['min_id'] ?? 0, $d1['max_id'] ?? 0,
        $d2['c'], $d2['min_id'] ?? 0, $d2['max_id'] ?? 0,
        $match ? "✓ IDs PRESERVADOS" : "✗ VERIFICAR"
    );
}

mysqli_close($v1);
mysqli_close($v2);

echo "\n";
