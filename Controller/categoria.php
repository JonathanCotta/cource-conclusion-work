<?php
	require_once '../classes/classBD.php';
	require '../classes/classeCategoria.php';
	
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$categoria = new Categoria($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idCategoria"])) ? $categoria->set_idcategoria($_POST["idCategoria"]) : "";
	(isset($_POST["nomeCategoria"])) ? $categoria->set_nome($_POST["nomeCategoria"]) : "";
	(isset($_POST["prioridadeCat"])) ? $categoria->set_prioridade($_POST["prioridadeCat"]) : "";
	(isset($_POST["plataformaId"])) ? $categoria->set_plataforma($_POST["plataformaId"]) : "";
	(isset($_POST["grupoId"])) ? $categoria->set_grupo($_POST["grupoId"]) : "";
		
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			$categoria->set_ativo(1);
		}
		else {
			$categoria->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
	
	switch ($op) {
		case 'adicionar':
			$categoria->inserir();
			break;
		
		case 'alterar':
			echo $categoria->get_nome();
			$categoria->alterar();
			break;
		
		case 'excluir':
			$categoria->excluir();
			break;
		
		case 'visualizar': 
			$categoria->selecionarTodos();
			break;
		
		case 'gerarLista':
			$categoria->gerarLista();
			break;
		
		case 'pesquisar':
			$categoria->pesquisar($pesquisa);
			break;
		
		case 'reativar':
			$categoria->reativar();
			break;
			
		case 'validaNome':
			$categoria->validaNome();
			break;
						
		default:			
			break;
	}
?>