# Script para corrigir short tags PHP (<?  para <?php)
# Necessário para compatibilidade com servidores onde short_open_tag = Off

Write-Host "Iniciando correção de short tags PHP..." -ForegroundColor Cyan
Write-Host ""

$arquivosCorrigidos = 0
$erros = 0

# Função para corrigir short tags
function Corrigir-ShortTags {
    param([string]$arquivo)
    
    try {
        $conteudo = Get-Content $arquivo -Raw -Encoding UTF8
        $original = $conteudo
        
        # Corrige short tags no início do arquivo
        # Apenas <?  seguido de quebra de linha ou espaço (não <?php, <?=, <?xml)
        $conteudo = $conteudo -replace '^\<\?(\s)', '<?php$1'
        
        # Corrige short tags após quebras de linha
        $conteudo = $conteudo -replace '(\r?\n)\<\?(\s)', '$1<?php$2'
        
        if ($conteudo -ne $original) {
            Set-Content $arquivo -Value $conteudo -Encoding UTF8 -NoNewline
            Write-Host "✓ $arquivo" -ForegroundColor Green
            return $true
        }
        return $false
    }
    catch {
        Write-Host "✗ Erro ao processar ${arquivo}: $_" -ForegroundColor Red
        return $false
    }
}

# Processar arquivos do admin
Write-Host "Corrigindo arquivos em admin/..." -ForegroundColor Yellow

# Admin principal
Get-ChildItem "admin\*.php" -File | ForEach-Object {
    if (Corrigir-ShortTags $_.FullName) {
        $arquivosCorrigidos++
    }
}

# Admin/app
Get-ChildItem "admin\app\*.php" -File | ForEach-Object {
    if (Corrigir-ShortTags $_.FullName) {
        $arquivosCorrigidos++
    }
}

# Processar arquivos da raiz (app/)
Write-Host ""
Write-Host "Corrigindo arquivos em app/..." -ForegroundColor Yellow

Get-ChildItem "app\*.php" -File | ForEach-Object {
    if (Corrigir-ShortTags $_.FullName) {
        $arquivosCorrigidos++
    }
}

# Processar arquivos da raiz principal
Write-Host ""
Write-Host "Corrigindo arquivos na raiz..." -ForegroundColor Yellow

Get-ChildItem "*.php" -File | ForEach-Object {
    if (Corrigir-ShortTags $_.FullName) {
        $arquivosCorrigidos++
    }
}

Write-Host ""
Write-Host "========================================" -ForegroundColor Cyan
Write-Host "Correção concluída!" -ForegroundColor Green
Write-Host "Arquivos corrigidos: $arquivosCorrigidos" -ForegroundColor White
Write-Host "========================================" -ForegroundColor Cyan
Write-Host ""
Write-Host "PRÓXIMOS PASSOS:" -ForegroundColor Yellow
Write-Host "1. Teste localmente: http://localhost/orion-v2/" -ForegroundColor White
Write-Host "2. Upload via FTP dos arquivos corrigidos" -ForegroundColor White
Write-Host "3. Teste em produção: https://orion-v2tecnolog1.websiteseguro.com/" -ForegroundColor White
