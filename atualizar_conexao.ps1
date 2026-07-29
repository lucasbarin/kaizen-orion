# Script para atualizar conexão do banco de dados no orion-v2
# Execute este script no PowerShell após copiar o banco de dados

$arquivo = "c:\wamp64\www\orion-v2\app\conexao8.php"

# Fazer backup
Copy-Item $arquivo "$arquivo.backup"
Write-Host "✅ Backup criado: conexao8.php.backup" -ForegroundColor Green

# Ler conteúdo
$conteudo = Get-Content $arquivo -Raw

# Substituir nome do banco
$conteudo = $conteudo -replace 'orionv2"', 'orionv2_frontend"'

# Salvar
Set-Content $arquivo $conteudo

Write-Host "✅ Arquivo atualizado: conexao8.php" -ForegroundColor Green
Write-Host "   Banco de dados alterado para: orionv2_frontend" -ForegroundColor Cyan
Write-Host ""
Write-Host "⚠️  IMPORTANTE: Certifique-se de copiar o banco 'orionv2' para 'orionv2_frontend' no phpMyAdmin!" -ForegroundColor Yellow
