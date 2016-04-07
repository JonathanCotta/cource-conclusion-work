<?php
	require_once '../classes/classBD.php';
	require '../classes/classeGrupo.php';	
	
	$bd = new Banco(); // instancia classe banco
	$con = $bd->conectarBd(); // retorna uma conexão	
		
	$grupo= new Grupo($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idGrupo"])) ? $grupo->set_idgrupo($_POST["idGrupo"]) : "";
	(isset($_POST["nomeGrupo"])) ? $grupo->set_nome($_POST["nomeGrupo"]) : "";	
			
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			$grupo->set_ativo(1);
		}
		else {
			$grupo->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	na pagina de grupo
	(isset($_POST["pesquisaGrupo"])) ? $pesquisaGrupo = $_POST["pesquisaGrupo"] : "";
	
	
	switch ($op) {
		case 'adicionar':
				$grupo->inserir();
			break;
		
		case 'alterar':
				$grupo->alterar();
			break;
		
		case 'excluir':
				$grupo->excluir();
			break;
		
		case 'visualizar': 
				$grupo->selecionarTodos();
			break;
		case 'gerarLista':
				$grupo->gerarLista();
			break;
			
		case 'pesquisar':
				$grupo->pesquisar($pesquisaGrupo);
			break;
								
		case 'reativar':
			$grupo->reativar();
			break;
			
		case 'validaNome':
			$grupo->validaNome();
			break;
						
		default:			
			break;
	}
?>