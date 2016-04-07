<?php
	require_once '../classes/classBD.php';
	require '../classes/classeAvaliacao.php';
	
	session_start();
	
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$avaliacao = new Avaliacao($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idchamado"])) ? $avaliacao->set_idchamado($_POST["idchamado"]) : "";
	(isset($_POST["tmpproblema"])) ? $avaliacao->set_temporesolucao($_POST["tmpproblema"]) : "";
	(isset($_POST["slcproblema"])) ? $avaliacao->set_solucao($_POST["slcproblema"]) : "";
	(isset($_POST["retorno"])) ? $avaliacao->set_feedback($_POST["retorno"]) : "";
	(isset($_POST["obs"])) ? $avaliacao->set_observacao($_POST["obs"]) : "";
	(isset($_POST["idGrupo"])) ? $idGrupo = $_POST["idGrupo"] : "";
	
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
	(isset($_POST["pag"])) ? $pag = $_POST["pag"] : "";	
	
	switch ($op) {
		case 'gerar':
			$avaliacao->gerar();
			break;
			
		case 'selecionarTodos':
			$avaliacao->selecionarTodos();
			break;
		
		case 'avaliar':
			$avaliacao->avaliar();
			break;
			
		case 'exibirAvaliacao':		
			$avaliacao->exibirUm();
			break;
		
		case 'exibirAvalicoesGrupo':		
			$avaliacao->exibirAvaliacaoGrupo();
			break;
		
		case 'exibirAvalicoesAtendente':				
			$avaliacao->exibirAvaliacaoAtendente($idGrupo);
			break;
		
		case 'pesquisar':			
			if(isset($_POST["idGrupo"])){
				 $avaliacao->pesquisar($pag,$pesquisa,$idGrupo);
			}
			else{
				 $avaliacao->pesquisar($pag,$pesquisa);
			}
		   
			break;
		default:			
			break;
	}
?>