# Orion Kaizen v2 — Guia do Sistema para Agentes

> **Fonte única de verdade** sobre como o sistema funciona. Substitui os antigos `SETUP.md`, `DEPLOY.md`,
> `README_FRONTEND.md`, `RELATORIO_FINALIZACAO.md`, `VERIFICACAO_COMPLETA_v2.1.md`, `docs/historico/*`,
> `guia/README.md` e `.github/copilot-instructions.md`.
>
> **Regra de manutenção:** toda mudança de comportamento, tabela, fluxo, arquivo estrutural ou configuração
> deve ser refletida aqui no mesmo commit. Registre a mudança em "Histórico" no final.

---

## 1. Visão geral

Sistema de melhoria contínua **Kaizen com gamificação por pontos** da Orion Engineered Carbons.
Colaboradores cadastram sugestões (Kaizens); o Gestor Kaizen responsável aprova/reprova/devolve; aprovações
geram pontos que são trocados por produtos de um catálogo.

- **Status:** em produção desde 02/02/2026 (v2). O sistema V1 continua acessível só para consulta
  (`https://orionprovisorio2.websiteseguro.com`, linkado na home e no menu admin como "Sistema anterior").
- **Hospedagem:** Locaweb (MySQL `*.mysql.dbaas.com.br`). Deploy manual via FTP.
- **Repositório:** `https://github.com/lucasbarin/kaizen-orion` (branch `main`).
- **Duas áreas:**
  - Front-end do colaborador: raiz (`/`)
  - Painel administrativo: `/admin/` (login separado)

### Stack

| Camada | Tecnologia |
|---|---|
| Backend | PHP 8.x procedural (sem framework, sem classes/namespaces, sem Composer) |
| Banco | MySQL 8, extensão `mysqli`, charset `utf8mb4` (tabelas majoritariamente MyISAM) |
| UI | Bootstrap 5.3.2 + Font Awesome 6.5.1 + fonte Inter (todos via CDN), jQuery 1.11.3 local |
| Admin | Bootstrap 5, DataTables 1.13.7, Chart.js 4.4 (relatórios) via CDN |
| E-mail | PHPMailer 6.7.1 (`app/PHPMailer-6.7.1/`) via SMTP SSL |
| Templates | Adobe Dreamweaver (`Templates/*.dwt.php`) — só marcação de design-time |

---

## 2. Ambientes e configuração

### `app/conexao8.php` (único arquivo de conexão)

Detecta o ambiente pelo `HTTP_HOST`:

- **local** (`localhost`/`127.0.0.1`): `root` sem senha, banco `orionv2_frontend`, `$urlSite = http://localhost/orion-v2/`.
- **produção** (qualquer outro host): banco `orionv226` na Locaweb, `$urlSite = https://orion2026proviso1.websiteseguro.com/`.

Define a variável global **`$con`** (conexão mysqli) e força `SET NAMES utf8mb4`.

Observações:
- ⚠️ As credenciais de produção (banco e SMTP) estão **versionadas** em `app/conexao8.php` e `app/configuracoes.php`.
  Não as copie para outros arquivos, logs ou respostas.
- O topo do arquivo tem um bloqueio "Sistema em atualização" válido até 02/02/2026 (liberado para um IP).
  A data já passou, então é código morto — pode ser removido quando for conveniente.

### `.htaccess` (raiz)

```apache
RewriteEngine On
RewriteCond %{SERVER_PORT} 80
RewriteRule ^(.*)$ https://www.orionkaizen.com.br/$1 [R,L]
```

Força HTTPS no domínio de produção. **Atenção no ambiente local:** em WAMP com `mod_rewrite` ativo,
`http://localhost/orion-v2/` é redirecionado (302) para produção. Para testar localmente, desative
temporariamente o `.htaccess` (renomear) **sem commitar**, ou use HTTPS local. Nunca envie um `.htaccess`
alterado para produção sem intenção.

### Ambiente local (WAMP, Windows)

- Caminho: `c:\wamp64\www\orion-v2` — URL: `http://localhost/orion-v2/` e `/admin/`
- Banco local: `orionv2_frontend` (importar um dump de produção; dumps ficam em `!ARQUIVOS/`, fora do git)
- Pasta de uploads com escrita: `imgs/kaizen/` (anexos) e `imgs/` (imagens de produtos)
- `short_open_tag` precisa estar **On** (há muitos `<?` sem `php` — ver armadilhas)

---

## 3. Estrutura de diretórios

```
/                          Páginas do colaborador (uma página = um .php)
├── app/                   Núcleo compartilhado
│   ├── conexao8.php       Conexão + detecção de ambiente ($con)
│   ├── funcoes8.php       Biblioteca de funções (sql, trata, volta, resp, uploads, complementos…)
│   ├── sessao.php         Guarda de páginas do colaborador (carrega $usuario)
│   ├── sessao2.php        Guarda dos processadores app/func_*.php (redireciona para ../index.php)
│   ├── configuracoes.php  Credenciais SMTP
│   ├── func_*.php         Processadores de formulários do front
│   ├── mail_*.php         Montagem e envio de e-mails (PHPMailer 6.7.1)
│   ├── _top_email.php / _bottom_email.php   Cabeçalho/rodapé HTML dos e-mails
│   ├── timthumb.php       Redimensionador de imagens (usado pelas funções tim*() de funcoes8)
│   ├── paginacao.php      Helper de paginação legado (não incluído atualmente)
│   └── PHPMailer-6.7.1/   Biblioteca (não editar)
├── admin/                 Painel administrativo
│   ├── app/               Processadores do admin (func_*.php), sessao.php, logout.php
│   ├── assets/            Tema Metronic legado + plugins (vendor; não editar)
│   ├── gerador/           Gerador de CRUD legado (BS3). Não usado pelo sistema atual
│   ├── menu.php           Menu lateral (incluído em todas as páginas admin)
│   └── _top_*/_bot_*.php  Cabeçalho/rodapé do tema antigo (usados só pelo gerador e módulos legados)
├── Templates/             kaizen-bs5.dwt.php (front) e admin-bs5.dwt.php (admin) — Dreamweaver
├── assets/css/estilos-v2.css   CSS compartilhado do front v2 (headers, wizard, upload, anexos, cards)
├── lib/css/               orion-custom.css (variáveis/cores BS5), admin-orion.css (admin),
│                          visualizar-kaizen-print.css (impressão), style/menu/tabela/pace (legado)
├── lib/js/                jQuery 1.11.x, maskMoney, inputmask, functions.js, admin-base.js
├── imgs/                  Uploads (imagens de produtos) — não versionar novos uploads à toa
│   └── kaizen/            Anexos dos Kaizens
├── guia/                  Manuais HTML estáticos para usuários (colaborador/ e admin/) + imgs SVG
├── _status.php            Snippet que renderiza o badge de status (usa $status)
├── !ARQUIVOS/             (ignorado no git) dumps SQL, SQLs de alteração, backups, PSD
└── backups/               (ignorado no git) zips de backup antigos
```

---

## 4. Autenticação e papéis

### Colaborador (front)

- Login: `index.php` → `app/func_login.php` (tabela `colaborador`, campo `usuario_colaborador` + `senha_colaborador`
  em **texto plano**). "Lembrar-me" grava cookies `orion_user` / `orion_pass` (senha em base64) por 30 dias.
- Sessão: `$_SESSION['log'] == 'fgnx45#Hdf2_fg3'` e `$_SESSION['usu']` = `id_colaborador`.
- `app/sessao.php` (topo das páginas protegidas) valida a sessão e carrega **`$usuario`** =
  `colaborador LEFT JOIN setor ON setor.lider_setor = colaborador.id_colaborador`.
  Se `alterarsenha_colaborador = 1`, força redirecionamento para `altera-senha.php` (a página define `$pgAltera = 1`).
- Esqueci a senha: `esqueci_senha.php` → `app/func_esqueci_senha.php` gera senha `temp#####`, marca
  `alterarsenha_colaborador = 1` e envia por e-mail (`mail_recuperar_senha.php`).
- Logout: `app/func_logout.php`.

### Papéis do colaborador (nomenclatura confusa — atenção)

| Papel na UI | Como é determinado | Acesso |
|---|---|---|
| Colaborador | qualquer usuário logado | criar/editar próprios Kaizens, trocar pontos |
| **Gestor Kaizen** | `colaborador.lider_colaborador = 1` | `sugestoes-analisar.php` + `analisar-kaizen.php` (aprovar/reprovar/devolver) |
| Gestor responsável | `colaborador.gestor_colaborador` = **ID** do Gestor Kaizen daquele colaborador | só esse gestor analisa os Kaizens do colaborador e recebe os e-mails |
| **Gestor/Líder de Setor** | existe `setor.lider_setor = id_colaborador` (vira `$usuario['nome_setor']`) | `lider-analisar.php` (acompanha os Kaizens do setor) |

> `gestor_colaborador` **não** é flag de admin: é uma FK para outro colaborador.
> O admin é um login totalmente separado (abaixo).

### Admin

- Login: `admin/index.php` contra a tabela **`login`** (`nome_login` / `senha_login`, texto plano).
  Cookies "lembrar-me": `orion_admin_user` / `orion_admin_pass`.
- Sessão: `$_SESSION['administrator'] == 'Qy2X5IVDEJ9lPlI3SMl1'`, verificada por `admin/app/sessao.php`
  no topo das páginas admin. Troca de senha: `admin/login-editar.php`.
- ⚠️ Os processadores em `admin/app/func_*.php` **não incluem** `sessao.php` (ex.: `func_kaizen_analisar.php`,
  `func_troca.php`, `func_kaizen_apagar.php`). Ao criar/alterar um processador admin, inclua a checagem.

---

## 5. Fluxo do Kaizen

### Status (`kaizen.status_kaizen`, renderizado por `_status.php`)

| Valor | Significado |
|---|---|
| 1 | Aguardando análise |
| 2 | Aprovado / "Implementado" (gera pontos) |
| 3 | Reprovado |
| 4 | Devolvido para ajuste (colaborador pode editar) |
| 5 | N.A. (apenas dados legados; nenhum fluxo atual grava 5) |

### Passo a passo

1. **Criação** — `novo-kaizen.php` (wizard de 3 etapas) → `app/func_cadastra_kaizen.php`
   - Valida cadastro do colaborador (setor/unidade válidos), tipo, colaboradores auxiliares não repetidos
     (`colaborador1`, `colaborador2`) e valor do benefício quando o tipo exige.
   - Insere com `status_kaizen = 1`, `unidade_kaizen`/`setor_kaizen` copiados do colaborador.
   - Anexos múltiplos via `uploadAnexos()` (obrigatório ≥ 1 anexo no front).
   - E-mail ao gestor responsável (`mail_novo_kaizen.php`).
   - O INSERT não usa `die()`: em erro, grava `error_log` e volta com mensagem amigável.
2. **Análise pelo Gestor Kaizen (front)** — `sugestoes-analisar.php` lista Kaizens de colaboradores cujo
   `gestor_colaborador` é o usuário logado → `analisar-kaizen.php` → `app/func_kaizen_analisar.php?kaizen=ID&analise=2|3|4`
   - Exige `lider_colaborador = 1`, ser o gestor responsável e o Kaizen estar em status 1.
   - `4` (devolver) grava `obs_kaizen` com o motivo; não mexe em pontos.
   - `2` (aprovar) credita pontos (ver seção 6). `3` apenas finaliza.
3. **Edição após devolução** — `kaizen-editar.php` → `app/func_edita_kaizen.php`
   - Só o autor e só se `status_kaizen = 4`. Permite excluir anexos existentes e enviar novos
     (`novos_anexos`). Volta o status para `1` e notifica o gestor (`mail_alterado_kaizen.php`).
4. **Análise/revisão pelo admin** — `admin/kaizen-admin.php` → `admin/kaizen-editar.php` →
   `admin/app/func_kaizen_analisar.php?kaizen=ID&analise=2|3`
   - Pode reanalisar Kaizens já finalizados: aprovado→reprovado **estorna** pontos; reprovado→aprovado credita.
   - Grava `admin_kaizen = 1` (colaborador ID 1 é usado como "admin").
5. **Outras ações admin:** `kaizen-indicar.php` (troca tipo/autor/auxiliares), `app/func_kaizen_apagar.php`
   (DELETE físico), `kaizen-busca.php` (relatórios com gráficos Chart.js), `kaizen-filtrar.php` (filtros),
   `kaizen-exportar.php` (Excel via tabela HTML, `Windows-1252`).

---

## 6. Pontos e trocas

### Pontuação

- Configurada em `pg` id **101** (`admin/pg-editar.php?id_pg=101`, menu "Info. base sistema"):
  `nome1_pg` = pontos do **criador** (padrão 2 se vazio), `nome2_pg` = pontos de cada **auxiliar** (padrão 1).
- Aprovação: `colaborador.ponto_colaborador += pontos` para o criador e para `colaborador1..4_kaizen` preenchidos;
  `kaizen.pontos_kaizen` recebe os pontos do criador.
- **Toda movimentação de pontos deve chamar** `registraLog($id_usu, $ponto, $saldo, $descricao, $pathConexao)`
  → tabela `log` (`usu_log`, `ponto_log` [negativo em débitos], `saldo_log` = saldo **antes** da operação,
  `descricao_log` até 100 caracteres, `data_log`). O extrato (`extrato-pontos.php`, `admin/colaborador-extrato.php`) lê daqui.
- A tabela `reducao` (faixas de pontos por custo/tempo) é do modelo V1 e **não é mais usada** para pontuar.

### Trocas (resgate de produtos)

- Catálogo: `categorias.php` (tabelas `catproduto`, `produto`) → `app/func_pontos_trocar.php?produto=ID`
  - Verifica saldo, insere em `logtroca` com `status_logtroca = 1`, debita pontos na hora e registra log negativo.
- Histórico do colaborador: `minhas-trocas.php`.
- Admin: `admin/troca-admin.php` → `admin/app/func_troca.php?id_logtroca=ID&acao=2|3`
  - `2` = aceita (sem mudança de saldo) · `3` = recusada/devolvida → **devolve** os pontos e registra log.

---

## 7. Tipos de benefício e complementos (v2.1)

Desde a v2.1 (dez/2025) o colaborador informa o valor **diretamente** (R$ ou horas). Não há mais cálculo.

**`comp`** (tipo de valor):

| id | nome | `tipo_entrada` | unidade exibida |
|---|---|---|---|
| 1 | Reais economizados (anual) | reais | R$ |
| 2 | Ganho R$ (anual) | reais | R$ |
| 3 | Horas Poupadas (anual) | horas | horas |

**`tipo`** (categoria do Kaizen; `complemento_tipo` → `comp.id_comp`, 0 = sem valor; `add_tipo = 1` exibe o campo texto `add_tipo_texto`):

| id | nome | complemento | campo extra |
|---|---|---|---|
| 1 | Segurança | 0 | — |
| 2 | Meio Ambiente | 1 | — |
| 3 | Qualidade - Defeitos | 1 | — |
| 4 | Qualidade - Defeitos | 3 | — |
| 5 | Fluxo de trabalho | 3 | — |
| 6 | Redução de Custo | 1 | — |
| 7 | Produtividade | 2 | — |
| 8 | Outros | 1 | sim |
| 9 | Outros | 2 | sim |
| 10 | Outros | 0 | — (adicionado em 11/12/2025) |

Campos gravados no Kaizen:
- `valor_original_kaizen` — valor informado (R$ ou horas). **É o campo a usar.**
- `tipo_complemento_kaizen` — id do `comp` usado (0 = nenhum).
- `complemento_kaizen` — **deprecado** (sempre 0). `calcularComplemento()` também é deprecada e retorna 0.
- Legado V1 (fallback em telas e exportação): `custo_kaizen`, `tempo_kaizen`, `reducao_kaizen`, `incidente_kaizen`, `categoria_kaizen`.

Exibição: sempre `formatarValorComplemento($valor, $comp_id)` (`R$ 1.234,56` ou `1.234 horas`, `-` se vazio).
Agregações: some R$ com `tipo_complemento_kaizen IN (1,2)` e horas com `= 3` — nunca misture.

Outros campos de `pg` 101: `nome3_pg` = limite (R$) para alerta de "Alta Redução" (`texto3_pg` = texto do alerta
no formulário); `nome4_pg`/`nome5_pg`/`nome6_pg` = custo linha parada, produtividade USD e câmbio (resquícios
do cálculo antigo; ainda lidos em `novo-kaizen.php`, mas sem efeito).

---

## 8. Anexos

- `uploadAnexos($_FILES['anexos'], $id_kaizen, $con)` em `funcoes8.php`: extensões `jpg jpeg png gif pdf doc docx xls xlsx zip rar`,
  máx. 10 MB por arquivo, salva em `imgs/kaizen/{time}_{i}_{uniqid}.{ext}` e registra em `kaizen_anexos`.
- O caminho de destino é relativo (`../imgs/kaizen/`): **só funciona chamado de dentro de `app/`**.
- `listarAnexos($id_kaizen, $con)` retorna o array de anexos.
- Campos `imagem1_kaizen`/`imagem2_kaizen` são do V1 (legado).

---

## 9. E-mails

- Credenciais SMTP: `app/configuracoes.php` (`$conta_envio`, `$senha_email`, `$host_smtp`, `$porta_smtp`, `$nome_remetente`).
- Padrão de cada `mail_*.php`: inclui `funcoes8`/`conexao8`/`configuracoes` com `__DIR__`, carrega PHPMailer 6.7.1,
  monta `$corpo` entre `_top_email.php` e `_bottom_email.php`, envia para `$para`. Falhas vão para `error_log`, sem interromper o fluxo.
  Para um e-mail novo, copie `mail_novo_kaizen.php`.

| Arquivo | Disparado por | Destinatário |
|---|---|---|
| `mail_novo_kaizen.php` | `func_cadastra_kaizen.php` | gestor responsável |
| `mail_alterado_kaizen.php` | `func_edita_kaizen.php` | gestor responsável |
| `mail_kaizen_analisado.php` | `app/` e `admin/app/func_kaizen_analisar.php` | e-mail do gestor (comportamento atual) |
| `mail_recuperar_senha.php` | `func_esqueci_senha.php` | colaborador |

⚠️ Os dois `func_kaizen_analisar.php` incluem o e-mail via `$_SERVER['DOCUMENT_ROOT']."/app/..."`. Funciona em
produção (site na raiz do domínio), mas **não localmente** (site em `/orion-v2/`) — o `include` falha com warning.

---

## 10. Funções principais (`app/funcoes8.php`)

| Função | Uso |
|---|---|
| `sql($query, $con)` | `mysqli_query` + `die(mysqli_error)` em erro. Sem `$con` usa a global |
| `trata($v)` | `trim` + `strip_tags` + `addslashes` (sanitização padrão de inputs) |
| `limpa_html($txt)` / `limpa_html2($txt)` | limpa HTML de textos longos (básico / com tabelas) |
| `volta($tipo, $msg, $url)` | redireciona para `$url?resp=1&msg=base64&tipo=ok|erro|alerta` e dá `exit` |
| `resp($_GET['resp'], $_GET['msg'], $_GET['tipo'])` | imprime o alerta Bootstrap na página de destino |
| `registraLog(...)` | auditoria de pontos (seção 6) |
| `formatarValorComplemento()` / `getUnidadeComplemento()` | exibição de valores (seção 7) |
| `uploadAnexos()` / `listarAnexos()` | anexos (seção 8) |
| `upload_imagem($img, $tam, $pasta)` | upload + redimensionamento de imagem (produtos) |
| `data_sql()` / `data_print()` / `money_sql()` / `money_print()` | conversões BR ↔ SQL (`*_print` dão `echo`) |
| `is_email()`, `validaCPF()`, `resumir()`, `remove_acentos()` | utilitários |

---

## 11. Padrões de código

### Página (view)

```php
<?php
include("app/conexao8.php");
include("app/funcoes8.php");
include("app/sessao.php");        // admin: include("app/sessao.php") + ../app/conexao8.php + ../app/funcoes8.php
// queries da página…
?>
<!DOCTYPE html>
<html lang="pt-br"><!-- InstanceBegin template="/Templates/kaizen-bs5.dwt.php" ... -->
```

- Front v2: Bootstrap 5 via CDN, `lib/css/orion-custom.css` + `assets/css/estilos-v2.css`, CSS específico inline no `<head>`.
  Algumas páginas (ex.: `home.php`, `novo-kaizen.php`) são HTML completo sem marcação de template.
- Admin: `Templates/admin-bs5.dwt.php`, `lib/css/admin-orion.css`, `lib/js/admin-base.js`, `<?php include("menu.php"); ?>`.
- Os `.dwt.php` **não são incluídos em runtime**; o HTML do template está copiado em cada página.
  Mudança de layout comum = editar cada página (e o `.dwt.php` se quiser manter o Dreamweaver coerente).
- Cores Orion: azul `#009ee3` (hover `#0088c7`), amarelo `#fcd700`, verde `#00812e`, vermelho `#c00003`, texto `#565656`.

### Processador (`func_*.php`)

```php
foreach ($_POST as $k => $v) { $$k = trata($v); }   // cria $nome_campo a partir do POST
// validações → volta("erro", "Mensagem", "../pagina.php");
// SQL com sql("...", $con)
volta("ok", "Mensagem", "../destino.php");
```

- Caminhos de `volta()` são relativos ao processador (`../` a partir de `app/` ou `admin/app/`).
- Em código novo prefira `intval()` para IDs e `mysqli_real_escape_string($con, …)` para textos (como em
  `func_cadastra_kaizen.php`), mantendo o estilo procedural.
- Convenção do banco: `id_tabela`, `campo_tabela` (ex.: `nome_colaborador`, `status_kaizen`).
- Não "modernize" arquivos que não fazem parte da tarefa.

---

## 12. Banco de dados (tabelas em uso)

| Tabela | Papel |
|---|---|
| `colaborador` | usuários do front: login/senha, `unidade_colaborador`, `setor_colaborador`, `lider_colaborador` (Gestor Kaizen), `gestor_colaborador` (FK gestor), `ponto_colaborador` (saldo), `status_colaborador`, `alterarsenha_colaborador` |
| `kaizen` | sugestões (campos nas seções 5 e 7; auxiliares `colaborador1..4_kaizen`; textos `situacao_atual`, `onde_kaizen`, `resultado_kaizen`, `obs_kaizen`) |
| `kaizen_anexos` | anexos (`id_kaizen`, `nome_arquivo`, `nome_original`, `tipo_arquivo`, `tamanho_arquivo`, `data_upload`) |
| `tipo`, `comp` | categorias e tipo de valor (seção 7) |
| `setor` | `nome_setor`, `unidade_setor`, `lider_setor` (FK colaborador), `status_setor` |
| `unidade` | Matriz, Fábrica Paulínia, Escritório São Paulo |
| `log` | extrato/auditoria de pontos |
| `produto`, `catproduto` | catálogo de trocas (`pontos_produto`, `imagem1_produto`, `status_produto`) |
| `logtroca` | solicitações de troca (`status_logtroca` 1 aguardando · 2 aceita · 3 recusada) |
| `pg` | configurações genéricas (`nome1..9_pg`, `texto1..9_pg`); **id 101** = parâmetros do Kaizen (rótulos em `admin/pg-config.php`) |
| `login` | usuários do admin |
| `opcao`, `status` | listas auxiliares (Sim/Não, Ativo/Excluído) |

Legado sem uso no fluxo atual: `categoria`, `reducao`, `complemento`, `fotopg`, `tabela`, `lidersetor`.
Tabelas de `colaborador`, `kaizen`, `log`, `logtroca`, `produto` foram migradas do V1 em jan/2026 (IDs preservados).

---

## 13. Armadilhas conhecidas

1. **Codificação mista.** A maioria dos arquivos é UTF-8, mas alguns são ANSI/Latin-1 (ex.: `app/func_login.php`,
   `app/func_pontos_trocar.php`, `admin/app/func_troca.php`) e alguns processadores já contêm acentos corrompidos (`�`).
   `app/func_altera_senha.php` e `app/func_esqueci_senha.php` têm BOM. **Preserve a codificação original ao editar.**
2. **Short tags `<?`** são usadas em vários arquivos (inclusive `_status.php`, usado em produção). O servidor precisa de
   `short_open_tag = On`. Em código novo use sempre `<?php` / `<?=`.
3. `sql()` faz `die()` com a mensagem do MySQL em qualquer erro — em fluxos sensíveis use `mysqli_query` e trate o erro.
4. `$$k = trata($v)` cria variáveis a partir de qualquer campo do POST/GET: cuidado com colisão de nomes (`$con`, `$usuario`…).
5. Localmente o `.htaccess` redireciona para produção (seção 2) e os e-mails de análise não são incluídos (seção 9).
6. `getRoot()` aponta para `/rastreabilidade/` (herança de outro projeto) — não use.
7. Exportação Excel (`admin/kaizen-exportar.php`) envia `charset=Windows-1252`.

## 14. Segurança — dívida técnica conhecida

Senhas em texto plano (colaborador e admin) e em cookies base64; SQL por concatenação com `addslashes`; sem CSRF;
tokens de sessão fixos; processadores admin sem checagem de sessão; `admin/gerador/` acessível sem login e capaz de
escrever arquivos; credenciais versionadas. Ao mexer nesses pontos, documente aqui e não quebre o comportamento atual
sem alinhamento.

---

## 15. Deploy (FTP)

1. Testar localmente (ver ressalva do `.htaccess`).
2. Se houver alteração de banco, gerar o `.sql` (guardar em `!ARQUIVOS/`) e aplicar em produção pelo phpMyAdmin **antes**
   de subir o PHP que depende dele. Fazer backup da tabela afetada.
3. Enviar via FTP apenas os arquivos alterados. **Não enviar:** `*.md`, `.git/`, `.github/`, `.vscode/`, `!ARQUIVOS/`,
   `backups/`, `*.zip`, `*.rar`, `*.sql`.
4. Não sobrescrever `app/conexao8.php`, `app/configuracoes.php` e `.htaccess` sem necessidade.
5. Pastas com escrita no servidor: `imgs/` e `imgs/kaizen/`.
6. Validar em produção: login, criar Kaizen com anexo, análise, e-mail, troca.

---

## 16. Itens fora do sistema (não mexer sem pedido)

- `!ARQUIVOS/` e `backups/`, `backup_php5_legado_*.zip` — arquivo morto local (ignorado no git).
- `admin/gerador/` + `admin/_top_*.php` / `_bot_*.php` — gerador de CRUD legado (gera código BS3 com short tags).
- Módulos admin legados sem link no menu: `lidersetor-*`, `tabela-*`, `fotopg-editar.php` (e seus `admin/app/func_*`).
- `admin/assets/` (tema Metronic) — ainda fornece o logo do header admin (`assets/admin/layout/img/logo.png`)
  e plugins usados por páginas legadas; não remover.
- `guia/` — manuais HTML para usuários finais; atualizar quando o fluxo mudar.

---

## Histórico

- **2026-09-29** — Documentação unificada neste arquivo; removidos `.md` antigos, scripts `.ps1` pontuais, scripts de
  migração (PHP 5→8 e V1→V2), e-mails `_OLD`/PHPMailer 5.1, módulos admin de CMS sem tabela (album, banner, noticia,
  foto, curso, teste) e arquivos de teste (`info.php`, `teste.html`, `admin/ver.html`).
- **2026-07-29** — Hotfix no cadastro de Kaizen (sem tela branca; validação de setor/unidade) e leitura de pontos.
- **2026-02-02** — Lançamento da v2 em produção (banco `orionv226`), dados migrados do V1 em 28/01/2026.
- **2025-12-11** — Tipo 10 "Outros" sem complemento.
- **2025-12-01** — v2.1: valores informados diretamente (R$/horas); `complemento_kaizen` deprecado.
- **2025-11** — v2.0: migração PHP 5→8 (mysqli, `conexao8`/`funcoes8`), Bootstrap 3→5, anexos múltiplos, PHPMailer 6.7.1.
