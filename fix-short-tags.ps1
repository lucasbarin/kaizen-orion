# Script para corrigir short tags PHP
Write-Host "Corrigindo short tags PHP..." -ForegroundColor Cyan
$count = 0
Get-ChildItem -Path admin -Filter *.php -Recurse | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    if ($content -match '^\<\?[^p]') {
        $content = $content -replace '^\<\?', '<?php'
        Set-Content $_.FullName -Value $content -NoNewline
        Write-Host "Corrigido: $($_.Name)" -ForegroundColor Green
        $count++
    }
}
Get-ChildItem -Path app -Filter *.php | ForEach-Object {
    $content = Get-Content $_.FullName -Raw
    if ($content -match '^\<\?[^p]') {
        $content = $content -replace '^\<\?', '<?php'
        Set-Content $_.FullName -Value $content -NoNewline
        Write-Host "Corrigido: $($_.Name)" -ForegroundColor Green
        $count++
    }
}
Write-Host ""
Write-Host "Total corrigido: $count arquivos" -ForegroundColor Yellow
