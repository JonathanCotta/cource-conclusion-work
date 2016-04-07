<?php
	require_once '../classes/classBD.php';
	require '../classes/classeUsuario.php';
		
	$bd = new Banco();// instancia classe banco
	$con = $bd->conectarBd();// retorna uma conexão
	
	$usuario = new Usuario($con);// instancia classe recebendo a conexão com banco
	//recebe variaveis e seta os valores nos atributos da classe
	(isset($_POST["idUsuario"])) ? $usuario->set_idusuario($_POST["idUsuario"]) : "";
	(isset($_POST["cpf"])) ? $usuario->set_cpf($_POST["cpf"]) : "";
	(isset($_POST["nomeUsuario"])) ? $usuario->set_nome($_POST["nomeUsuario"]) : "";
	(isset($_POST["login"])) ? $usuario->set_login($_POST["login"]) : "";
	(isset($_POST["senha"])) ? $usuario->set_senha($_POST["senha"]) : "";
	(isset($_POST["email"])) ? $usuario->set_email($_POST["email"]) : "";
	(isset($_POST["ramal"])) ? $usuario->set_ramal($_POST["ramal"]) : "";
	(isset($_POST["cel"])) ? $usuario->set_celular($_POST["cel"]) : "";
	(isset($_POST["depId"])) ? $usuario->set_departamentoid($_POST["depId"]) : "";
	(isset($_POST["cargoId"])) ? $usuario->set_cargoid($_POST["cargoId"]) : "";
	(isset($_POST["nvlacesso"])) ? $usuario->set_nivelacesso($_POST["nvlacesso"]) : "";
	
	// recebe valor para filtrar entre registros ativos(1) ou inativos(0)	
	if (isset($_POST["statusPesquisa"])) {
		$statusPesquisa = $_POST["statusPesquisa"];
		if ($statusPesquisa == "ativo") {
			$usuario->set_ativo(1);
		}
		else {
			$usuario->set_ativo(0);
		}
	}	
			
	// define qual operação será realizada	
	(isset($_POST["op"])) ? $op = $_POST["op"] : "";	
	
	// variavel a ser usada na pesquisa	
	(isset($_POST["pesquisa"])) ? $pesquisa = $_POST["pesquisa"] : "";
		
	switch ($op) {
		case 'adicionar':			
			$usuario->inserir();
			break;
		
		case 'alterar':
			$usuario->alterar();
			break;
			
		case 'alterarSenha':
			$usuario->alterarSenha("alter");
			break;
		
		case 'resetSenha':
			$usuario->alterarSenha("reset");
			break;
		
		case 'excluir':
			$usuario->excluir();
			break;
		
		case 'visualizar': 
			$usuario->selecionarTodos();
			break;		
		
		case 'pesquisar':
			$usuario->pesquisar($pesquisa);
			break;
		
		case 'reativar':
			$usuario->reativar();
			break;
		
		case 'logar':
			$usuario->logar();
			break;
		
		case 'deslogar':
			$usuario->deslogar();
			break;
			
		case 'verificarSessao':
			$usuario->verificarSessao();
			break;
			
		case 'validaLogin':
			$usuario->validaLogin();
			break;
			
		case 'validaCPF':
			$usuario->validaCPF();
			break;
			
		default:			
			break;
	}	
?>