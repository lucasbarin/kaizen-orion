# Guias de Utiliza\u00e7\u00e3o do Sistema Orion Kaizen

Este diret\u00f3rio cont\u00e9m guias navegaveis em HTML para colaboradores e administradores do sistema.

## \ud83d\udcda Guias Dispon\u00edveis

### Para Colaboradores
**URL:** `http://seudominio.com/guia/colaborador/`

Cont\u00e9m instru\u00e7\u00f5es sobre:
- Como fazer login
- Criar novos Kaizens
- Acompanhar status das sugest\u00f5es
- Sistema de pontos
- Resgatar produtos

### Para Administradores
**URL:** `http://seudominio.com/guia/admin/`

Documenta\u00e7\u00e3o completa de administra\u00e7\u00e3o:
- Analisar e aprovar Kaizens
- Gerenciar colaboradores e permiss\u00f5es
- Cadastrar produtos no cat\u00e1logo
- Configurar tipos e par\u00e2metros
- Exportar relat\u00f3rios
- Troubleshooting e manuten\u00e7\u00e3o

## \ud83d\uddbc\ufe0f Imagens Ilustrativas

### Imagens Atuais (SVG Placeholders)
O sistema atualmente usa imagens SVG ilustrativas geradas automaticamente. Elas s\u00e3o **funcionais e apresent\u00e1veis**, mas n\u00e3o s\u00e3o screenshots reais.

**Localiza\u00e7\u00e3o:** `/guia/imgs/`

**Imagens dispon\u00edveis:**
- `colaborador-login.svg`
- `colaborador-novo-kaizen.svg`
- `colaborador-upload.svg`
- `colaborador-minhas-sugestoes.svg`
- `colaborador-catalogo.svg`
- `admin-dashboard.svg`
- `admin-kaizens.svg`
- `admin-analisar.svg`
- `admin-colaboradores.svg`
- `admin-produtos.svg`
- `admin-config.svg`

### Como Capturar Screenshots Reais (Opcional)

Se desejar substituir os placeholders SVG por screenshots reais das telas do sistema:

#### M\u00e9todo Autom\u00e1tico (PowerShell)
```powershell
# 1. Certifique-se que o WAMP est\u00e1 rodando
# 2. Fa\u00e7a login no sistema
# 3. Execute:
.\guia\capturar-telas.ps1
```

**IMPORTANTE:** Este script captura a tela inteira, n\u00e3o apenas a janela do navegador. Voc\u00ea precisar\u00e1 recortar as imagens ap\u00f3s a captura.

#### M\u00e9todo Manual (Recomendado)
1. Abra o sistema no navegador
2. Use **Win + Shift + S** (Snipping Tool)
3. Capture cada tela importante
4. Salve como PNG em `/guia/imgs/`
5. Renomeie para substituir os arquivos `.svg` existentes:
   - `colaborador-login.svg` \u2192 `colaborador-login.png`
   - `colaborador-novo-kaizen.svg` \u2192 `colaborador-novo-kaizen.png`
   - Etc.

#### M\u00e9todo com Extens\u00e3o de Navegador
Use extens\u00f5es como:
- **Awesome Screenshot** (Chrome/Edge)
- **Fireshot** (Chrome/Edge/Firefox)
- **Nimbus Screenshot** (Chrome/Edge)

Estas extens\u00f5es permitem capturar a p\u00e1gina inteira, incluindo scroll.

## \ud83d\udee0\ufe0f Atualizar os Guias

Se voc\u00ea capturou screenshots reais em PNG:

1. Salve os arquivos em `/guia/imgs/`
2. **Renomeie de `.svg` para `.png`** nos guias HTML:

**colaborador/index.html:**
```html
<!-- Antes -->
<img src="../imgs/colaborador-login.svg" alt="...">

<!-- Depois -->
<img src="../imgs/colaborador-login.png" alt="...">
```

**admin/index.html:**
```html
<!-- Antes -->
<img src="../imgs/admin-dashboard.svg" alt="...">

<!-- Depois -->
<img src="../imgs/admin-dashboard.png" alt="...">
```

Ou fa\u00e7a uma substitui\u00e7\u00e3o em massa:
```powershell
# PowerShell - Substituir .svg por .png nos HTML
(Get-Content guia\colaborador\index.html) -replace '\.svg"', '.png"' | Set-Content guia\colaborador\index.html
(Get-Content guia\admin\index.html) -replace '\.svg"', '.png"' | Set-Content guia\admin\index.html
```

## \ud83c\udfaf Recomenda\u00e7\u00f5es para Screenshots

### Resolu\u00e7\u00e3o
- M\u00ednimo: 1200px de largura
- Ideal: 1920px de largura
- Formato: PNG (melhor qualidade) ou JPG (menor tamanho)

### Telas Priorit\u00e1rias para Capturar

**Colaborador:**
1. \u2705 Tela de login (com formul\u00e1rio vis\u00edvel)
2. \u2705 Formul\u00e1rio novo Kaizen (wizard - etapa 1)
3. \u2705 \u00c1rea de upload drag-and-drop
4. \u2705 Lista "Minhas Sugest\u00f5es" (com Kaizens de exemplo)
5. \u2705 Cat\u00e1logo de produtos (com cards de produtos)

**Admin:**
1. \u2705 Dashboard com m\u00e9tricas
2. \u2705 Lista de Kaizens (com filtros vis\u00edveis)
3. \u2705 Tela de an\u00e1lise de Kaizen (formul\u00e1rio completo)
4. \u2705 Lista de colaboradores
5. \u2705 Cadastro de produto
6. \u2705 P\u00e1gina de configura\u00e7\u00f5es

### Dicas de Captura
- Use janela maximizada do navegador
- Remova extens\u00f5es que adicionam \u00edcones \u00e0 p\u00e1gina
- Use modo an\u00f4nimo para evitar autocompletes vis\u00edveis
- Capture com dados de exemplo realistas
- Evite dados sens\u00edveis ou reais nas capturas

## \ud83d\udccc Como Compartilhar os Guias

### M\u00e9todo 1: Link Direto
Compartilhe a URL com colaboradores e gestores:
```
http://seudominio.com/guia/colaborador/
http://seudominio.com/guia/admin/
```

### M\u00e9todo 2: Adicionar ao Menu do Sistema
Adicione links nos menus principais:

**Front-end (home.php):**
```html
<li><a href="guia/colaborador/" target="_blank">
    <i class="fas fa-question-circle"></i> Ajuda
</a></li>
```

**Admin (admin/menu.php):**
```html
<li><a href="../guia/admin/" target="_blank">
    <i class="fas fa-book"></i> Guia do Admin
</a></li>
```

### M\u00e9todo 3: Email de Boas-Vindas
Inclua os links no email de cadastro de novos usu\u00e1rios.

## \ud83d\udce6 Estrutura de Arquivos

```
guia/
\u251c\u2500\u2500 colaborador/
\u2502   \u2514\u2500\u2500 index.html          # Guia do colaborador
\u251c\u2500\u2500 admin/
\u2502   \u2514\u2500\u2500 index.html          # Guia do administrador
\u251c\u2500\u2500 imgs/                    # Imagens (SVG ou PNG)
\u2502   \u251c\u2500\u2500 colaborador-*.svg
\u2502   \u2514\u2500\u2500 admin-*.svg
\u251c\u2500\u2500 criar-placeholders.ps1   # Script para gerar SVGs
\u251c\u2500\u2500 capturar-telas.ps1       # Script para screenshots (PowerShell)
\u2514\u2500\u2500 README.md                # Este arquivo
```

## \u2728 Caracter\u00edsticas dos Guias

- \u2705 Design responsivo (mobile-friendly)
- \u2705 Navega\u00e7\u00e3o sticky com scroll suave
- \u2705 \u00cdcones Font Awesome
- \u2705 Cores customizadas (Orion blue/yellow para colaborador, vermelho/preto para admin)
- \u2705 Bot\u00e3o "voltar ao topo"
- \u2705 Estrutura clara com cards de passos numerados
- \u2705 Boxes de dicas e avisos
- \u2705 Sem depend\u00eancias externas (CDN p\u00fablicos)

## \ud83d\udd04 Atualizar o Conte\u00fado

Os guias s\u00e3o arquivos HTML est\u00e1ticos. Para atualizar:

1. Edite `colaborador/index.html` ou `admin/index.html`
2. Modifique o HTML diretamente
3. Salve e recarregue a p\u00e1gina

N\u00e3o \u00e9 necess\u00e1rio reiniciar o servidor ou cache.

## \ud83d\udcdd Notas

- **SVG vs PNG:** Os placeholders SVG s\u00e3o vetoriais e escalam perfeitamente. Se substituir por PNG, use alta resolu\u00e7\u00e3o.
- **Performance:** Imagens PNG podem ser grandes. Considere otimizar com ferramentas como TinyPNG.
- **Acessibilidade:** Todas as imagens possuem atributo `alt` descritivo.
- **Manuten\u00e7\u00e3o:** Atualize os guias sempre que adicionar novas funcionalidades ao sistema.

---

**\u00daltima atualiza\u00e7\u00e3o:** 19/11/2025  
**Vers\u00e3o do Sistema:** Orion Kaizen v2.0
