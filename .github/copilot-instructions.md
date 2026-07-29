# Sistema Orion Kaizen - Instruções para Agentes de IA

## Visão Geral da Arquitetura

Sistema PHP 8.x de melhoria contínua **Kaizen com gamificação** através de pontos. Migrado de PHP 5.x em 2025.

**Duas áreas principais:**
- **Front-end** (`/`): Portal do colaborador - submeter/visualizar Kaizens, resgatar pontos
- **Admin** (`/admin/`): Gerenciamento - analisar Kaizens, processar aprovações, administrar catálogo

**Características fundamentais:**
- Código procedural PHP puro (sem frameworks, POO ou namespaces)
- Templates Adobe Dreamweaver (.dwt.php) com regiões editáveis
- MySQL via mysqli com wrapper `sql($query, $con)`
- Fluxo: formulário → processador (`func_*.php`) → redirecionamento com mensagem

## Camada de Banco de Dados (PHP 8)

### Arquivos de Conexão
```php
// ✅ SEMPRE usar (PHP 8):
include("app/conexao8.php");
include("app/funcoes8.php");

// ❌ NUNCA usar (legado PHP 5):
include("app/conexao.php");
include("app/funcoes.php");
```

### Função Wrapper sql()
```php
// Wrapper em app/funcoes8.php com fallback automático
$result = sql("SELECT * FROM colaborador WHERE id = 123", $con);

// Funções mysqli requerem $con como primeiro parâmetro:
mysqli_query($con, $query);
mysqli_error($con);
mysqli_insert_id($con);

// Estas NÃO precisam $con:
mysqli_fetch_array($result);
mysqli_num_rows($result);
```

**Codificação:** UTF-8MB4 configurado automaticamente em `conexao8.php`

## Sessões e Autenticação

### Front-end (Colaborador)
```php
include("app/sessao.php"); // Topo de páginas protegidas

// Verifica:
$_SESSION['log'] == "fgnx45#Hdf2_fg3"
$_SESSION['usu'] // ID numérico do colaborador

// Usuário carregado automaticamente:
$usuario['lider_colaborador']   // 1 = líder de setor
$usuario['gestor_colaborador']  // 1 = gestor (acesso admin)
$usuario['ponto_colaborador']   // Saldo atual de pontos
```

### Admin
```php
include("app/sessao.php"); // ou admin/app/sessao.php

// Verifica:
$_SESSION['administrator'] == "Qy2X5IVDEJ9lPlI3SMl1"
```

**Nota:** Sessões usam strings estáticas hardcoded (não são seguras criptograficamente).

## Lógica de Negócio - Sistema de Pontos

### Fluxo Completo do Kaizen

**1. Criação (Colaborador):**
```
novo-kaizen.php → app/func_cadastra_kaizen.php
├── Valida campos obrigatórios
├── Calcula complemento (se houver)
├── Insere registro: status_kaizen = 1 (pendente)
└── Envia email para gestor (app/mail_novo_kaizen.php)
```

**2. Análise (Líder/Admin):**
```
admin/kaizen-editar.php → admin/app/func_kaizen_analisar.php
├── Aprova (status = 2) ou Rejeita (status = 3)
├── Calcula pontos baseado em tipo + reducao
├── Atualiza colaborador.ponto_colaborador
├── Registra log via registraLog()
└── Envia email (app/mail_kaizen_analisado.php)
```

**3. Estados:**
- `1` = Pendente
- `2` = Aprovado (pontos concedidos)
- `3` = Rejeitado (sem pontos)

### Cálculo de Complementos (v2.0)

**Sistema de Benefícios:** Tabela `comp` define 3 tipos:
```php
// Função: calcularComplemento($tipo_id, $valor, $con)
// Localização: app/funcoes8.php linha 1136

// ID 1: Reais economizados (R$ direto)
$complemento = $valor; // Sem cálculo

// ID 2: Horas poupadas
$complemento = $horas × $custo_linha_parada; // config em pg.nome4_pg

// ID 3: Kg carbono reduzido
$complemento = $kg × ($produtividade_usd × $cambio); // pg.nome5_pg e pg.nome6_pg
```

**Armazenamento:**
- `kaizen.valor_original_kaizen`: Valor bruto informado pelo usuário
- `kaizen.complemento_kaizen`: Valor calculado em R$
- `kaizen.tipo_complemento_kaizen`: ID do complemento usado (1, 2 ou 3)

### Pontuação

**Lógica de pontos** (em `admin/app/func_kaizen_analisar.php`):
```php
// Criador principal recebe pontos cheios
// Colaboradores auxiliares (1 e 2) recebem fração
// Baseado em: tipo_kaizen + categoria/reducao
```

**Auditoria obrigatória:**
```php
registraLog($id_usuario, $pontos, $saldo_novo, $descricao, $pathConexao);
// Grava em tabela `log` toda movimentação de pontos
```

## Funções Padrão (app/funcoes8.php)

### Entrada/Validação
```php
trata($valor)            // trim() + strip_tags() + addslashes()
limpa_html($txt)         // Remove classes/styles, permite tags básicas
limpa_html2($txt)        // Versão completa (com tabelas)
validaCPF($cpf)          // Validação algorítmica de CPF
is_email($email)         // Validação via filter_var
```

### Redirecionamento e Mensagens
```php
// Redireciona com mensagem base64 encoded
volta("erro|ok|alerta", "Mensagem", "destino.php");

// Exibe mensagem recebida (em página de destino)
resp($_GET['resp'], $_GET['msg'], $_GET['tipo']);
```

### Formatação
```php
data_sql($data)          // DD/MM/YYYY → YYYY-MM-DD
data_print($data)        // Echo formatado: DD.MM.YYYY
money_sql($numero)       // "1.234,56" → 1234.56
money_print($numero)     // Echo: 1.234,56
```

### Upload de Arquivos
```php
upload_imagem($imagem, $tamanho, $pasta, $bg='')
// - Redimensiona mantendo proporção
// - Converte para JPG (qualidade 95%)
// - Nome: timestamp_hash.jpg
// - Retorna: nome do arquivo salvo

uploadAnexos($files, $id_kaizen, $con)
// - Upload múltiplo (v2.0)
// - Extensões: jpg, png, pdf, doc, xls, zip
// - Max 10MB por arquivo
// - Salva em imgs/kaizen/
// - Registra em kaizen_anexos

listarAnexos($id_kaizen, $con)
// - Retorna array de anexos do kaizen
```

## Padrões de Código

### Estrutura de Arquivos
```
[página].php              # View (formulário HTML)
├── include conexao8.php + funcoes8.php + sessao.php
├── Queries para popular dados
├── <!-- InstanceBegin template="/Templates/kaizen.dwt.php" -->
└── <!-- InstanceBeginEditable name="conteudo" -->

app/func_[acao].php       # Processador (lógica)
├── Validações
├── Manipulação de dados
└── volta("tipo", "msg", "redirect.php")
```

### Processamento de Formulários
```php
// Padrão em TODOS os func_*.php:
foreach ($_POST as $k => $v) {
    $$k = trata($v); // Cria variáveis dinamicamente
}

// Depois disso, use diretamente:
$nome_colaborador  // De $_POST['nome_colaborador']
$email_colaborador // De $_POST['email_colaborador']
```

### Queries com Joins
```php
// Padrão comum: LEFT JOIN para buscar dados relacionados
$sql = sql("SELECT k.*, c.nome_colaborador, s.nome_setor
FROM kaizen k
LEFT JOIN colaborador c ON c.id_colaborador = k.colaborador_kaizen
LEFT JOIN setor s ON s.id_setor = k.setor_kaizen
WHERE k.id_kaizen = $id LIMIT 1", $con);

$dados = mysqli_fetch_array($sql);
```

## Estrutura do Banco de Dados

### Tabelas Principais
```
colaborador
├── id_colaborador (PK)
├── nome_colaborador, email_colaborador, usuario_colaborador
├── senha_colaborador (texto plano - não seguro!)
├── ponto_colaborador (saldo de pontos)
├── lider_colaborador (1 = líder)
└── gestor_colaborador (1 = admin)

kaizen
├── id_kaizen (PK)
├── tipo_kaizen (FK → tipo)
├── complemento_kaizen (valor em R$)
├── valor_original_kaizen (valor bruto)
├── tipo_complemento_kaizen (1, 2 ou 3)
├── colaborador_kaizen (FK → colaborador)
├── colaborador1_kaizen, colaborador2_kaizen (auxiliares)
├── status_kaizen (1=pendente, 2=aprovado, 3=rejeitado)
├── setor_kaizen (FK → setor)
└── datacadastro_kaizen

log (auditoria de pontos)
├── id_log (PK)
├── id_usu (FK → colaborador)
├── ponto_log (pontos movimentados)
├── saldo_log (saldo após operação)
├── descricao_log
└── data_log

tipo (tipos de benefício - 9 tipos)
├── id_tipo (PK)
├── nome_tipo
└── complemento_tipo (FK → comp)

comp (configuração de complementos)
├── id_comp (1, 2 ou 3)
├── nome_comp
└── calculo_comp (0=sem cálculo, 1=kg carbono, 2=horas)

produto (catálogo de resgate)
├── id_produto (PK)
├── nome_produto
└── pontos_produto (custo em pontos)

logtroca (resgates)
├── id_logtroca (PK)
├── colaborador_logtroca (FK)
├── produto_logtroca (FK)
└── pontos_logtroca
```

## Ambiente de Desenvolvimento

### WAMP (Windows)
```powershell
# Caminho: c:\wamp64\www\orion-v2
# URL: http://localhost/orion-v2/
# Admin: http://localhost/orion-v2/admin/

# Banco de dados (phpMyAdmin):
# Host: localhost
# User: root
# Pass: (vazio)
# DB: orionv2_frontend (ou orionv2)
```

### Configuração (app/conexao8.php)
```php
$servidor = 'localhost';
$usuario = 'root';
$senha = '';
$banco = 'orionv2_frontend'; // ← Atualizar aqui
```

## Sistema de Templates Dreamweaver

**Comentários especiais:**
```html
<!-- InstanceBegin template="/Templates/kaizen.dwt.php" -->
<!-- InstanceBeginEditable name="doctitle" -->
<title>Título da Página</title>
<!-- InstanceEndEditable -->
```

**NÃO edite** código fora de regiões editáveis - Dreamweaver sobrescreve.

## Sistema de Email (PHPMailer v5.1)

**Config:** `app/configuracoes.php` (SMTP)

**Templates disponíveis:**
- `mail_novo_kaizen.php` - Notifica gestor de nova submissão
- `mail_kaizen_analisado.php` - Notifica colaborador de aprovação/rejeição
- `mail_alterado_kaizen.php` - Notifica de edições
- `mail_recuperar_senha.php` - Reset de senha

**Estrutura:**
```php
include("app/_top_email.php");
// Conteúdo HTML do email
include("app/_bottom_email.php");
```

## Notas de Segurança

⚠️ **Este é código legado com práticas desatualizadas:**

- **SQL Injection:** `addslashes()` não é suficiente - use `mysqli_real_escape_string($con, $valor)` em novas implementações
- **CSRF:** Sem proteção - tokens não implementados
- **Senhas:** Texto simples no banco (não usa password_hash)
- **Sessões:** IDs estáticos hardcoded (inseguro)
- **XSS:** `limpa_html()` remove alguns riscos, mas não é completo

**Ao adicionar novos recursos:**
- Mantenha consistência com padrões existentes
- Documente explicitamente se implementar melhorias de segurança
- Não quebre código legado tentando "modernizar" sem aprovação

## Checklist para Novas Funcionalidades

- [ ] Usar `conexao8.php` e `funcoes8.php`
- [ ] Incluir `sessao.php` em páginas protegidas
- [ ] Chamar `trata()` em todos os inputs
- [ ] Passar `$con` em chamadas `sql()`
- [ ] Usar `volta()` para redirecionamentos
- [ ] Registrar mudanças de pontos com `registraLog()`
- [ ] Testar em `http://localhost/orion-v2/`
- [ ] Validar UTF-8 em textos com acentos
- [ ] Verificar permissões de usuário (líder/gestor)
- [ ] Seguir convenção de nomes: `nome_tabela`, `id_tabela`
