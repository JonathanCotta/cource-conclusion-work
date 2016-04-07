<?php
	require_once '../classes/classBD.php';
	require '../classes/classePlataforma.php';	
	
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	 $plataforma = new Plataforma($con); // instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idPlataforma"])) ? $plataforma->set_idplataforma($_POST["idPlataforma"]) : "";
	(isset($_POST["nomePlataforma"])) ?  $plataforma->set_nome($_POST["nomePlataforma"]) : "";
	(isset($_POST["grupoId"])) ?  $plataforma->set_idgrupo($_POST["grupoId"]) : "";
	
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			 $plataforma->set_ativo(1);
		}
		else {
			 $plataforma->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
	
	switch ($op) {
		case 'adicionar':
			 $plataforma->inserir();
			break;
		
		case 'alterar':
			 $plataforma->alterar();
			break;
		
		case 'excluir':
			 $plataforma->excluir();
			break;
		
		case 'visualizar': 
			 $plataforma->selecionarTodos();
			break;
			
		case 'gerarLista':
			 $plataforma->gerarLista();
			break;
			
		case 'gerarListaChamado':
			 $plataforma->gerarLista();
			break;
		
		case 'pesquisar':
			 $plataforma->pesquisar($pesquisa);
			break;
		
		case 'reativar':
			 $plataforma->reativar();
			break;
			
		case 'validaNome':
			$plataforma->validaNome();
			break;
			
		default:			
			break;
	}
?>