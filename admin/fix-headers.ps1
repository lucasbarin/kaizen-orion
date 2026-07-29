# Script para padronizar headers em todas as páginas admin
# Substituir estrutura antiga por nova com logo

$files = Get-ChildItem -Path "c:\wamp64\www\orion-v2\admin" -Filter "*.php" -Exclude "*backup*","*bs5*.php"

$oldPattern = @'
        <div class="header-left">
            <button class="btn btn-link text-white" id="sidebarToggle">
                <i class="fas fa-bars fa-lg"></i>
            </button>
            <img src="../imgs/logo-orion.png" alt="Orion" height="40">
        </div>
        <div class="header-right">
            <a href="index.php" class="btn btn-sm btn-outline-light">
                <i class="fas fa-sign-out-alt"></i> Sair
            </a>
        </div>
'@

$newPattern = @'
        <button class="sidebar-toggle" id="sidebarToggle">
            <i class="fas fa-bars"></i>
        </button>
        
        <a href="../" class="logo">
            <img src="assets/admin/layout/img/logo.png" alt="Orion Kaizen" onerror="this.style.display='none'">
            <span>ORION KAIZEN</span>
        </a>
        
        <div class="header-right">
            <a href="app/logout.php" class="btn-logout">
                <i class="fas fa-sign-out-alt"></i>
                <span>Sair</span>
            </a>
        </div>
'@

$count = 0

foreach ($file in $files) {
    $content = Get-Content -Path $file.FullName -Raw -Encoding UTF8
    
    if ($content -match [regex]::Escape('<div class="header-left">')) {
        Write-Host "Processando: $($file.Name)" -ForegroundColor Yellow
        
        # Substituir padrão
        $content = $content -replace [regex]::Escape($oldPattern), $newPattern
        
        # Salvar arquivo
        Set-Content -Path $file.FullName -Value $content -Encoding UTF8 -NoNewline
        
        $count++
        Write-Host "  ✓ Atualizado" -ForegroundColor Green
    }
}

Write-Host "`nTotal de arquivos atualizados: $count" -ForegroundColor Cyan
Write-Host "Concluido!" -ForegroundColor Green
