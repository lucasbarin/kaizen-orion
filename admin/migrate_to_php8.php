<?php
/**
 * Script de Migração PHP 5.x para PHP 8.x
 * Executa via navegador: http://localhost/orion/admin/migrate_to_php8.php
 */

set_time_limit(300); // 5 minutos
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "<html><head><title>Migração PHP 8</title>
<style>
body { font-family: Arial; padding: 20px; background: #f5f5f5; }
.success { color: green; }
.error { color: red; }
.info { color: blue; }
.stats { background: white; padding: 15px; margin: 20px 0; border-radius: 5px; }
</style>
</head><body>";

echo "<h1>🔧 Migração para PHP 8.x</h1>";

$stats = [
    'includes_updated' => 0,
    'mysql_updated' => 0,
    'sql_calls_updated' => 0,
    'files_processed' => 0,
    'errors' => 0
];

// Função para substituir includes
function updateIncludes($content) {
    $patterns = [
        'include ("app/conexao.php")' => 'include ("app/conexao8.php")',
        'include ("app/funcoes.php")' => 'include ("app/funcoes8.php")',
        'include ("../app/conexao.php")' => 'include ("../app/conexao8.php")',
        'include ("../app/funcoes.php")' => 'include ("../app/funcoes8.php")',
        'include ("../../app/conexao.php")' => 'include ("../../app/conexao8.php")',
        'include ("../../app/funcoes.php")' => 'include ("../../app/funcoes8.php")',
        'include ("conexao.php")' => 'include ("conexao8.php")',
        'include ("funcoes.php")' => 'include ("funcoes8.php")',
    ];
    
    $updated = false;
    foreach ($patterns as $search => $replace) {
        if (strpos($content, $search) !== false) {
            $content = str_replace($search, $replace, $content);
            $updated = true;
        }
    }
    
    return [$content, $updated];
}

// Função para migrar mysql_* para mysqli_*
function updateMysqlFunctions($content) {
    global $stats;
    
    $patterns = [
        '/\bmysql_query\s*\(/i' => 'mysqli_query($con, ',
        '/\bmysql_fetch_array\s*\(/i' => 'mysqli_fetch_array(',
        '/\bmysql_fetch_assoc\s*\(/i' => 'mysqli_fetch_assoc(',
        '/\bmysql_num_rows\s*\(/i' => 'mysqli_num_rows(',
        '/\bmysql_error\s*\(\s*\)/i' => 'mysqli_error($con)',
        '/\bmysql_insert_id\s*\(\s*\)/i' => 'mysqli_insert_id($con)',
        '/\bmysql_affected_rows\s*\(\s*\)/i' => 'mysqli_affected_rows($con)',
    ];
    
    $updated = false;
    foreach ($patterns as $pattern => $replace) {
        if (preg_match($pattern, $content)) {
            $content = preg_replace($pattern, $replace, $content);
            $updated = true;
        }
    }
    
    return [$content, $updated];
}

// Função para adicionar $con nas chamadas sql()
function updateSqlCalls($content) {
    // Padrão: sql("query") ou sql('query') SEM segundo parâmetro
    $pattern = '/\bsql\s*\(\s*(["\'][^"\']*["\'])\s*\)(?!\s*\))/';
    
    $newContent = preg_replace_callback($pattern, function($matches) {
        return 'sql(' . $matches[1] . ', $con)';
    }, $content);
    
    $updated = ($newContent !== $content);
    return [$newContent, $updated];
}

// Processar um arquivo
function processFile($filePath, &$stats) {
    $content = file_get_contents($filePath);
    $original = $content;
    
    // 1. Atualizar includes
    list($content, $includesUpdated) = updateIncludes($content);
    if ($includesUpdated) $stats['includes_updated']++;
    
    // 2. Atualizar funções mysql_*
    list($content, $mysqlUpdated) = updateMysqlFunctions($content);
    if ($mysqlUpdated) $stats['mysql_updated']++;
    
    // 3. Atualizar chamadas sql()
    list($content, $sqlUpdated) = updateSqlCalls($content);
    if ($sqlUpdated) $stats['sql_calls_updated']++;
    
    // Salvar se houve mudanças
    if ($content !== $original) {
        file_put_contents($filePath, $content);
        return true;
    }
    
    return false;
}

// Processar diretório recursivamente
function processDirectory($dir, &$stats, $exclude = ['assets', 'gerador', 'import', 'PHPMailer_v5.1']) {
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        if (is_file($file) && basename($file) !== 'migrate_to_php8.php') {
            $stats['files_processed']++;
            
            try {
                if (processFile($file, $stats)) {
                    echo "<div class='success'>✓ " . str_replace($_SERVER['DOCUMENT_ROOT'], '', $file) . "</div>";
                }
            } catch (Exception $e) {
                $stats['errors']++;
                echo "<div class='error'>✗ " . basename($file) . ": " . $e->getMessage() . "</div>";
            }
        }
    }
    
    // Processar subdiretórios
    $dirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($dirs as $subdir) {
        $basename = basename($subdir);
        if (!in_array($basename, $exclude)) {
            processDirectory($subdir, $stats, $exclude);
        }
    }
}

echo "<div class='info'>📁 Iniciando migração...</div><br>";

// Processar diretório raiz do projeto
$rootDir = dirname(__DIR__); // c:\wamp64\www\orion
echo "<div class='info'>Processando raiz do projeto...</div>";
processDirectory($rootDir, $stats, ['admin', 'lib', 'Templates', 'app']);

// Processar diretório app
echo "<div class='info'>Processando app/...</div>";
processDirectory($rootDir . '/app', $stats, ['PHPMailer_v5.1']);

// Processar diretório admin
echo "<div class='info'>Processando admin/...</div>";
processDirectory(__DIR__, $stats, ['assets', 'gerador', 'import']);

// Processar admin/app
echo "<div class='info'>Processando admin/app/...</div>";
processDirectory(__DIR__ . '/app', $stats);

echo "<div class='stats'>";
echo "<h2>📊 Estatísticas</h2>";
echo "<strong>Arquivos processados:</strong> {$stats['files_processed']}<br>";
echo "<strong>Includes atualizados:</strong> <span class='success'>{$stats['includes_updated']}</span><br>";
echo "<strong>Funções mysql_* migradas:</strong> <span class='success'>{$stats['mysql_updated']}</span><br>";
echo "<strong>Chamadas sql() atualizadas:</strong> <span class='success'>{$stats['sql_calls_updated']}</span><br>";
echo "<strong>Erros:</strong> <span class='error'>{$stats['errors']}</span><br>";
echo "</div>";

echo "<div class='success'><h2>✅ Migração concluída!</h2></div>";
echo "<p><strong>Próximos passos:</strong></p>";
echo "<ol>";
echo "<li>Verificar se o sistema está rodando corretamente</li>";
echo "<li>Testar funcionalidades principais (login, cadastro de kaizen, pontos)</li>";
echo "<li>Revisar arquivos que possam ter funções mysql_* em contextos especiais</li>";
echo "<li>DELETAR este arquivo (migrate_to_php8.php) após confirmar que tudo funciona</li>";
echo "</ol>";

echo "</body></html>";
?>
