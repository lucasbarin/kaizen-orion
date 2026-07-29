<?php
/**
 * ============================================================================
 * SCRIPT DE CONTINUAÇÃO DE MIGRAÇÃO V1 → V2
 * ============================================================================
 * 
 * Este script continua a migração de onde parou (logtroca, produto, lidersetor)
 * 
 * @author Script gerado por GitHub Copilot
 * @date 2026-01-28
 */

// ============================================================================
// CONFIGURAÇÃO
// ============================================================================

// Banco V1 (ORIGEM)
$v1_host = 'orionkaizen.mysql.dbaas.com.br';
$v1_user = 'orionkaizen';
$v1_pass = 'sdgbj@CGH5bhj_';
$v1_db   = 'orionkaizen';

// Banco V2 (DESTINO)
$v2_host = 'orionv226.mysql.dbaas.com.br';
$v2_user = 'orionv226';
$v2_pass = 'frn6XjyY#zCdMV';
$v2_db   = 'orionv226';

// ============================================================================
// FUNÇÕES
// ============================================================================

function log_msg($msg, $type = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    echo "[$timestamp] [$type] $msg\n";
}

// ============================================================================
// CONEXÕES
// ============================================================================

echo "\n=== CONTINUAÇÃO DA MIGRAÇÃO ===\n\n";

log_msg("Conectando ao V1...");
$v1 = mysqli_connect($v1_host, $v1_user, $v1_pass, $v1_db);
if (!$v1) die("Erro V1: " . mysqli_connect_error());
mysqli_set_charset($v1, 'utf8mb4');
log_msg("V1 conectado!", 'OK');

log_msg("Conectando ao V2...");
$v2 = mysqli_connect($v2_host, $v2_user, $v2_pass, $v2_db);
if (!$v2) die("Erro V2: " . mysqli_connect_error());
mysqli_set_charset($v2, 'utf8mb4');
log_msg("V2 conectado!", 'OK');

// ============================================================================
// VERIFICAR STATUS ATUAL
// ============================================================================

echo "\n=== STATUS ATUAL DO V2 ===\n\n";

$tabelas = ['colaborador', 'kaizen', 'log', 'logtroca', 'produto', 'lidersetor'];
foreach ($tabelas as $t) {
    $r = mysqli_query($v2, "SELECT COUNT(*) as c FROM $t");
    $row = mysqli_fetch_assoc($r);
    log_msg("$t: {$row['c']} registros", $row['c'] > 0 ? 'OK' : 'WARN');
}

// ============================================================================
// MIGRAR LOGTROCA
// ============================================================================

echo "\n=== MIGRANDO LOGTROCA ===\n\n";

// Verificar se já tem dados
$r = mysqli_query($v2, "SELECT COUNT(*) as c FROM logtroca");
$row = mysqli_fetch_assoc($r);
if ($row['c'] > 0) {
    log_msg("logtroca já tem {$row['c']} registros - pulando", 'WARN');
} else {
    $result = mysqli_query($v1, "SELECT * FROM logtroca ORDER BY id_logtroca");
    $total = mysqli_num_rows($result);
    $count = 0;
    
    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['id_logtroca'];
        $modificacao = mysqli_real_escape_string($v2, $row['modificacao_logtroca']);
        $colaborador = (int)$row['id_colaborador'];
        $produto = (int)$row['id_produto'];
        $nome = mysqli_real_escape_string($v2, $row['nome_logtroca']);
        $ponto = (int)$row['ponto_logtroca'];
        $data = mysqli_real_escape_string($v2, $row['data_logtroca']);
        $status = (int)$row['status_logtroca'];
        
        $sql = "INSERT INTO logtroca (id_logtroca, modificacao_logtroca, id_colaborador, 
                id_produto, nome_logtroca, ponto_logtroca, data_logtroca, status_logtroca) 
                VALUES ($id, '$modificacao', $colaborador, $produto, '$nome', $ponto, '$data', $status)";
        
        if (!mysqli_query($v2, $sql)) {
            log_msg("Erro logtroca #$id: " . mysqli_error($v2), 'ERROR');
        }
        $count++;
    }
    log_msg("Logtrocas migrados: $count de $total", 'OK');
}

// ============================================================================
// MIGRAR PRODUTO
// ============================================================================

echo "\n=== MIGRANDO PRODUTO ===\n\n";

$r = mysqli_query($v2, "SELECT COUNT(*) as c FROM produto");
$row = mysqli_fetch_assoc($r);
if ($row['c'] > 0) {
    log_msg("produto já tem {$row['c']} registros - pulando", 'WARN');
} else {
    $result = mysqli_query($v1, "SELECT * FROM produto ORDER BY id_produto");
    $total = mysqli_num_rows($result);
    $count = 0;
    
    while ($row = mysqli_fetch_assoc($result)) {
        $id = (int)$row['id_produto'];
        $nome = mysqli_real_escape_string($v2, $row['nome_produto']);
        $catproduto = (int)$row['catproduto_produto'];
        $numero = (int)$row['numero_produto'];
        $pontos = (int)$row['pontos_produto'];
        $imagem1 = mysqli_real_escape_string($v2, $row['imagem1_produto']);
        $site = mysqli_real_escape_string($v2, $row['site_produto']);
        $texto1 = mysqli_real_escape_string($v2, $row['texto1_produto']);
        $status = (int)$row['status_produto'];
        
        $sql = "INSERT INTO produto (id_produto, nome_produto, catproduto_produto, numero_produto, 
                pontos_produto, imagem1_produto, site_produto, texto1_produto, status_produto) 
                VALUES ($id, '$nome', $catproduto, $numero, $pontos, '$imagem1', '$site', '$texto1', $status)";
        
        if (!mysqli_query($v2, $sql)) {
            log_msg("Erro produto #$id: " . mysqli_error($v2), 'ERROR');
        }
        $count++;
    }
    log_msg("Produtos migrados: $count de $total", 'OK');
}

// ============================================================================
// ATUALIZAR AUTO_INCREMENT
// ============================================================================

echo "\n=== ATUALIZANDO AUTO_INCREMENT ===\n\n";

$tabelas_autoincrement = [
    'colaborador' => 'id_colaborador',
    'kaizen' => 'id_kaizen',
    'log' => 'id_log',
    'logtroca' => 'id_logtroca',
    'produto' => 'id_produto',
    'lidersetor' => 'id_lidersetor'
];

foreach ($tabelas_autoincrement as $tabela => $campo) {
    $result = mysqli_query($v2, "SELECT MAX($campo) as max_id FROM $tabela");
    $row = mysqli_fetch_assoc($result);
    $max_id = (int)$row['max_id'] + 1;
    
    $sql = "ALTER TABLE `$tabela` AUTO_INCREMENT = $max_id";
    if (mysqli_query($v2, $sql)) {
        log_msg("$tabela AUTO_INCREMENT = $max_id", 'OK');
    } else {
        log_msg("Erro $tabela: " . mysqli_error($v2), 'ERROR');
    }
}

// ============================================================================
// VERIFICAÇÃO FINAL
// ============================================================================

echo "\n=== VERIFICAÇÃO FINAL ===\n\n";

foreach (['colaborador', 'kaizen', 'log', 'logtroca', 'produto'] as $t) {
    $r1 = mysqli_query($v1, "SELECT COUNT(*) as c FROM $t");
    $r2 = mysqli_query($v2, "SELECT COUNT(*) as c FROM $t");
    $v1c = mysqli_fetch_assoc($r1)['c'];
    $v2c = mysqli_fetch_assoc($r2)['c'];
    
    $status = ($v1c == $v2c) ? 'OK' : 'WARN';
    log_msg("$t: V1=$v1c, V2=$v2c " . ($v1c == $v2c ? '✓' : '⚠'), $status);
}

mysqli_close($v1);
mysqli_close($v2);

echo "\n=== MIGRAÇÃO CONCLUÍDA! ===\n\n";
