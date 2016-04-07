<?php
	require_once '../classes/classBD.php';
	require '../classes/classeChamado.php';
	require '../classes/classeLogChamado.php';
	
	session_start();
		
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$chamado = new Chamado($con);// instancia classe recebendo a conexão com banco
	$logChamado = new LogChamado($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idusuario"])) ? $chamado->set_idusuario($_POST["idusuario"]) : "";
	
	//recebendo id do chamado
	if(isset($_POST["idchamado"])){
		$chamado->set_idchamado($_POST["idchamado"]);
		$logChamado->set_idchamado($_POST["idchamado"]);	
	}
	
	(isset($_POST["statusChamado"])) ? $chamado->set_status($_POST["statusChamado"]) : "";
	(isset($_POST["plataforma"])) ? $chamado->set_plataforma($_POST["plataforma"]) : "";
	(isset($_POST["categoria"])) ? $chamado->set_categoria($_POST["categoria"]) : "";
	(isset($_POST["descricao"])) ? $chamado->set_descricao($_POST["descricao"]) : "";
	(isset($_POST["assunto"])) ? $chamado->set_assunto($_POST["assunto"]) : "";
	(isset($_POST["atendente"])) ? $chamado->set_atendente($_POST["atendente"]) : "";
	
	// setando pelo valor de session de quem esta fazendo alterações no chamado	
	$logChamado->set_agente($_SESSION['login']);
	
	// interação do chat		
	if ((isset($_POST["resposta"])) && (isset($_POST["interacao"]))) {		
		$resposta = $_POST["resposta"];
		$interacao = $_POST["interacao"];
		if($interacao != "Vazio"){
			if($resposta != ""){
				$interacao = $interacao . " \n";
				$chamado->set_interacao($interacao . $_SESSION['login'] . " disse: "  . $resposta);				
			}
		}
		else{
			if($resposta != ""){			
				$chamado->set_interacao( $_SESSION['login'] . " disse: "  . $resposta);			
			}	
		}			
	}	
	
	//upload anexo
	if(isset($_FILES['anexo']['tmp_name'])){
		if(is_array($_FILES)) {
			if(is_uploaded_file($_FILES['anexo']['tmp_name'])) {
				$fileName = rand(000,999).$_FILES["anexo"]["name"] ;		
				$sourcePath = $_FILES['anexo']['tmp_name'];
				$targetPath = "../Model/anexos/".$fileName;			
				if(move_uploaded_file($sourcePath,$targetPath)) {
					$chamado->set_anexo($targetPath);					
				}		
			}
		}	
	}
	
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)
	(isset($_POST["statusPesquisa"])) ? $statusPesquisa = $_POST["statusPesquisa"] : "";
				
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
		
	switch ($op) {
		case 'visualizarUm':
			$chamado->exibirUm();
			break;
		case 'visualizarTodos':
			$chamado->exibirTodos($statusPesquisa);
			break;
		
		case 'visualizarLog':
			$logChamado->visualizarLog();
			break;			
			
		case 'selecionarUm':
			$chamado->selecionarUm();
			break;
			
		case 'inserir':			
			$id = $chamado->inserir();			
			$logChamado->iniciarLog($op,$id,"");
			break;
		case 'alterar':
			$logChamado->gerarLog($chamado->get_status(),$chamado->get_interacao(),$resposta);
			$chamado->alterar();			
			break;
		
		case 'repassar':
			$chamado->repassar();		
			$logChamado->iniciarLog($op,$chamado->get_idchamado(),$chamado->get_atendente());			
			break;
			
		case 'pesquisar':
			if(isset($_POST["intervalo"])){
				$chamado->pesquisar($pesquisa , $statusPesquisa ,$_POST["intervalo"]);
			}
			else{
				$chamado->pesquisar($pesquisa , $statusPesquisa);
			}
			
			break;
		
		case 'visualizarAnexo':
			$chamado->exibirAnexo();
			break;
				
		default:			
			break;
	}	
?>