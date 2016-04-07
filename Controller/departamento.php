<?php
	require_once '../classes/classBD.php';
	require '../classes/classeDepartamento.php';
	
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$departamento = new Departamento($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idDepartamento"])) ? $departamento->set_iddepartamento($_POST["idDepartamento"]) : "";
	(isset($_POST["nomeDepartamento"])) ? $departamento->set_nome($_POST["nomeDepartamento"]) : "";
	(isset($_POST["prioridadeDep"])) ? $departamento->set_prioridade($_POST["prioridadeDep"]) : "";	
		
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			$departamento->set_ativo(1);
		}
		else {
			$departamento->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
	
	switch ($op) {
		case 'adicionar':
			$departamento->inserir();
			break;
		
		case 'alterar':
			$departamento->alterar();
			break;
		
		case 'excluir':
			$departamento->excluir();
			break;
		
		case 'visualizar': 
			$departamento->selecionarTodos();
			break;
			
		case 'gerarLista':
			$departamento->gerarLista();
			break;
			
		case 'pesquisar':
			$departamento->pesquisar($pesquisa);
			break;
		
		case 'reativar':
			$departamento->reativar();
			break;
			
		case 'validaNome':
			$departamento->validaNome();
			break;
			
		default:			
			break;
	}
?>