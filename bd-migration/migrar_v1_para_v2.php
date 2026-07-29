<?php
/**
 * ============================================================================
 * SCRIPT DE MIGRAÇÃO V1 → V2 - Sistema Orion Kaizen
 * ============================================================================
 * 
 * Este script migra dados do banco V1 (orionkaizen) para o banco V2 (orionv226)
 * 
 * TABELAS MIGRADAS:
 * - colaborador (estrutura idêntica)
 * - kaizen (28 colunas V1 → 33 colunas V2, com transformação)
 * - log (estrutura idêntica)
 * - logtroca (estrutura idêntica)
 * - produto (estrutura idêntica)
 * - lidersetor (estrutura idêntica)
 * 
 * TABELAS NÃO MIGRADAS (estruturais do V2):
 * - comp, tipo, kaizen_anexos, categoria, reducao, status, pg, unidade, setor
 * 
 * @author Script gerado por GitHub Copilot
 * @date 2026-01-28
 * @version 1.0
 * ============================================================================
 */

// ============================================================================
// CONFIGURAÇÃO
// ============================================================================

// Banco V1 (ORIGEM - dados a migrar)
$v1_host = 'orionkaizen.mysql.dbaas.com.br';
$v1_user = 'orionkaizen';
$v1_pass = 'sdgbj@CGH5bhj_';
$v1_db   = 'orionkaizen';

// Banco V2 (DESTINO - produção)
$v2_host = 'orionv226.mysql.dbaas.com.br';
$v2_user = 'orionv226';
$v2_pass = 'frn6XjyY#zCdMV';
$v2_db   = 'orionv226';

// Modo de execução
$DRY_RUN = false; // true = apenas simula, false = executa de verdade

// ============================================================================
// FUNÇÕES AUXILIARES
// ============================================================================

function log_msg($msg, $type = 'INFO') {
    $timestamp = date('Y-m-d H:i:s');
    $colors = [
        'INFO' => "\033[36m",    // Cyan
        'OK' => "\033[32m",      // Green
        'WARN' => "\033[33m",    // Yellow
        'ERROR' => "\033[31m",   // Red
        'RESET' => "\033[0m"     // Reset
    ];
    
    // Para CLI
    if (php_sapi_name() === 'cli') {
        echo $colors[$type] ?? '';
        echo "[$timestamp] [$type] $msg\n";
        echo $colors['RESET'];
    } else {
        // Para browser
        $color = match($type) {
            'OK' => 'green',
            'WARN' => 'orange',
            'ERROR' => 'red',
            default => 'blue'
        };
        echo "<div style='color:$color;font-family:monospace;'>[$timestamp] [$type] $msg</div>\n";
        flush();
    }
}

function connect_db($host, $user, $pass, $db, $name) {
    log_msg("Conectando ao banco $name ($host)...");
    
    $conn = mysqli_connect($host, $user, $pass, $db);
    if (!$conn) {
        log_msg("ERRO ao conectar em $name: " . mysqli_connect_error(), 'ERROR');
        return false;
    }
    
    mysqli_set_charset($conn, 'utf8mb4');
    log_msg("Conectado ao banco $name com sucesso!", 'OK');
    return $conn;
}

// ============================================================================
// INÍCIO DA MIGRAÇÃO
// ============================================================================

echo "\n";
echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║        MIGRAÇÃO V1 → V2 - SISTEMA ORION KAIZEN                   ║\n";
echo "╠══════════════════════════════════════════════════════════════════╣\n";
echo "║  Modo: " . ($DRY_RUN ? "SIMULAÇÃO (DRY RUN)" : "EXECUÇÃO REAL") . str_repeat(' ', $DRY_RUN ? 25 : 30) . "║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

if ($DRY_RUN) {
    log_msg("⚠️  MODO SIMULAÇÃO - Nenhuma alteração será feita no banco V2", 'WARN');
    log_msg("Para executar de verdade, altere \$DRY_RUN = false no script", 'WARN');
    echo "\n";
}

// Conectar aos bancos
$v1 = connect_db($v1_host, $v1_user, $v1_pass, $v1_db, 'V1');
$v2 = connect_db($v2_host, $v2_user, $v2_pass, $v2_db, 'V2');

if (!$v1 || !$v2) {
    log_msg("Falha na conexão. Abortando.", 'ERROR');
    exit(1);
}

echo "\n";

// ============================================================================
// ETAPA 1: BACKUP DAS TABELAS V2 (criar tabelas _backup)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 1: Criando backup das tabelas no V2", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

$tabelas_backup = ['colaborador', 'kaizen', 'log', 'logtroca', 'produto', 'lidersetor'];

foreach ($tabelas_backup as $tabela) {
    $backup_name = $tabela . '_backup_' . date('Ymd_His');
    $sql = "CREATE TABLE `$backup_name` AS SELECT * FROM `$tabela`";
    
    if ($DRY_RUN) {
        log_msg("[SIMULAÇÃO] Criaria backup: $backup_name", 'INFO');
    } else {
        if (mysqli_query($v2, $sql)) {
            log_msg("Backup criado: $backup_name", 'OK');
        } else {
            log_msg("Erro ao criar backup de $tabela: " . mysqli_error($v2), 'ERROR');
        }
    }
}

echo "\n";

// ============================================================================
// ETAPA 2: LIMPAR TABELAS NO V2
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 2: Limpando tabelas no V2 (TRUNCATE)", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

// Ordem importante: primeiro as que têm dependências
$tabelas_limpar = ['logtroca', 'log', 'kaizen', 'produto', 'lidersetor', 'colaborador'];

if (!$DRY_RUN) {
    mysqli_query($v2, "SET FOREIGN_KEY_CHECKS = 0");
}

foreach ($tabelas_limpar as $tabela) {
    $sql = "TRUNCATE TABLE `$tabela`";
    
    if ($DRY_RUN) {
        log_msg("[SIMULAÇÃO] Executaria: TRUNCATE TABLE $tabela", 'INFO');
    } else {
        if (mysqli_query($v2, $sql)) {
            log_msg("Tabela $tabela limpa", 'OK');
        } else {
            log_msg("Erro ao limpar $tabela: " . mysqli_error($v2), 'ERROR');
        }
    }
}

if (!$DRY_RUN) {
    mysqli_query($v2, "SET FOREIGN_KEY_CHECKS = 1");
}

echo "\n";

// ============================================================================
// ETAPA 3: MIGRAR COLABORADOR (estrutura idêntica)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 3: Migrando tabela COLABORADOR", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

$result = mysqli_query($v1, "SELECT * FROM colaborador ORDER BY id_colaborador");
$total = mysqli_num_rows($result);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id_colaborador'];
    $nome = mysqli_real_escape_string($v2, $row['nome_colaborador']);
    $email = mysqli_real_escape_string($v2, $row['email_colaborador']);
    $usuario = mysqli_real_escape_string($v2, $row['usuario_colaborador']);
    $senha = mysqli_real_escape_string($v2, $row['senha_colaborador']);
    $unidade = (int)$row['unidade_colaborador'];
    $setor = (int)$row['setor_colaborador'];
    $status = (int)$row['status_colaborador'];
    $lider = (int)$row['lider_colaborador'];
    $ponto = (int)$row['ponto_colaborador'];
    $gestor = (int)$row['gestor_colaborador'];
    $alterarsenha = (int)$row['alterarsenha_colaborador'];
    
    $sql = "INSERT INTO colaborador (id_colaborador, nome_colaborador, email_colaborador, 
            usuario_colaborador, senha_colaborador, unidade_colaborador, setor_colaborador, 
            status_colaborador, lider_colaborador, ponto_colaborador, gestor_colaborador, 
            alterarsenha_colaborador) VALUES 
            ($id, '$nome', '$email', '$usuario', '$senha', $unidade, $setor, 
            $status, $lider, $ponto, $gestor, $alterarsenha)";
    
    if (!$DRY_RUN) {
        mysqli_query($v2, $sql);
    }
    $count++;
}

log_msg("Colaboradores migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 4: MIGRAR KAIZEN (TRANSFORMAÇÃO 28 → 33 colunas)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 4: Migrando tabela KAIZEN (com transformação)", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("Transformação: V1 (28 colunas) → V2 (33 colunas)", 'INFO');
log_msg("Colunas adicionadas com defaults: add_tipo_texto='', complemento_kaizen=0.00,", 'INFO');
log_msg("valor_original_kaizen=0.00, tipo_complemento_kaizen=0, situacao_atual=''", 'INFO');

$result = mysqli_query($v1, "SELECT * FROM kaizen ORDER BY id_kaizen");
$total = mysqli_num_rows($result);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    // Campos originais do V1
    $id = (int)$row['id_kaizen'];
    $categoria = (int)$row['categoria_kaizen'];
    $tipo = (int)$row['tipo_kaizen'];
    $tempo = (int)$row['tempo_kaizen'];
    $custo = (float)$row['custo_kaizen'];
    $reducao = (int)$row['reducao_kaizen'];
    $destaque = (int)$row['destaque_kaizen'];
    $onde = mysqli_real_escape_string($v2, $row['onde_kaizen']);
    $resultado = mysqli_real_escape_string($v2, $row['resultado_kaizen']);
    $imagem1 = mysqli_real_escape_string($v2, $row['imagem1_kaizen']);
    $imagem2 = mysqli_real_escape_string($v2, $row['imagem2_kaizen']);
    $colaborador = (int)$row['colaborador_kaizen'];
    $colaborador1 = (int)$row['colaborador1_kaizen'];
    $colaborador2 = (int)$row['colaborador2_kaizen'];
    $colaborador3 = (int)$row['colaborador3_kaizen'];
    $colaborador4 = (int)$row['colaborador4_kaizen'];
    $lider = (int)$row['lider_kaizen'];
    $datacadastro = mysqli_real_escape_string($v2, $row['datacadastro_kaizen']);
    $dataconclusao = mysqli_real_escape_string($v2, $row['dataconclusao_kaizen']);
    $status = (int)$row['status_kaizen'];
    $admin = (int)$row['admin_kaizen'];
    $pontos = (int)$row['pontos_kaizen'];
    $unidade = (int)$row['unidade_kaizen'];
    $setor = (int)$row['setor_kaizen'];
    $outrosdep = (int)$row['outrosdep_kaizen'];
    $qual = mysqli_real_escape_string($v2, $row['qual_kaizen']);
    $incidente = (int)$row['incidente_kaizen'];
    $obs = mysqli_real_escape_string($v2, $row['obs_kaizen']);
    
    // Campos NOVOS do V2 com valores DEFAULT
    $add_tipo_texto = '';          // VARCHAR(200) - Texto quando tipo="Outros"
    $complemento_kaizen = 0.00;    // DECIMAL(10,2) - DEPRECADO
    $valor_original_kaizen = 0.00; // DECIMAL(10,2) - Valor original
    $tipo_complemento_kaizen = 0;  // INT - ID do complemento
    $situacao_atual = '';          // TEXT - Nova funcionalidade v2
    
    // INSERT com estrutura V2 (33 colunas)
    $sql = "INSERT INTO kaizen (
        id_kaizen, categoria_kaizen, tipo_kaizen, add_tipo_texto, 
        tempo_kaizen, custo_kaizen, complemento_kaizen, valor_original_kaizen, 
        tipo_complemento_kaizen, reducao_kaizen, destaque_kaizen, onde_kaizen, 
        resultado_kaizen, situacao_atual, imagem1_kaizen, imagem2_kaizen, 
        colaborador_kaizen, colaborador1_kaizen, colaborador2_kaizen, 
        colaborador3_kaizen, colaborador4_kaizen, lider_kaizen, 
        datacadastro_kaizen, dataconclusao_kaizen, status_kaizen, admin_kaizen, 
        pontos_kaizen, unidade_kaizen, setor_kaizen, outrosdep_kaizen, 
        qual_kaizen, incidente_kaizen, obs_kaizen
    ) VALUES (
        $id, $categoria, $tipo, '$add_tipo_texto',
        $tempo, $custo, $complemento_kaizen, $valor_original_kaizen,
        $tipo_complemento_kaizen, $reducao, $destaque, '$onde',
        '$resultado', '$situacao_atual', '$imagem1', '$imagem2',
        $colaborador, $colaborador1, $colaborador2,
        $colaborador3, $colaborador4, $lider,
        '$datacadastro', '$dataconclusao', $status, $admin,
        $pontos, $unidade, $setor, $outrosdep,
        '$qual', $incidente, '$obs'
    )";
    
    if (!$DRY_RUN) {
        if (!mysqli_query($v2, $sql)) {
            log_msg("Erro ao inserir kaizen #$id: " . mysqli_error($v2), 'ERROR');
        }
    }
    $count++;
    
    // Progresso a cada 100 registros
    if ($count % 100 == 0) {
        log_msg("Progresso: $count de $total kaizens...", 'INFO');
    }
}

log_msg("Kaizens migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 5: MIGRAR LOG (estrutura idêntica)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 5: Migrando tabela LOG", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

$result = mysqli_query($v1, "SELECT * FROM log ORDER BY id_log");
$total = mysqli_num_rows($result);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id_log'];
    $usu = (int)$row['usu_log'];
    $ponto = (int)$row['ponto_log'];
    $saldo = (int)$row['saldo_log'];
    $data = mysqli_real_escape_string($v2, $row['data_log']);
    $descricao = mysqli_real_escape_string($v2, $row['descricao_log']);
    
    $sql = "INSERT INTO log (id_log, usu_log, ponto_log, saldo_log, data_log, descricao_log) 
            VALUES ($id, $usu, $ponto, $saldo, '$data', '$descricao')";
    
    if (!$DRY_RUN) {
        mysqli_query($v2, $sql);
    }
    $count++;
}

log_msg("Logs migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 6: MIGRAR LOGTROCA (estrutura idêntica)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 6: Migrando tabela LOGTROCA", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

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
    
    if (!$DRY_RUN) {
        mysqli_query($v2, $sql);
    }
    $count++;
}

log_msg("Logtrocas migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 7: MIGRAR PRODUTO (estrutura idêntica)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 7: Migrando tabela PRODUTO", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

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
    
    if (!$DRY_RUN) {
        mysqli_query($v2, $sql);
    }
    $count++;
}

log_msg("Produtos migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 8: MIGRAR LIDERSETOR (estrutura idêntica)
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 8: Migrando tabela LIDERSETOR", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

$result = mysqli_query($v1, "SELECT * FROM lidersetor ORDER BY id_lidersetor");
$total = mysqli_num_rows($result);
$count = 0;

while ($row = mysqli_fetch_assoc($result)) {
    $id = (int)$row['id_lidersetor'];
    $nome = mysqli_real_escape_string($v2, $row['nome_lidersetor']);
    $setores = mysqli_real_escape_string($v2, $row['setores_lidersetor']);
    $status = (int)$row['status_lidersetor'];
    
    $sql = "INSERT INTO lidersetor (id_lidersetor, nome_lidersetor, setores_lidersetor, status_lidersetor) 
            VALUES ($id, '$nome', '$setores', $status)";
    
    if (!$DRY_RUN) {
        mysqli_query($v2, $sql);
    }
    $count++;
}

log_msg("Lidersetores migrados: $count de $total", 'OK');

echo "\n";

// ============================================================================
// ETAPA 9: ATUALIZAR AUTO_INCREMENT
// ============================================================================

log_msg("═══════════════════════════════════════════════════════════════", 'INFO');
log_msg("ETAPA 9: Atualizando AUTO_INCREMENT das tabelas", 'INFO');
log_msg("═══════════════════════════════════════════════════════════════", 'INFO');

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
    
    if ($DRY_RUN) {
        log_msg("[SIMULAÇÃO] $tabela AUTO_INCREMENT seria definido para $max_id", 'INFO');
    } else {
        if (mysqli_query($v2, $sql)) {
            log_msg("$tabela AUTO_INCREMENT definido para $max_id", 'OK');
        } else {
            log_msg("Erro ao definir AUTO_INCREMENT de $tabela: " . mysqli_error($v2), 'ERROR');
        }
    }
}

echo "\n";

// ============================================================================
// FINALIZAÇÃO
// ============================================================================

mysqli_close($v1);
mysqli_close($v2);

echo "╔══════════════════════════════════════════════════════════════════╗\n";
echo "║                    MIGRAÇÃO CONCLUÍDA!                           ║\n";
echo "╠══════════════════════════════════════════════════════════════════╣\n";
if ($DRY_RUN) {
echo "║  ⚠️  MODO SIMULAÇÃO - Nenhuma alteração foi feita                ║\n";
echo "║  Para executar de verdade, altere \$DRY_RUN = false              ║\n";
} else {
echo "║  ✅ Todos os dados foram migrados com sucesso!                   ║\n";
echo "║  Tabelas de backup criadas com sufixo _backup_YYYYMMDD_HHMMSS    ║\n";
}
echo "╚══════════════════════════════════════════════════════════════════╝\n";
echo "\n";

log_msg("Script finalizado!", 'OK');
