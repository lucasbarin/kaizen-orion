# Script para capturar screenshots do Sistema Orion Kaizen
# Requer Chrome/Edge instalado e sistema rodando em http://localhost/orion-v2/

Add-Type -AssemblyName System.Windows.Forms
Add-Type -AssemblyName System.Drawing

# Função para capturar screenshot
function Capture-Screenshot {
    param(
        [string]$FilePath
    )
    
    Start-Sleep -Seconds 2
    
    $screen = [System.Windows.Forms.Screen]::PrimaryScreen.Bounds
    $bitmap = New-Object System.Drawing.Bitmap $screen.Width, $screen.Height
    $graphics = [System.Drawing.Graphics]::FromImage($bitmap)
    $graphics.CopyFromScreen($screen.Location, [System.Drawing.Point]::Empty, $screen.Size)
    
    $bitmap.Save($FilePath, [System.Drawing.Imaging.ImageFormat]::Png)
    $graphics.Dispose()
    $bitmap.Dispose()
    
    Write-Host "✓ Screenshot salvo: $FilePath" -ForegroundColor Green
}

# Função para abrir URL e capturar
function Capture-Page {
    param(
        [string]$Url,
        [string]$OutputFile,
        [int]$WaitSeconds = 3
    )
    
    Write-Host "`nAbrindo: $Url" -ForegroundColor Cyan
    Start-Process "msedge" $Url
    Start-Sleep -Seconds $WaitSeconds
    
    Capture-Screenshot -FilePath $OutputFile
}

# Diretório de saída
$outputDir = "c:\wamp64\www\orion-v2\guia\imgs"
$baseUrl = "http://localhost/orion-v2"

Write-Host "========================================" -ForegroundColor Yellow
Write-Host "  CAPTURA DE TELAS - Sistema Orion" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""
Write-Host "INSTRUÇÕES:" -ForegroundColor Cyan
Write-Host "1. Certifique-se de que o WAMP está rodando" -ForegroundColor White
Write-Host "2. Faça login no sistema antes de executar" -ForegroundColor White
Write-Host "3. Mantenha o navegador em foco durante as capturas" -ForegroundColor White
Write-Host "4. Pressione F11 para tela cheia (opcional)" -ForegroundColor White
Write-Host ""

$continue = Read-Host "Sistema está rodando e você está logado? (S/N)"
if ($continue -ne "S" -and $continue -ne "s") {
    Write-Host "Execução cancelada." -ForegroundColor Red
    exit
}

Write-Host "`nIniciando capturas em 3 segundos..." -ForegroundColor Yellow
Start-Sleep -Seconds 3

# ÁREA DO COLABORADOR
Write-Host "`n=== ÁREA DO COLABORADOR ===" -ForegroundColor Magenta

# 1. Página inicial/Home
Capture-Page -Url "$baseUrl/home.php" -OutputFile "$outputDir\colaborador-home.png" -WaitSeconds 4

# 2. Novo Kaizen
Capture-Page -Url "$baseUrl/novo-kaizen.php" -OutputFile "$outputDir\colaborador-novo-kaizen.png" -WaitSeconds 4

# 3. Minhas Sugestões
Capture-Page -Url "$baseUrl/minhas-sugestoes.php" -OutputFile "$outputDir\colaborador-minhas-sugestoes.png" -WaitSeconds 3

# 4. Extrato de Pontos
Capture-Page -Url "$baseUrl/extrato-pontos.php" -OutputFile "$outputDir\colaborador-extrato-pontos.png" -WaitSeconds 3

# 5. Trocar Pontos (Catálogo)
Capture-Page -Url "$baseUrl/home.php#produtos" -OutputFile "$outputDir\colaborador-catalogo.png" -WaitSeconds 3

Write-Host "`n=== ÁREA ADMINISTRATIVA ===" -ForegroundColor Magenta
Write-Host "ATENÇÃO: Você precisa estar logado como ADMIN" -ForegroundColor Yellow
$continueAdmin = Read-Host "Continuar para capturas do admin? (S/N)"

if ($continueAdmin -eq "S" -or $continueAdmin -eq "s") {
    # 6. Dashboard Admin
    Capture-Page -Url "$baseUrl/admin/home.php" -OutputFile "$outputDir\admin-dashboard.png" -WaitSeconds 4
    
    # 7. Lista de Kaizens
    Capture-Page -Url "$baseUrl/admin/kaizen-admin.php" -OutputFile "$outputDir\admin-kaizens-lista.png" -WaitSeconds 3
    
    # 8. Colaboradores
    Capture-Page -Url "$baseUrl/admin/colaborador-admin.php" -OutputFile "$outputDir\admin-colaboradores.png" -WaitSeconds 3
    
    # 9. Produtos
    Capture-Page -Url "$baseUrl/admin/produto-admin.php" -OutputFile "$outputDir\admin-produtos.png" -WaitSeconds 3
    
    # 10. Configurações
    Capture-Page -Url "$baseUrl/admin/pg-config.php" -OutputFile "$outputDir\admin-config.png" -WaitSeconds 3
}

Write-Host "`n========================================" -ForegroundColor Green
Write-Host "  CAPTURAS CONCLUÍDAS!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host "`nImagens salvas em: $outputDir" -ForegroundColor Cyan
Write-Host "`nPróximos passos:" -ForegroundColor Yellow
Write-Host "1. Revise as imagens capturadas" -ForegroundColor White
Write-Host "2. Recorte/edite se necessário" -ForegroundColor White
Write-Host "3. Execute o script de atualização dos guias" -ForegroundColor White
Write-Host ""
