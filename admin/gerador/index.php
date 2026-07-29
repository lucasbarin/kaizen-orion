<!DOCTYPE html>
<html lang="pt-br">
<head>
<!-- Meta tags Obrigatórias -->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<!-- Bootstrap CSS -->
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
<title>Gerador 1.0 </title>
</head>
<body>
    <div class="container">
<h1 class="text-center border-bottom my-5">Selecione as áreas para gerar</h1>
<?php
$path = "includes/";
$diretorio = dir( $path );
echo '
<table class="table table-hover">
  <tbody>';
$menu = '';
while ( $arquivo = $diretorio->read() ) {

  if ( $arquivo != "." && $arquivo != ".." ) {

    $nome = str_replace( ".php", "", $arquivo );

    echo "
        
    <tr>
      <td><span style='font-size: 1.2em;'> " . $nome . " </span></td>
      <td><a class='btn btn-sm btn-primary' href='arquivo.php?config=" . $nome . "'> GERAR ARQUIVOS</a></td>
      <td><a class='btn btn-sm btn-warning' href='bd.php?config=" . $nome . "'> GERAR BD</a></td>
    </tr>
";
    
      // crio menu       
      $menu.='<li> <a href="'.$nome.'-admin.php"> '.strtoupper($nome).'</a> </li>
      ';
  }

}
echo '
  </tbody>
</table>
';
$diretorio->close();
        
    $name = "../menu-adicional.php";
    $file = fopen($name, 'w');
	$escreve = fwrite($file, $menu);
	fclose($file); 
?>
</div>
<!-- JavaScript (Opcional) --> 
<!-- jQuery primeiro, depois Popper.js, depois Bootstrap JS --> 
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script> 
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script> 
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js" integrity="sha384-ChfqqxuZUCnJSK3+MXmPNIyE6ZbWh2IMqE241rYiqJxyMiZ6OW/JmZQ5stwEULTy" crossorigin="anonymous"></script>
</body>
</html>