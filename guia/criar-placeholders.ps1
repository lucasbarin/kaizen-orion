# Script para criar imagens placeholder ilustrativas para os guias
# Gera imagens SVG temporarias ate que screenshots reais sejam capturados

$outputDir = "c:\wamp64\www\orion-v2\guia\imgs"

# Criar diretorio se nao existir
if (-not (Test-Path $outputDir)) {
    New-Item -ItemType Directory -Path $outputDir -Force | Out-Null
}

function Create-PlaceholderSVG {
    param(
        [string]$FilePath,
        [string]$Title,
        [string]$Description,
        [string]$Color = "#009ee3"
    )
    
    $svg = @"
<svg width="1200" height="800" xmlns="http://www.w3.org/2000/svg">
    <defs>
        <linearGradient id="grad" x1="0%" y1="0%" x2="100%" y2="100%">
            <stop offset="0%" style="stop-color:$Color;stop-opacity:1" />
            <stop offset="100%" style="stop-color:#0075ad;stop-opacity:1" />
        </linearGradient>
    </defs>
    <rect width="1200" height="800" fill="url(#grad)"/>
    <rect x="50" y="50" width="1100" height="700" fill="white" opacity="0.95" rx="10"/>
    
    <!-- Header simulado -->
    <rect x="50" y="50" width="1100" height="80" fill="$Color" rx="10"/>
    <text x="600" y="100" font-family="Arial, sans-serif" font-size="28" fill="white" text-anchor="middle" font-weight="bold">Sistema Orion Kaizen</text>
    
    <!-- Titulo da tela -->
    <text x="100" y="180" font-family="Arial, sans-serif" font-size="36" fill="#1a1a2e" font-weight="bold">$Title</text>
    <line x1="100" y1="200" x2="400" y2="200" stroke="#fcd700" stroke-width="4"/>
    
    <!-- Descricao -->
    <text x="100" y="250" font-family="Arial, sans-serif" font-size="20" fill="#495057">$Description</text>
    
    <!-- Elementos decorativos simulando conteudo -->
    <rect x="100" y="300" width="1000" height="60" fill="#f8f9fa" rx="5"/>
    <rect x="100" y="380" width="1000" height="60" fill="#f8f9fa" rx="5"/>
    <rect x="100" y="460" width="1000" height="60" fill="#f8f9fa" rx="5"/>
    
    <circle cx="130" cy="330" r="20" fill="$Color" opacity="0.3"/>
    <circle cx="130" cy="410" r="20" fill="$Color" opacity="0.3"/>
    <circle cx="130" cy="490" r="20" fill="$Color" opacity="0.3"/>
    
    <!-- Nota -->
    <rect x="100" y="600" width="1000" height="100" fill="#fff3cd" rx="5" opacity="0.7"/>
    <text x="600" y="640" font-family="Arial, sans-serif" font-size="18" fill="#856404" text-anchor="middle">Imagem ilustrativa</text>
    <text x="600" y="670" font-family="Arial, sans-serif" font-size="16" fill="#856404" text-anchor="middle">Capture a tela real usando: guia\capturar-telas.ps1</text>
</svg>
"@
    
    $svg | Out-File -FilePath $FilePath -Encoding UTF8
    Write-Host "Criado: $FilePath" -ForegroundColor Green
}

Write-Host "========================================" -ForegroundColor Yellow
Write-Host "  Criando Placeholders Ilustrativos" -ForegroundColor Yellow
Write-Host "========================================" -ForegroundColor Yellow
Write-Host ""

# COLABORADOR
Create-PlaceholderSVG -FilePath "$outputDir\colaborador-home.svg" -Title "Pagina Inicial" -Description "Dashboard do colaborador com saldo de pontos e acesso rapido" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-login.svg" -Title "Login" -Description "Tela de autenticacao - Digite CPF e senha" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-novo-kaizen.svg" -Title "Novo Kaizen" -Description "Formulario wizard em 3 etapas para criar sugestao" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-upload.svg" -Title "Upload de Anexos" -Description "Area de drag-and-drop para evidencias" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-minhas-sugestoes.svg" -Title "Minhas Sugestoes" -Description "Lista de Kaizens enviados com badges de status" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-extrato.svg" -Title "Extrato de Pontos" -Description "Historico completo de ganhos e resgates" -Color "#009ee3"

Create-PlaceholderSVG -FilePath "$outputDir\colaborador-catalogo.svg" -Title "Catalogo de Produtos" -Description "Produtos disponiveis para resgate com pontos" -Color "#009ee3"

# ADMIN
Create-PlaceholderSVG -FilePath "$outputDir\admin-dashboard.svg" -Title "Dashboard Administrativo" -Description "Metricas, Kaizens pendentes e indicadores do sistema" -Color "#dc3545"

Create-PlaceholderSVG -FilePath "$outputDir\admin-kaizens.svg" -Title "Gerenciar Kaizens" -Description "Lista completa com filtros por status, setor e periodo" -Color "#dc3545"

Create-PlaceholderSVG -FilePath "$outputDir\admin-analisar.svg" -Title "Analisar Kaizen" -Description "Tela de analise para aprovar/rejeitar e conceder pontos" -Color "#dc3545"

Create-PlaceholderSVG -FilePath "$outputDir\admin-colaboradores.svg" -Title "Colaboradores" -Description "Gerenciar usuarios, permissoes e saldo de pontos" -Color "#dc3545"

Create-PlaceholderSVG -FilePath "$outputDir\admin-produtos.svg" -Title "Catalogo de Produtos" -Description "Adicionar/editar produtos e gerenciar estoque" -Color "#dc3545"

Create-PlaceholderSVG -FilePath "$outputDir\admin-config.svg" -Title "Configuracoes" -Description "Tipos de Kaizen, setores, parametros e calculos" -Color "#dc3545"

Write-Host ""
Write-Host "========================================" -ForegroundColor Green
Write-Host "  Placeholders Criados!" -ForegroundColor Green
Write-Host "========================================" -ForegroundColor Green
Write-Host ""
Write-Host "Localizacao: $outputDir" -ForegroundColor Cyan
Write-Host ""
Write-Host "Proximos passos:" -ForegroundColor Yellow
Write-Host "1. Os guias agora podem usar estas imagens SVG" -ForegroundColor White
Write-Host "2. Para screenshots reais, execute capturar-telas.ps1" -ForegroundColor White
Write-Host "3. Substitua os .svg por .png das capturas reais" -ForegroundColor White
Write-Host ""
