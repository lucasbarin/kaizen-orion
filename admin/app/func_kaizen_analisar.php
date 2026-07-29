<?php

ob_start();
@session_start();

include( "../../app/funcoes8.php" );
include( "../../app/conexao8.php" );

error_reporting(E_ALL);

if ( $_GET['kaizen'] && $_GET['analise'] ) {

	foreach ( $_GET as $k => $v ) {
		$$k = trata( $v );
	}
	
	// dados do usu admin
	$sql_usu = sql("SELECT * FROM colaborador WHERE id_colaborador =  1 LIMIT 1", $con);
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usu�rio n�o foi encontrado!", "../kaizen-admin.php" );
	}
	

	$id_kaizen = trata ($_GET['kaizen']);
	
	if (!empty ($id_kaizen) && is_numeric ($id_kaizen)){
	$sql = mysqli_query($con, "SELECT * FROM kaizen
	LEFT JOIN tipo ON tipo.id_tipo = kaizen.tipo_kaizen
	LEFT JOIN categoria ON categoria.id_categoria = kaizen.categoria_kaizen
	LEFT JOIN colaborador ON colaborador.id_colaborador = kaizen.colaborador_kaizen
	LEFT JOIN setor ON setor.id_setor = kaizen.setor_kaizen
	LEFT JOIN unidade ON unidade.id_unidade = kaizen.setor_kaizen
	WHERE id_kaizen = ".$id_kaizen." LIMIT 1") or die (mysqli_error($con));
	 
	if(mysqli_num_rows($sql) > 0){
		$dados = mysqli_fetch_array($sql);

	} else {
		volta ("erro", "Registro n�o encontrado!", "kaizen-admin.php");
	} 
	
	} else {
		volta ("erro", "Registro n�o encontrado!", "kaizen-admin.php");
	}

	if ($analise <> 2 && $analise <> 3){
		volta ("erro", "Clique nos bot�es Aprovar ou Reprovar!", "kaizen-editar.php");
		// 1 aberto , 2 aprovado , 3 reprovado

        
        
	}
	   if ($analise == 2){
            $s = 'Aprovado';
        }
        
        if ($analise == 3){
            $s = 'Reprovado';
        }
	
	
	   if ($dados['email_colaborador']){
		
		  /*
		  Envia e-mail para colaborador
		  */
	   
	   $root = $_SERVER['DOCUMENT_ROOT']."/";
	   $diretorio = "";

			
		  $idkaizen = $id_kaizen;
	
	 	  $sqlGestor = sql("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($dados['gestor_colaborador'])." LIMIT 1");
		  $gestor = mysqli_fetch_array($sqlGestor);
		  $nome_colaborador = $dados[ 'nome_colaborador' ];
          $usuario_colaborador = $dados[ 'usuario_colaborador' ];
          $email_colaborador = $dados[ 'email_colaborador' ];
		
	      $para = $gestor['email_colaborador'];


          include( $root.$diretorio."app/mail_kaizen_analisado.php" );
          
	
	}
	
	
	$pontosOld = $dados['ponto_colaborador'];
	$statusOld = $dados['status_kaizen'];
	
	// vejamos quantos pontos ser�o conferidos
    /*
	if ($dados['tipo_kaizen'] == 1){
		// custo
		$sqltipo = sql("SELECT * FROM reducao 
		LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
		WHERE reducao.id_reducao = ".$dados['custo_kaizen']." LIMIT 1");
		$tipo = mysqli_fetch_array($sqltipo);

	} elseif ($dados['tipo_kaizen'] == 2) {
		// tempo
		$sqltipo = sql("SELECT * FROM reducao 
		LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
		WHERE reducao.id_reducao = ".$dados['tempo_kaizen']." LIMIT 1");
		$tipo = mysqli_fetch_array($sqltipo);	

	} else {
		// tempo
		$sqltipo = sql("SELECT * FROM reducao 
		LEFT JOIN tipo ON reducao.tipo_reducao = tipo.id_tipo 
		WHERE reducao.id_reducao = ".$dados['reducao_kaizen']." LIMIT 1");
		$tipo = mysqli_fetch_array($sqltipo);	
	}
    
    $pontos = $tipo['pontos_reducao'];
    
    */
    
    
	



	
	
	
    
    $sqlPonto = sql("SELECT * FROM pg WHERE id_pg = 101", $con);
	$ponto = mysqli_fetch_array($sqlPonto);
    
    if (is_numeric($ponto['nome1_pg']) && $ponto['nome1_pg'] > 0){
        $pontoCriador = $ponto['nome1_pg'];
    } else {
        $pontoCriador = 2; // padrao
    }    
    
    if (is_numeric($ponto['nome2_pg']) && $ponto['nome2_pg'] > 0){
        $pontoAuxiliar = $ponto['nome2_pg'];
    } else {
        $pontoAuxiliar = 1; // padrao
    }
    
    $pontos = $pontoCriador;
    
    
   $data = date("Y-m-d");
	 
	// echo "status $statusOld | analise $analise"; die;
	$sql = sql ("
	UPDATE `kaizen` SET `dataconclusao_kaizen` = '".$data."', `status_kaizen` = '".$analise."', `admin_kaizen` = '".$usuario['id_colaborador']."', `pontos_kaizen` = '".$pontos."' WHERE `kaizen`.`id_kaizen` = $id_kaizen;
	"); 
    	
	if ($pontos < 1 && $analise == 2){
		volta ("erro", "Tente novamente, houve um erro ao conferir os pontos!", "kaizen-editar.php");
	}
    
	if ($analise == 3){
		$pontos = 0;
	}
    
 
if ($sql){
	   
		// $pontos = $tipo['pontos_reducao'];
	
		if ($statusOld <> 1){
			
			if ($analise == 2){
				// aprovou
				if ($statusOld <> $analise && $statusOld){
					// era reprovado e ficou aprovado
					$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoCriador) WHERE id_colaborador = ".$dados['colaborador_kaizen']." LIMIT 1");
                    
                    $lg = registraLog($dados['colaborador_kaizen'], $pontoCriador, $pontosOld , "Kaizen #".$dados['id_kaizen']." ".$s." (Criador)", $pathConexao = "../../app/");
                    
                    

					// colaboradores que partiviparam
					if (!empty($dados['colaborador1_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen'])){
					  for ($i=1; $i <= 4; $i++){
						if (!empty($dados['colaborador'.$i.'_kaizen'])){
                            
                            $sqlColaborador = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                            $colaborador = mysqli_fetch_array($sqlColaborador);

							$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoAuxiliar) WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                            
                            $lg = registraLog($colaborador['id_colaborador'], $pontoAuxiliar, $colaborador['ponto_colaborador'] , "Kaizen #".$dados['id_kaizen']." ".$s." (Auxiliar)", $pathConexao = "../../app/");

						}
					  }
					}
					volta ("ok", "Kaizen analisado e finalizado! 1", "../kaizen-admin.php");				


				} else {
					volta ("ok", "Kaizen analisado e finalizado! 2", "../kaizen-admin.php");
				}
				
			} else {
				// reprovou
					if ($statusOld <> $analise){
					// era aprovado e ficou reprovado
					$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador - $pontoCriador) WHERE id_colaborador = ".$dados['colaborador_kaizen']." LIMIT 1");
                        
                    $lg = registraLog($dados['colaborador_kaizen'], -abs($pontoCriador), $pontosOld , "Kaizen #".$dados['id_kaizen']." ".$s." (Criador)", $pathConexao = "../../app/");

					// colaboradores que partiviparam
					if (!empty($dados['colaborador1_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen'])){
					  for ($i=1; $i <= 4; $i++){
						if (!empty($dados['colaborador'.$i.'_kaizen'])){
                            
                            $sqlColaborador = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                            $colaborador = mysqli_fetch_array($sqlColaborador);

							$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador - $pontoAuxiliar)
							WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                            
                             $lg = registraLog($colaborador['id_colaborador'], -abs($pontoAuxiliar), $colaborador['ponto_colaborador'] , "Kaizen #".$dados['id_kaizen']." ".$s." (Auxiliar)", $pathConexao = "../../app/");

						}
					  }
					}
					volta ("ok", "Kaizen analisado e finalizado! 3", "../kaizen-admin.php");				


				} else {
					volta ("ok", "Kaizen analisado e finalizado! 4", "../kaizen-admin.php");
				}	
			}

			
		} else {
				
			if ($analise == 2){
				// atualizar colaborador ponto
				$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoCriador) WHERE id_colaborador = ".$dados['colaborador_kaizen']." LIMIT 1");
                 $lg = registraLog($dados['colaborador_kaizen'], $pontoCriador, $pontosOld , "Kaizen #".$dados['id_kaizen']." ".$s." (Criador)", $pathConexao = "../../app/");

				// colaboradores que partiviparam
				if (!empty($dados['colaborador1_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen'])){
				  for ($i=1; $i <= 4; $i++){
					if (!empty($dados['colaborador'.$i.'_kaizen'])){
                        
                         $sqlColaborador = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                         $colaborador = mysqli_fetch_array($sqlColaborador);
                        
						$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoAuxiliar) WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                        
                        $lg = registraLog($colaborador['id_colaborador'], $pontoAuxiliar, $colaborador['ponto_colaborador'] , "Kaizen #".$dados['id_kaizen']." ".$s." (Auxiliar)", $pathConexao = "../../app/");

					}
				  }
				}
				volta ("ok", "Kaizen analisado e finalizado! ", "../kaizen-admin.php");
				
			} else {
               volta ("ok", "Kaizen analisado e finalizado (reprovado)! ", "../kaizen-admin.php");
                
            }
			
			
		}
		
		
	} else {
		volta ("erro", "Erro enviar sua solicita��o! Tente novamente mais tarde!", "../kaizen-editar.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../kaizen-editar.php");
}
ob_end_flush();
?>