<?php	
	require_once '../classes/classBD.php';
	require '../classes/classeCargo.php';
	
	$bd = new Banco(); // instancia classe banco
	$con = $bd->conectarBd(); // retorna uma conexão
	
	$cargo = new Cargo($con); // instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idCargo"])) ? $cargo->set_idcargo($_POST["idCargo"]) : "";
	(isset($_POST["nomeCargo"])) ? $cargo->set_nome($_POST["nomeCargo"]) : "";	
	(isset($_POST["prioridadeCar"])) ? $cargo->set_prioridade($_POST["prioridadeCar"]) : "";
	//(isset($_POST["statusPesquisa"])) ? $statusPesquisa = $_POST["statusPesquisa"] : "";
	
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			$cargo->set_ativo(1);
		}
		else {
			$cargo->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
		
	switch ($op) {
		case 'adicionar':
				$cargo->inserir();
			break;
		
		case 'alterar':				
				$cargo->alterar();
			break;
		
		case 'excluir':
			    $cargo->excluir();
			break;
		
		case 'visualizar':	
				$cargo->selecionarTodos();
			break;
		
		case 'gerarLista':
				$cargo->gerarLista();
			break;
			
		case 'pesquisar':
			$cargo->pesquisar($pesquisa);
			break;		
		
		case 'reativar':
			$cargo->reativar();
			break;
		
		case 'validaNome':
			$cargo->validaNome();
			break;
			
		default:			
			break;
	}
?>