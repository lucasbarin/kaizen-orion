<?php
// Script para migrar includes de conexao.php e funcoes.php para conexao8.php e funcoes8.php

$rootDir = __DIR__;

// Padrões a serem substituídos
$patterns = [
    // Para arquivos na raiz do admin
    [
        'search' => 'include ("../app/conexao8.php");',
        'replace' => 'include ("../app/conexao8.php");'
    ],
    [
        'search' => 'include ("../app/funcoes8.php");',
        'replace' => 'include ("../app/funcoes8.php");'
    ],
    // Para arquivos dentro de admin/app
    [
        'search' => 'include ("../../app/conexao8.php");',
        'replace' => 'include ("../../app/conexao8.php");'
    ],
    [
        'search' => 'include ("../../app/funcoes8.php");',
        'replace' => 'include ("../../app/funcoes8.php");'
    ],
];

function replaceInFile($file, $patterns) {
    $content = file_get_contents($file);
    $originalContent = $content;
    
    foreach ($patterns as $pattern) {
        $content = str_replace($pattern['search'], $pattern['replace'], $content);
    }
    
    if ($content !== $originalContent) {
        file_put_contents($file, $content);
        return true;
    }
    
    return false;
}

function processDirectory($dir, $patterns, &$stats) {
    $files = glob($dir . '/*.php');
    
    foreach ($files as $file) {
        if (is_file($file)) {
            if (replaceInFile($file, $patterns)) {
                $stats['updated']++;
                echo "✓ Atualizado: " . basename($file) . "\n";
            } else {
                $stats['skipped']++;
            }
        }
    }
    
    // Processar subdiretórios
    $dirs = glob($dir . '/*', GLOB_ONLYDIR);
    foreach ($dirs as $subdir) {
        if (basename($subdir) !== 'assets' && basename($subdir) !== 'gerador') {
            processDirectory($subdir, $patterns, $stats);
        }
    }
}

$stats = ['updated' => 0, 'skipped' => 0];

echo "=== Migrando includes no diretório admin ===\n\n";

// Processar diretório admin
processDirectory($rootDir, $patterns, $stats);

echo "\n=== Resumo ===\n";
echo "Arquivos atualizados: " . $stats['updated'] . "\n";
echo "Arquivos ignorados: " . $stats['skipped'] . "\n";

echo "\n✓ Migração concluída!\n";
