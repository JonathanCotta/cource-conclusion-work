<?php
	require_once '../classes/classBD.php';	
	require '../classes/classeAtendente.php';
	
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$atendente = new Atendente($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idUsuario"])) ? $atendente->set_idusuario($_POST["idUsuario"]) : "";
	(isset($_POST["cpf"])) ? $atendente->set_cpf($_POST["cpf"]) : "";
	(isset($_POST["nomeUsuario"])) ? $atendente->set_nome($_POST["nomeUsuario"]) : "";
	(isset($_POST["login"])) ? $atendente->set_login($_POST["login"]) : "";
	(isset($_POST["senha"])) ? $atendente->set_senha($_POST["senha"]) : "";
	(isset($_POST["email"])) ? $atendente->set_email($_POST["email"]) : "";
	(isset($_POST["ramal"])) ? $atendente->set_ramal($_POST["ramal"]) : "";
	(isset($_POST["cel"])) ? $atendente->set_celular($_POST["cel"]) : "";
	(isset($_POST["depId"])) ? $atendente->set_departamentoid($_POST["depId"]) : "";
	(isset($_POST["cargoId"])) ? $atendente->set_cargoid($_POST["cargoId"]) : "";
	(isset($_POST["nvlacesso"])) ? $atendente->set_nivelacesso($_POST["nvlacesso"]) : "";	
	// atributo exclusivo do atendente
	(isset($_POST["idGrupo"])) ? $atendente->set_idgrupo($_POST["idGrupo"]) : "";	
				
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
		
	switch ($op) {
		case 'adicionar':			
			$atendente->inserir();
			break;
		
		case 'alterar':
			$atendente->alterar();
			break;
		
		case 'resetSenha':
			$atendente->alterarSenha("reset");
			break;
		
		case 'excluir':
			$atendente->excluir();
			break;
		
		case 'visualizar': 
			$atendente->selecionarTodos();
			break;
		
		case 'gerarLista':
			$atendente->gerarLista("adicionar");
			break;
			
		case 'gerarListaInativos':
			$atendente->gerarLista("inativos");
			break;
			
		case 'gerarListaRepasse':
			$atendente->gerarLista("repasse");
			break;
			
		case 'pesquisar':
			$atendente->pesquisar($pesquisa);
			break;
		
		case 'reativar':
			$atendente->reativar();
			break;
			
		case 'reativarAtendente':
			$atendente->reativarAtendente();
			break;
			
		case 'adicionarNoGrupo':
			$atendente->inserirNoGrupo();	
			break;
			
		case 'removerDoGrupo':
			$atendente->removerDoGrupo();	
			break;	
			
		case 'validaNome':
			$atendente->validaNome();
			break;
			
		case 'validaCPF':
			$atendente->validaCPF();
			break;
			
		default:			
			break;
	}	
?>