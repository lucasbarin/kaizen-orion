<?php
/**
 * Verificação de inconsistência de setor no kaizen #1629
 */

// Conexão V2 (produção)
$con = mysqli_connect('orionv226.mysql.dbaas.com.br', 'orionv226', 'frn6XjyY#zCdMV', 'orionv226');
if (!$con) die("Erro de conexão V2");
mysqli_set_charset($con, 'utf8mb4');

// Conexão V1 (para comparar)
$v1 = mysqli_connect('orionkaizen.mysql.dbaas.com.br', 'orionkaizen', 'sdgbj@CGH5bhj_', 'orionkaizen');
if (!$v1) die("Erro de conexão V1");
mysqli_set_charset($v1, 'utf8mb4');

echo "\n╔══════════════════════════════════════════════════════════════════╗\n";
echo "║     VERIFICAÇÃO DE SETOR - KAIZEN #1629 / COLABORADOR #1055     ║\n";
echo "╚══════════════════════════════════════════════════════════════════╝\n\n";

// =============================================================================
// 1. DADOS DO KAIZEN #1629 NO V2
// =============================================================================
echo "═══ KAIZEN #1629 NO V2 ═══\n\n";

$r = mysqli_query($con, "SELECT k.*, s.nome_setor as setor_nome, c.nome_colaborador, c.setor_colaborador,
                         s2.nome_setor as setor_colaborador_nome
                         FROM kaizen k 
                         LEFT JOIN setor s ON s.id_setor = k.setor_kaizen
                         LEFT JOIN colaborador c ON c.id_colaborador = k.colaborador_kaizen
                         LEFT JOIN setor s2 ON s2.id_setor = c.setor_colaborador
                         WHERE k.id_kaizen = 1629");
$d = mysqli_fetch_assoc($r);

echo "Colaborador: {$d['nome_colaborador']} (ID: {$d['colaborador_kaizen']})\n";
echo "Setor gravado NO KAIZEN (setor_kaizen): {$d['setor_kaizen']} → {$d['setor_nome']}\n";
echo "Setor atual DO COLABORADOR (setor_colaborador): {$d['setor_colaborador']} → {$d['setor_colaborador_nome']}\n";
echo "\n";

// =============================================================================
// 2. DADOS DO KAIZEN #1629 NO V1 (original)
// =============================================================================
echo "═══ KAIZEN #1629 NO V1 (original) ═══\n\n";

$r1 = mysqli_query($v1, "SELECT k.*, s.nome_setor as setor_nome, c.nome_colaborador, c.setor_colaborador,
                          s2.nome_setor as setor_colaborador_nome
                          FROM kaizen k 
                          LEFT JOIN setor s ON s.id_setor = k.setor_kaizen
                          LEFT JOIN colaborador c ON c.id_colaborador = k.colaborador_kaizen
                          LEFT JOIN setor s2 ON s2.id_setor = c.setor_colaborador
                          WHERE k.id_kaizen = 1629");
$d1 = mysqli_fetch_assoc($r1);

if ($d1) {
    echo "Colaborador: {$d1['nome_colaborador']} (ID: {$d1['colaborador_kaizen']})\n";
    echo "Setor gravado NO KAIZEN (setor_kaizen): {$d1['setor_kaizen']} → {$d1['setor_nome']}\n";
    echo "Setor atual DO COLABORADOR (setor_colaborador): {$d1['setor_colaborador']} → {$d1['setor_colaborador_nome']}\n";
} else {
    echo "Kaizen #1629 não existe no V1\n";
}
echo "\n";

// =============================================================================
// 3. DADOS DO COLABORADOR #1055 EM AMBOS
// =============================================================================
echo "═══ COLABORADOR #1055 ═══\n\n";

$r_c2 = mysqli_query($con, "SELECT c.*, s.nome_setor FROM colaborador c 
                            LEFT JOIN setor s ON s.id_setor = c.setor_colaborador 
                            WHERE c.id_colaborador = 1055");
$c2 = mysqli_fetch_assoc($r_c2);

$r_c1 = mysqli_query($v1, "SELECT c.*, s.nome_setor FROM colaborador c 
                           LEFT JOIN setor s ON s.id_setor = c.setor_colaborador 
                           WHERE c.id_colaborador = 1055");
$c1 = mysqli_fetch_assoc($r_c1);

echo "V1: {$c1['nome_colaborador']} - Setor: {$c1['setor_colaborador']} ({$c1['nome_setor']})\n";
echo "V2: {$c2['nome_colaborador']} - Setor: {$c2['setor_colaborador']} ({$c2['nome_setor']})\n";
echo "\n";

// =============================================================================
// 4. VERIFICAR TABELA DE SETORES
// =============================================================================
echo "═══ TABELA DE SETORES V2 ═══\n\n";

$r = mysqli_query($con, "SELECT * FROM setor ORDER BY id_setor");
while ($s = mysqli_fetch_assoc($r)) {
    echo "ID {$s['id_setor']}: {$s['nome_setor']}\n";
}
echo "\n";

// =============================================================================
// 5. DIAGNÓSTICO
// =============================================================================
echo "═══ DIAGNÓSTICO ═══\n\n";

if ($d['setor_kaizen'] != $d['setor_colaborador']) {
    echo "⚠️  INCONSISTÊNCIA DETECTADA:\n";
    echo "   O campo 'setor_kaizen' do kaizen ({$d['setor_kaizen']}) é diferente do\n";
    echo "   campo 'setor_colaborador' do colaborador ({$d['setor_colaborador']})\n\n";
    
    echo "POSSÍVEIS CAUSAS:\n";
    echo "1. O colaborador mudou de setor após criar o kaizen (comportamento esperado)\n";
    echo "2. O kaizen foi migrado com setor diferente do atual do colaborador\n";
    echo "3. Erro de dados na migração\n\n";
    
    // Verificar se no V1 também era inconsistente
    if ($d1 && $d1['setor_kaizen'] == $d['setor_kaizen']) {
        echo "✓ O setor_kaizen no V1 também era {$d1['setor_kaizen']} ({$d1['setor_nome']})\n";
        echo "  → Isso indica que NÃO foi erro de migração.\n";
        echo "  → O kaizen foi criado quando o colaborador estava no setor '{$d1['setor_nome']}'\n";
    } else if ($d1) {
        echo "✗ No V1 o setor_kaizen era {$d1['setor_kaizen']} ({$d1['setor_nome']})\n";
        echo "  → Possível erro de migração!\n";
    }
}

echo "\n";

mysqli_close($con);
mysqli_close($v1);
