<?php
/**
 * Atualizar setor_kaizen do colaborador #1055 para Qualidade (ID 3)
 */

$con = mysqli_connect('orionv226.mysql.dbaas.com.br', 'orionv226', 'frn6XjyY#zCdMV', 'orionv226');
if (!$con) die("Erro de conexão");
mysqli_set_charset($con, 'utf8mb4');

echo "\n=== ATUALIZAÇÃO DE SETOR - COLABORADOR #1055 ===\n\n";

// Verificar kaizens afetados
$r = mysqli_query($con, "SELECT id_kaizen, setor_kaizen FROM kaizen WHERE colaborador_kaizen = 1055 AND setor_kaizen != 3");
$count = mysqli_num_rows($r);

echo "Kaizens a atualizar (setor != Qualidade):\n";
while ($d = mysqli_fetch_assoc($r)) {
    echo "  - Kaizen #{$d['id_kaizen']}: setor {$d['setor_kaizen']} → 3 (Qualidade)\n";
}
echo "\nTotal: $count kaizens\n\n";

// Executar atualização
$update = mysqli_query($con, "UPDATE kaizen SET setor_kaizen = 3 WHERE colaborador_kaizen = 1055");
$affected = mysqli_affected_rows($con);

echo "✓ Atualizado: $affected registros\n\n";

// Verificar resultado
$r2 = mysqli_query($con, "SELECT id_kaizen, setor_kaizen FROM kaizen WHERE colaborador_kaizen = 1055 LIMIT 5");
echo "Verificação (primeiros 5 kaizens do colaborador):\n";
while ($d = mysqli_fetch_assoc($r2)) {
    $setor = $d['setor_kaizen'] == 3 ? "✓ Qualidade" : "✗ Setor {$d['setor_kaizen']}";
    echo "  - Kaizen #{$d['id_kaizen']}: $setor\n";
}

mysqli_close($con);
echo "\n";
