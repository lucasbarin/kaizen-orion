<?php
include ("conexao8.php");
include ("funcoes8.php");
include ("sessao2.php");



if ( $_GET['kaizen'] && $_GET['analise'] ) {

	foreach ( $_GET as $k => $v ) {
		$$k = trata( $v );
	}
	
	
	$sql_usu = sql ("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($_SESSION['usu'])." LIMIT 1");
	if (mysqli_num_rows($sql_usu)){
		$usuario = mysqli_fetch_array($sql_usu);
	} else {
		volta( "erro", "Seu usuário não foi encontrado!", "../home.php" );
	}
	
	if ($usuario['lider_colaborador'] <> 1){
		volta ("erro", "Área restrita para líderes Kaizen. Você não tem permissão para acessá-lo!", "home.php");	
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
			volta ("erro", "Registro não encontrado!", "sugestoes-analisar.php");
		} 
	
	} else {
		volta ("erro", "Registro não encontrado!", "sugestoes-analisar.php");
	}
	
	if ($dados['status_kaizen'] <> 1){
		volta ("erro", "Este kaizen já foi analizado!", "sugestoes-analisar.php");
	}

	if ($analise <> 2 && $analise <> 3 && $analise <> 4){
		volta ("erro", "Clique nos botões Aprovar, Reprovar ou Devolver!", "kaizen-editar.php");
		// 1 aberto , 2 aprovado , 3 reprovado
        if ($analise == 2){
            $s = 'Aprovado';
        }
        
        if ($analise == 3){
            $s = 'Reprovado';
        }
        
                
        if ($analise == 4){
            $s = 'Devolvido';
        }
	}
	
        if ($analise == 2){
            $s = 'Aprovado';
        }
        
        if ($analise == 3){
            $s = 'Reprovado';
        }
        
       if ($analise == 4){
            $s = 'Devolvido';
        }
	
	
    
   if ($usuario['email_colaborador']){
		
		  /*
		  Envia e-mail para colaborador
		  */
	   
	   $root = $_SERVER['DOCUMENT_ROOT']."/";
	   $diretorio = "";

			
		  $idkaizen = $id_kaizen;
	
	 	  $sqlGestor = sql("SELECT * FROM colaborador WHERE id_colaborador =  ".trata($usuario['gestor_colaborador'])." LIMIT 1");
		  $gestor = mysqli_fetch_array($sqlGestor);
		  $nome_colaborador = $usuario[ 'nome_colaborador' ];
          $usuario_colaborador = $usuario[ 'usuario_colaborador' ];
          $email_colaborador = $usuario[ 'email_colaborador' ];
		
	      $para = $gestor['email_colaborador'];


          include( $root.$diretorio."app/mail_kaizen_analisado.php" );
          
	
	}
    
    
    // processo a devolução
    
    if ($analise == 4){
            
        $sql = sql ("
	   UPDATE `kaizen` SET `status_kaizen` = '".$analise."', `admin_kaizen` = '".$usuario['id_colaborador']."', `obs_kaizen` = '".$obs_kaizen."' WHERE `kaizen`.`id_kaizen` = $id_kaizen;");

        if ($sql){
            volta ("ok", "Kaizen devolvido ao colaborador!", "../sugestoes-analisar.php");
        } else {
            volta ("erro", "Erro enviar sua solicitação! Tente novamente mais tarde!", "../analisar-kaizen.php");
        }
        
    }
    
    
    
	/*// vejamos quantos pontos serão conferidos	
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
    
    $pontosOld = $dados['ponto_colaborador'];
    
    $sqlPonto = sql("SELECT * FROM pg WHERE id_pg = 101", $con);
    $ponto = mysqli_fetch_array($sql);
    
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
    
	if ($pontos < 1 && $analise == 2){
		volta ("erro", "Tente novamente, houve um erro ao conferir os pontos!", "analisar-kaizen.php");
	}
	
	if ($analise == 3){
		$pontos = 0;
	}
	
	$data = date("Y-m-d");
	
		 
	
	$sql = sql ("
	UPDATE `kaizen` SET `dataconclusao_kaizen` = '".$data."', `status_kaizen` = '".$analise."', `admin_kaizen` = '".$usuario['id_colaborador']."', `pontos_kaizen` = '".$pontos."' WHERE `kaizen`.`id_kaizen` = $id_kaizen;");
	

if ($sql){
	
		if ($analise == 2){
		
			// atualizar colaborador ponto

            $sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoCriador) WHERE id_colaborador = ".$dados['colaborador_kaizen']." LIMIT 1");
            $lg = registraLog($dados['colaborador_kaizen'], $pontoCriador, $pontosOld , "Kaizen #".$dados['id_kaizen']." ".$s." (Criador)", $pathConexao = "../app/");

			// colaboradores que partiviparam

			if (!empty($dados['colaborador1_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen']) or !empty($dados['colaborador2_kaizen'])){
			  for ($i=1; $i <= 4; $i++){
				if (!empty($dados['colaborador'.$i.'_kaizen'])){
                    
                     $sqlColaborador = sql("SELECT * FROM colaborador WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                     $colaborador = mysqli_fetch_array($sqlColaborador);

					$sqlcol = sql("UPDATE `colaborador` SET `ponto_colaborador` = (ponto_colaborador + $pontoAuxiliar) WHERE id_colaborador = ".$dados['colaborador'.$i.'_kaizen']." LIMIT 1");
                            
                    $lg = registraLog($colaborador['id_colaborador'], $pontoAuxiliar, $colaborador['ponto_colaborador'] , "Kaizen #".$dados['id_kaizen']." ".$s." (Auxiliar)", $pathConexao = "../app/");

					

				}
			  }
			}
			
		}
		volta ("ok", "Kaizen analisado e finalizado!", "../sugestoes-analisar.php");
		
		
	} else {
		volta ("erro", "Erro enviar sua solicitação! Tente novamente mais tarde!", "../analisar-kaizen.php");
	}
} else {
	volta ("erro", "Preencha todos os campos!", "../analisar-kaizen.php");
}
?>