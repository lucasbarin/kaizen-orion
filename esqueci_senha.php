<?php
@session_start();
include("app/conexao8.php");
include("app/funcoes8.php");

$step = 1;
$usuario_informado = '';
$email_oculto = '';
$erro_msg = '';

// Processar formulário da etapa 1
if (!empty($_POST['usuario'])) {
	$login = trata($_POST['usuario']);
	$usuario_informado = $login;
	
	$sql = mysqli_query($con, "SELECT * FROM colaborador WHERE usuario_colaborador = '$login' LIMIT 1") or die(mysqli_error($con));
	
	if (mysqli_num_rows($sql)) {
		$dados = mysqli_fetch_array($sql);
		$mail = $dados['email_colaborador'];
		
		if (!empty($mail) && is_email($mail)) {
			// Email válido - mostrar etapa 2
			$step = 2;
			$email_oculto = substr_replace($mail, '*****', 1, strpos($mail, '@') - 2);
		} else {
			// Sem email cadastrado
			$step = 3;
			$erro_msg = "Não é possível recuperar sua senha, pois seu usuário não possui e-mail cadastrado. Entre em contato com o administrador do sistema.";
		}
	} else {
		// Usuário não encontrado
		$step = 1;
		$erro_msg = "Usuário não encontrado.";
	}
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	
	<title>Recuperar Senha - Sistema Orion Kaizen</title>
	
	<meta name="description" content="Sistema de Melhoria Contínua Kaizen">
	<meta name="author" content="Orion">
	
	<link rel="shortcut icon" href="favicon.ico">
	
	<!-- Bootstrap 5.3 CSS -->
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
	
	<!-- Font Awesome 6 -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
	
	<!-- Google Fonts Inter -->
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
	
	<style>
		:root {
			--orion-primary: #009ee3;
			--orion-primary-dark: #007ab8;
			--orion-secondary: #fcd700;
			--orion-text: #2c3e50;
			--orion-text-light: #7f8c8d;
		}
		
		* {
			margin: 0;
			padding: 0;
			box-sizing: border-box;
		}
		
		body {
			font-family: 'Inter', sans-serif;
			min-height: 100vh;
			display: flex;
			align-items: center;
			justify-content: center;
			background: linear-gradient(135deg, #009ee3 0%, #007ab8 100%);
			position: relative;
			overflow: hidden;
		}
		
		body::before {
			content: '';
			position: absolute;
			top: 0;
			left: 0;
			right: 0;
			bottom: 0;
			background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="100" height="100" patternUnits="userSpaceOnUse"><path d="M 100 0 L 0 0 0 100" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100%" height="100%" fill="url(%23grid)"/></svg>');
			animation: gridMove 20s linear infinite;
		}
		
		@keyframes gridMove {
			0% { transform: translate(0, 0); }
			100% { transform: translate(100px, 100px); }
		}
		
		.floating-shapes {
			position: absolute;
			width: 100%;
			height: 100%;
			overflow: hidden;
			pointer-events: none;
		}
		
		.shape {
			position: absolute;
			opacity: 0.1;
			animation: float 20s infinite ease-in-out;
		}
		
		.shape:nth-child(1) {
			width: 80px;
			height: 80px;
			background: var(--orion-secondary);
			border-radius: 50%;
			top: 10%;
			left: 10%;
			animation-delay: 0s;
		}
		
		.shape:nth-child(2) {
			width: 120px;
			height: 120px;
			background: white;
			border-radius: 30% 70% 70% 30% / 30% 30% 70% 70%;
			top: 70%;
			left: 80%;
			animation-delay: 2s;
		}
		
		.shape:nth-child(3) {
			width: 100px;
			height: 100px;
			background: rgba(255, 255, 255, 0.3);
			border-radius: 50%;
			top: 40%;
			left: 90%;
			animation-delay: 4s;
		}
		
		@keyframes float {
			0%, 100% { transform: translate(0, 0) rotate(0deg); }
			25% { transform: translate(30px, -30px) rotate(90deg); }
			50% { transform: translate(-20px, 20px) rotate(180deg); }
			75% { transform: translate(20px, 30px) rotate(270deg); }
		}
		
		.recovery-container {
			position: relative;
			z-index: 1;
			width: 100%;
			max-width: 500px;
			padding: 0 1.5rem;
		}
		
		.recovery-card {
			background: rgba(255, 255, 255, 0.95);
			backdrop-filter: blur(20px);
			border-radius: 1.5rem;
			box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
			padding: 3rem;
			border: 1px solid rgba(255, 255, 255, 0.3);
		}
		
		.logo-container {
			text-align: center;
			margin-bottom: 2rem;
		}
		
		.logo-container img {
			max-width: 180px;
			height: auto;
			margin-bottom: 1rem;
		}
		
		.logo-container h1 {
			font-size: 1.5rem;
			font-weight: 700;
			color: var(--orion-primary);
			margin-bottom: 0.5rem;
		}
		
		.logo-container p {
			color: var(--orion-text-light);
			font-size: 0.9rem;
		}
		
		.steps {
			display: flex;
			justify-content: center;
			margin-bottom: 2rem;
			gap: 1rem;
		}
		
		.step-indicator {
			width: 40px;
			height: 40px;
			border-radius: 50%;
			background: #e0e0e0;
			color: #666;
			display: flex;
			align-items: center;
			justify-content: center;
			font-weight: 600;
			transition: all 0.3s ease;
		}
		
		.step-indicator.active {
			background: var(--orion-primary);
			color: white;
			box-shadow: 0 4px 12px rgba(0, 158, 227, 0.3);
		}
		
		.form-group {
			position: relative;
			margin-bottom: 1.5rem;
		}
		
		.form-group label {
			display: block;
			margin-bottom: 0.5rem;
			color: var(--orion-text);
			font-weight: 500;
			font-size: 0.95rem;
		}
		
		.form-group input {
			width: 100%;
			border: 2px solid #e0e0e0;
			border-radius: 0.75rem;
			padding: 0.875rem 1rem 0.875rem 3rem;
			font-size: 1rem;
			transition: all 0.3s ease;
			background: white;
			color: var(--orion-text);
		}
		
		.form-group input::placeholder {
			color: #aaa;
		}
		
		.form-group input:focus {
			border-color: var(--orion-primary);
			box-shadow: 0 0 0 0.2rem rgba(0, 158, 227, 0.15);
			outline: none;
		}
		
		.input-icon {
			position: absolute;
			left: 1rem;
			bottom: 0.875rem;
			color: var(--orion-primary);
			font-size: 1.1rem;
			z-index: 5;
		}
		
		.btn-primary {
			width: 100%;
			padding: 1rem;
			background: linear-gradient(135deg, var(--orion-primary) 0%, var(--orion-primary-dark) 100%);
			border: none;
			border-radius: 0.75rem;
			color: white;
			font-weight: 600;
			font-size: 1.05rem;
			cursor: pointer;
			transition: all 0.3s ease;
			box-shadow: 0 4px 15px rgba(0, 158, 227, 0.3);
		}
		
		.btn-primary:hover {
			transform: translateY(-2px);
			box-shadow: 0 6px 20px rgba(0, 158, 227, 0.4);
		}
		
		.btn-secondary {
			width: 100%;
			padding: 0.875rem;
			background: white;
			border: 2px solid var(--orion-primary);
			border-radius: 0.75rem;
			color: var(--orion-primary);
			font-weight: 600;
			font-size: 1rem;
			cursor: pointer;
			transition: all 0.3s ease;
			margin-top: 1rem;
		}
		
		.btn-secondary:hover {
			background: var(--orion-primary);
			color: white;
		}
		
		.alert {
			border-radius: 0.75rem;
			padding: 1rem;
			margin-bottom: 1.5rem;
			border: none;
			display: flex;
			align-items: flex-start;
			gap: 0.75rem;
		}
		
		.alert-danger {
			background: #fee;
			color: #c33;
		}
		
		.alert-info {
			background: #d1ecf1;
			color: #0c5460;
		}
		
		.alert i {
			font-size: 1.25rem;
			flex-shrink: 0;
			margin-top: 2px;
		}
		
		.email-hint {
			background: #f8f9fa;
			padding: 1rem;
			border-radius: 0.5rem;
			margin-bottom: 1.5rem;
			text-align: center;
		}
		
		.email-hint strong {
			color: var(--orion-primary);
			font-size: 1.1rem;
		}
		
		.back-link {
			text-align: center;
			margin-top: 1.5rem;
		}
		
		.back-link a {
			color: var(--orion-primary);
			text-decoration: none;
			font-size: 0.95rem;
			transition: all 0.3s ease;
		}
		
		.back-link a:hover {
			color: var(--orion-primary-dark);
			text-decoration: underline;
		}
		
		@media (max-width: 576px) {
			.recovery-card {
				padding: 2rem 1.5rem;
			}
			
			.logo-container h1 {
				font-size: 1.3rem;
			}
		}
	</style>
</head>
<body>
	<div class="floating-shapes">
		<div class="shape"></div>
		<div class="shape"></div>
		<div class="shape"></div>
	</div>
	
	<div class="recovery-container">
		<div class="recovery-card">
			<div class="logo-container">
				<img src="lib/img/logo-kaizen.png" alt="Orion Kaizen">
				<h1>Recuperar Senha</h1>
				<p>Sistema de Melhoria Contínua</p>
			</div>
			
			<!-- Indicador de Etapas -->
			<div class="steps">
				<div class="step-indicator <?php echo $step == 1 ? 'active' : ''; ?>">1</div>
				<div class="step-indicator <?php echo $step == 2 ? 'active' : ''; ?>">2</div>
			</div>
			
			<?php resp($_GET['resp'] ?? null, $_GET['msg'] ?? null, $_GET['tipo'] ?? null); ?>
			
			<?php if (!empty($erro_msg)): ?>
			<div class="alert alert-danger">
				<i class="fas fa-exclamation-circle"></i>
				<div><strong>Erro!</strong> <?php echo $erro_msg; ?></div>
			</div>
			<?php endif; ?>
			
			<?php if ($step == 1): ?>
				<!-- Etapa 1: Informar Usuário -->
				<div class="alert alert-info">
					<i class="fas fa-info-circle"></i>
					<div><strong>Etapa 1:</strong> Digite seu nome de usuário para iniciar a recuperação de senha.</div>
				</div>
				
				<form action="esqueci_senha.php" method="post">
					<div class="form-group">
						<label for="usuario">Nome de Usuário</label>
						<i class="fas fa-user input-icon"></i>
						<input type="text" 
							   class="form-control" 
							   id="usuario" 
							   name="usuario" 
							   placeholder="Digite seu usuário" 
							   required 
							   autocomplete="off"
							   autofocus>
					</div>
					
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-arrow-right me-2"></i>
						Próximo
					</button>
				</form>
				
			<?php elseif ($step == 2): ?>
				<!-- Etapa 2: Confirmar Email -->
				<div class="alert alert-info">
					<i class="fas fa-info-circle"></i>
					<div><strong>Etapa 2:</strong> Complete seu e-mail corretamente para receber o link de recuperação.</div>
				</div>
				
				<div class="email-hint">
					<p class="mb-1"><small>Dica do seu e-mail:</small></p>
					<strong><?php echo $email_oculto; ?></strong>
				</div>
				
				<form action="app/func_esqueci_senha.php" method="post">
					<input type="hidden" name="usuario" value="<?php echo htmlspecialchars($usuario_informado); ?>">
					
					<div class="form-group">
						<label for="email">E-mail</label>
						<i class="fas fa-envelope input-icon"></i>
						<input type="email" 
							   class="form-control" 
							   id="email" 
							   name="email" 
							   placeholder="Digite seu e-mail completo" 
							   required 
							   autocomplete="off"
							   autofocus>
					</div>
					
					<button type="submit" class="btn btn-primary">
						<i class="fas fa-key me-2"></i>
						Recuperar Senha
					</button>
					
					<button type="button" onclick="window.location.href='esqueci_senha.php'" class="btn btn-secondary">
						<i class="fas fa-arrow-left me-2"></i>
						Voltar
					</button>
				</form>
				
			<?php elseif ($step == 3): ?>
				<!-- Erro: Sem Email Cadastrado -->
				<div class="alert alert-info">
					<i class="fas fa-headset"></i>
					<div>
						<strong>Contate o Administrador</strong><br>
						Para cadastrar um e-mail ou recuperar sua senha manualmente, entre em contato com o suporte.
					</div>
				</div>
				
				<button type="button" onclick="window.location.href='index.php'" class="btn btn-primary">
					<i class="fas fa-home me-2"></i>
					Voltar para Login
				</button>
			<?php endif; ?>
			
			<div class="back-link">
				<a href="index.php">
					<i class="fas fa-arrow-left"></i> Voltar para o login
				</a>
			</div>
		</div>
	</div>
	
	<!-- Bootstrap 5 JS Bundle -->
	<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


	<!-- InstanceBeginEditable name="doctitle" -->
<title>Kaizen</title>
<!-- InstanceEndEditable -->
	<!-- 
<meta name="twitter:card" content="summary">
<meta name="twitter:site" content="http://www.dominio.com.br">
<meta name="twitter:title" content="Kaizen">
<meta name="twitter:description" content="<? echo $resumo ?>">
<meta name="twitter:url" content="<? echo url_atual2(); ?>">

<meta property="og:title" content="Kaizen" />
<meta property="og:type" content="website" />
<meta property="og:description" content="<? echo $resumo ?>" />
<meta property="og:url" content="<? echo url_atual2(); ?>" />
<meta property="og:image" content="<? echo $urlimg; ?>" />
-->
	<meta name="title" content="Kaizen"/>
	<meta name="author" content=""/>
	<meta name="description" content="<? echo $resumo ?>"/>
	<meta name="Copyright" content=""/>
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<link rel="shortcut icon" href="favicon.ico"/>
	<link rel="stylesheet" href="lib/css/style.css"/>
	<link rel="stylesheet" href="lib/css/tabela.css"/>
	<link rel="stylesheet" href="lib/css/menu.css"/>
	<link rel="stylesheet" href="lib/fontawesome-free-5.9.0-web/css/all.min.css"/>
	<script src="lib/js/prefixfree.min.js"></script>
	<script src="lib/js/modernizr-2.7.1.dev.js"></script>
	<script src="lib/js/jquery-1.11.3.min.js"></script>
	<script src="lib/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="lib/js/jquery.easing.1.3.js"></script>
    
    <script src="lib/js/jquery.inputmask.bundle.min.js"></script> 
    <script src="lib/js/jquery.maskMoney.js"></script>

	<!-- PACE -->
	<link rel="stylesheet" href="lib/css/pace.css"/>
	<script src="lib/js/pace.min.js"></script>

	<!-- PACE -->

	<!-- NACHO LIGHTBOX -->
	<link href="lib/nacho-lightbox-1.33/plugin/css/nchlightbox-1.3.css" rel="stylesheet">
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.hammer.min.js"></script>
	<script src="lib/nacho-lightbox-1.33/plugin/js/jquery.nchlightbox-1.3.js"></script>
	<!-- NACHO LIGHTBOX rel="group1" class="nch-lightbox"  -->

	<!-- InstanceBeginEditable name="head" -->
<!-- InstanceEndEditable -->
</head>

<body>
	<!--<div id="overlayLoad">
<p class="alig-cen">Carregando...</p>
 <div class="loader"></div>
</div>-->

	<header id="Desk">
		<section class="conteudo">
			<!--<img id="logoDesk" src="lib/img/logo.svg" alt="" />-->
			<ul id="menuDesk">
				<?
		if (!empty($_SESSION['usu'])){
		?>
				<li class="float-esq"><a href="home.php">Página inicial</a>
				</li>
				<li>Olá
					<? echo " ".$usuario['nome_colaborador'];  ?>!
					<? if ($usuario['ponto_colaborador'] > 0) {  if ($usuario['ponto_colaborador'] > 1) { echo $usuario['ponto_colaborador']." pontos."; } else { echo '1 ponto.'; }  } else { echo "0 pontos."; } ?>
				</li>
				<li><a href="app/func_logout.php">Sair</a>
				</li>
				<?	
		}
	  ?>

			</ul>
			<div class="clearfix"></div>
		</section>
	</header>
	<header id="Mobile">
		<section class="conteudo"><a id="btMenu"></a> <img id="logoMob" src="lib/img/logo-kaizen.png" alt=""/>
			<ul id="menuMob" class="MobFechado">
				<li style="font-size: 0.8em; padding-bottom: 10px;">Olá
					<? echo " ".$usuario['nome_colaborador'];  ?>!
					<? if ($usuario['ponto_colaborador'] > 0) {  if ($usuario['ponto_colaborador'] > 1) { echo $usuario['ponto_colaborador']." pontos."; } else { echo '1 ponto.'; }  } else { echo "0 pontos."; } ?>
				</li>


				<li><a href="home.php">Página inicial</a>
				</li>
				<li><a href="minhas-sugestoes.php">Meus Kaizens</a>
				</li>
				<li><a href="novo-kaizen.php">Novo Kaizen</a>
				</li>
				<li><a href="categorias.php">Trocar pontos</a>
				</li>
				<li><a href="minhas-trocas.php">Minhas trocas</a>
				</li>
				<li><a href="app/func_logout.php">Sair</a>
				</li>

				<?
		if ($usuario['lider_colaborador'] == 1){
		?>
				<li><a href="sugestoes-analisar.php"> Gestor Kaizen</a>
				</li>
				<?		
		}
	  ?>

				<?
		if (!empty($usuario['nome_setor'])){
		?>
				<li><a href="lider-analisar.php">Gestor Setor <? echo $usuario['nome_setor'] ?></a>
				</li>
				<?		
		}
	  ?>
			</ul>
			<div class="clearfix"></div>
		</section>
	</header>
	<section class="conteudo">
		<div id="spacerTop"></div>
		<!-- InstanceBeginEditable name="conteudo" -->
<div class="div1-4"><img src="lib/img/logo-kaizen.png" alt="KAIZEN" id="logoKaizen"/></div>
<div class="div1-2 float-dir">
          <? resp ($_GET['resp'], $_GET['msg'], $_GET['tipo']); ?>
          <h1>Esqueceu a senha?</h1>
		
	<? 
	if (!empty($_POST['usuario'])){
		
		$login = trata($_POST['usuario']);
		
		$sql = mysqli_query($con, "SELECT * FROM colaborador WHERE usuario_colaborador = '$login' LIMIT 1") or die (mysqli_error($con));
		if(mysqli_num_rows($sql)){
			
			
			$dados = mysqli_fetch_array($sql);
			$mail = $dados['email_colaborador'];

			$oculto = substr_replace($mail, '*****', 1, strpos($mail, '@') - 2);

			if (!empty($mail) && is_email($mail)){
			
			?>
		
     <p>Etapa 2: Complete seu e-mail corretamente abaixo, ou <a href="esqueci_senha.php"> <u> clique aqui</u></a> para voltar.</p>
	
     <p>&nbsp;</p>
	<em>Dica: <? echo $oculto; ?></em>
     <p>&nbsp;</p>
     <form action="app/func_esqueci_senha.php" method="post" name="formContato" class="formulario" id="formContato">
	  <input name="usuario" type="hidden" id="usuario" value="<? echo $login ; ?>">
	  <input name="email" type="text" required id="email" placeholder="E-mail*:">
   
      <p class="fonte-p">&nbsp;</p>
      <div class="clearfix"></div>
      <p>&nbsp;</p>
      <input type="submit" name="enviar" id="enviar" value="Recuperar senha">
      <div class="clearfix"></div>
    </form>	
	
			<?	
				
		} else {
				
			?>
	<div class="alert alert-danger"> <strong>Erro!</strong> Não é possível recuperar sua senha, pois seu usuário não possui e-mail cadastrado. <br>
	Entre em contato com o administrador do sistema para cadastrar seu e-mail ou para recuperar sua senha manualmente.
	</div>
	
     <p><a href="index.php"> <u> Clique aqui</u></a> para voltar.</p>
     <p>&nbsp;</p>
			<?
				
		}

			
			
		} else {
			
			?>
	 <div class="alert alert-danger"> <strong>Erro!</strong> Usuário não encontrado. </div>
	
     <p>Etapa 1: Entre com seu usuário abaixo, ou <a href="index.php"> <u> clique aqui</u></a> para voltar.</p>
     <p>&nbsp;</p>
     <form action="esqueci_senha.php" method="post" name="formContato" class="formulario" id="formContato">
		 
      <input name="usuario" type="text" required id="nome" placeholder="Usuário*:">
   
      <p class="fonte-p">&nbsp;</p>
      <div class="clearfix"></div>
      <p>&nbsp;</p>
      <input type="submit" name="enviar" id="enviar" value="Próximo">
      <div class="clearfix"></div>
    </form>	
			<?
			
		}
		
		
		
		
	} else {
		?>
	
     <p>Etapa 1: Entre com seu usuário abaixo, ou <a href="index.php"> <u> clique aqui</u></a> para voltar.</p>
     <p>&nbsp;</p>
    <form action="esqueci_senha.php" method="post" name="formContato" class="formulario" id="formContato">
		 
      <input name="usuario" type="text" required id="nome" placeholder="Usuário*:">
   
      <p class="fonte-p">&nbsp;</p>
      <div class="clearfix"></div>
      <p>&nbsp;</p>
      <input type="submit" name="enviar" id="enviar" value="Próximo">
      <div class="clearfix"></div>
    </form>	
	
		<?		
	}
	?>

    <p>&nbsp;</p>
        </div>
  <!-- InstanceEndEditable -->
		<script src="lib/js/functions.js"></script>
		<div class="clearfix"></div>
		<footer class="fonte-p">: : Desenvolvido por catenacom.com</footer>
	</section>
</body>
<!-- InstanceEnd --></html>
