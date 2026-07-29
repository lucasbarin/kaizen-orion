<?php
$con = mysqli_connect('orionv226.mysql.dbaas.com.br', 'orionv226', 'frn6XjyY#zCdMV', 'orionv226');
mysqli_set_charset($con, 'utf8mb4');
$r = mysqli_query($con, "SELECT k.id_kaizen, k.colaborador_kaizen, k.setor_kaizen, s.nome_setor, c.nome_colaborador 
                         FROM kaizen k 
                         LEFT JOIN setor s ON s.id_setor = k.setor_kaizen
                         LEFT JOIN colaborador c ON c.id_colaborador = k.colaborador_kaizen
                         WHERE k.id_kaizen = 1629");
$d = mysqli_fetch_assoc($r);
echo "\nKaizen #1629:\n";
echo "  Colaborador: " . $d['nome_colaborador'] . " (ID: " . $d['colaborador_kaizen'] . ")\n";
echo "  Setor ID: " . $d['setor_kaizen'] . "\n";
echo "  Setor Nome: " . $d['nome_setor'] . "\n";
mysqli_close($con);
