<!doctype html>
<!--[if lt IE 7 ]> <html class="ie ie6 ie-lt10 ie-lt9 ie-lt8 ie-lt7 no-js" lang="pt-br"> <![endif]-->
<!--[if IE 7 ]>    <html class="ie ie7 ie-lt10 ie-lt9 ie-lt8 no-js" lang="pt-br"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie ie8 ie-lt10 ie-lt9 no-js" lang="pt-br"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie ie9 ie-lt10 no-js" lang="pt-br"> <![endif]-->
<!--[if gt IE 9]><!-->
<html class="no-js" lang="pt-br">
<!--<![endif]-->
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">


	<!-- TemplateBeginEditable name="doctitle" -->
	<title>Kaizen</title>
	<!-- TemplateEndEditable -->
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
	<link rel="shortcut icon" href="../favicon.ico"/>
	<link rel="stylesheet" href="../lib/css/style.css"/>
	<link rel="stylesheet" href="../lib/css/tabela.css"/>
	<link rel="stylesheet" href="../lib/css/menu.css"/>
	<link rel="stylesheet" href="../lib/fontawesome-free-5.9.0-web/css/all.min.css"/>
	<script src="../lib/js/prefixfree.min.js"></script>
	<script src="../lib/js/modernizr-2.7.1.dev.js"></script>
	<script src="../lib/js/jquery-1.11.3.min.js"></script>
	<script src="../lib/js/jquery-migrate-1.2.1.min.js"></script>
	<script src="../lib/js/jquery.easing.1.3.js"></script>
    
    <script src="../lib/js/jquery.inputmask.bundle.min.js"></script> 
    <script src="../lib/js/jquery.maskMoney.js"></script>

	<!-- PACE -->
	<link rel="stylesheet" href="../lib/css/pace.css"/>
	<script src="../lib/js/pace.min.js"></script>

	<!-- PACE -->

	<!-- NACHO LIGHTBOX -->
	<link href="../lib/nacho-lightbox-1.33/plugin/css/nchlightbox-1.3.css" rel="stylesheet">
	<script src="../lib/nacho-lightbox-1.33/plugin/js/jquery.hammer.min.js"></script>
	<script src="../lib/nacho-lightbox-1.33/plugin/js/jquery.nchlightbox-1.3.js"></script>
	<!-- NACHO LIGHTBOX rel="group1" class="nch-lightbox"  -->

	<!-- TemplateBeginEditable name="head" -->
	<!-- TemplateEndEditable -->
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
				<li class="float-esq"><a href="../home.php">Página inicial</a>
				</li>
				<li>Olá
					<? echo " ".$usuario['nome_colaborador'];  ?>!
					<? if ($usuario['ponto_colaborador'] > 0) {  if ($usuario['ponto_colaborador'] > 1) { echo $usuario['ponto_colaborador']." pontos."; } else { echo '1 ponto.'; }  } else { echo "0 pontos."; } ?>
				</li>
				<li><a href="../app/func_logout.php">Sair</a>
				</li>
				<?	
		}
	  ?>

			</ul>
			<div class="clearfix"></div>
		</section>
	</header>
	<header id="Mobile">
		<section class="conteudo"><a id="btMenu"></a> <img id="logoMob" src="../lib/img/logo-kaizen.png" alt=""/>
			<ul id="menuMob" class="MobFechado">
				<li style="font-size: 0.8em; padding-bottom: 10px;">Olá
					<? echo " ".$usuario['nome_colaborador'];  ?>!
					<? if ($usuario['ponto_colaborador'] > 0) {  if ($usuario['ponto_colaborador'] > 1) { echo $usuario['ponto_colaborador']." pontos."; } else { echo '1 ponto.'; }  } else { echo "0 pontos."; } ?>
				</li>


				<li><a href="../home.php">Página inicial</a>
				</li>
				<li><a href="../minhas-sugestoes.php">Meus Kaizens</a>
				</li>
				<li><a href="../novo-kaizen.php">Novo Kaizen</a>
				</li>
				<li><a href="../categorias.php">Trocar pontos</a>
				</li>
				<li><a href="../minhas-trocas.php">Minhas trocas</a>
				</li>
				<li><a href="../app/func_logout.php">Sair</a>
				</li>

				<?
		if ($usuario['lider_colaborador'] == 1){
		?>
				<li><a href="../sugestoes-analisar.php"> Gestor Kaizen</a>
				</li>
				<?		
		}
	  ?>

				<?
		if (!empty($usuario['nome_setor'])){
		?>
				<li><a href="../lider-analisar.php">Gestor Setor <? echo $usuario['nome_setor'] ?></a>
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
		<!-- TemplateBeginEditable name="conteudo" -->conteudo
		<!-- TemplateEndEditable -->
		<script src="../lib/js/functions.js"></script>
		<div class="clearfix"></div>
		<footer class="fonte-p">: : Desenvolvido por catenacom.com</footer>
	</section>
</body>
</html>