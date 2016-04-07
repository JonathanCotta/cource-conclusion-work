<!DOCTYPE html>
<?php
	session_start();
	if ((!isset ($_SESSION['login']) == true) && (!isset ($_SESSION['senha']) == true)){
		header('location:../index.html');			
	}
	if ($_SESSION["perfil"] == "Comum" ) {
		header('location:inicio.php');
	}
	if ($_SESSION["perfil"] == "Atendente"  ) {
		header('location:gerenciarchamado.php');
	}	
?>
	<html lang="pt-br">

	<head>
		<meta charset="utf-8">
		<title>Plataformas</title>
		<link rel="stylesheet" href="css/bootstrap/bootstrap.min.css" type="text/css">
		<link rel="stylesheet" href="css/estilo.css" type="text/css">
		<link rel="icon" type="image/png" href="css/imagens/curled-tentacle.png">
	</head>

	<body>
		<header>
			<nav id="barra-superior" class="navbar navbar-default ">
				<div class="navbar-header">
					<div id="dl-menu" class="dl-menuwrapper">
						<button class="dl-trigger">Open Menu</button>
						<ul class="dl-menu">
							<li><a href="inicio.php">Inicio</a></li>
							<li>
								<a href="#">Chamados</a>
								<ul class="dl-submenu">
									<li><a href="novochamado.php">Novo chamado</a></li>
									<li><a href="gerenciarchamado.php">Gerenciar chamados</a></li>
								</ul>
							</li>
							<li>
								<a href="usuario.php" class="nv-0 nv-1">Usuários</a>
							</li>
							<li>
								<a href="#" class="nv-0 nv-1">Grupos e atendentes</a>
								<ul class="dl-submenu">
									<li><a href="grupo.php" class="nv-1">Grupos de Atendimento</a></li>
									<li><a href="gerenciargrupo.php" class="nv-1">Manejamento de atendentes</a></li>
								</ul>
							</li>
							<li><a href="plataforma.php" class="nv-0 nv-1">Plataformas</a></li>
							<li><a href="categoria.php" class="nv-0 nv-1">Categorias</a></li>
							<li><a href="cargo.php" class="nv-0 nv-1">Cargos</a></li>
							<li><a href="departamento.php" class="nv-0 nv-1">Departamentos</a></li>
							<li>
								<a href="" class="nv-0 nv-1">Relatórios</a>
								<ul class="dl-submenu ">
									<li><a href="report_cargo.php" class="nv-0 nv-1">Cargos</a></li>
									<li><a href="report_categoria.php" class="nv-0 nv-1">Categorias</a></li>
									<li><a href="report_departamento.php" class="nv-0 nv-1">Departamentos</a></li>
									<li><a href="report_grupo.php" class="nv-0 nv-1">Grupos</a></li>
									<li><a href="report_plataforma.php" class="nv-0 nv-1">Plataformas</a></li>
									<li><a href="report_usuario.php" class="nv-0 nv-1">Usuários</a></li>
								</ul>
							</li>
							<li>
								<a href="" class="nv-0">Avaliações</a>
								<ul class="dl-submenu">
									<li><a href="avaliacoesatendentes.php" class="nv-0">De Atendentes</a></li>
									<li><a href="avaliacoesgrupo.php" class="nv-0">De Grupos</a></li>
								</ul>
							</li>
						</ul>
					</div>
					<ul class="nav navbar-nav nav-breadcrumbs">
						<li><a href="inicio.php" class="nav-bc navbar-brand">Inicio</a></li>
						<li><a href="" class="nav-bc-final navbar-brand">Plataformas</a></li>
					</ul>
				</div>

				<ul class="nav navbar-nav navbar-right navbar-top hidden-xs">

					<li class="dropdown">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
							<span class="material-icons md-light md-26">account_circle</span>
						</a>
						<ul class="dropdown-menu">
							<li class="disabled">
								<a href="#">
									<?php echo $_SESSION["nome"] ?>
								</a>
							</li>
							<li><a href="#">Sobre o sistema</a></li>
							<li role="separator" class="divider"></li>
							<li><a id="sair" href="#">Sair</a></li>
						</ul>
					</li>
				</ul>

			</nav>
		</header>

		<section>
			<div class="panel panel-default container panel-crud">
				<h4>Gerênciamento de plataformas</h4>
				<nav class="navbar navbar-default">

					<form class="navbar-form navbar-left" role="search" id="formPesquisa">
						<div class="form-group input-group">
							<input type="text" id="pesquisa" class="form-control" placeholder="Digite aqui" rel="tooltip" data-placement="top">
							<span class="input-group-btn"><button type="button" id="btnpesquisa"class="btn btn-info btn-pesquisa"><span class="material-icons md-light md-20">search</span></button>
							</span>
						</div>
						<label class="radio">
							<input type="radio" name="optpesquisa" value="ativo" checked> Ativos </label>
						<label class="radio">
							<input type="radio" name="optpesquisa" value="inativo"> Inativos </label>

					</form>

					<div class="navbar-right">
						<button id="inserir" class="btn btn-default nav-btn nv-1" rel="tooltip" title="Adicionar novo registro" data-placement="left">
							<span class="material-icons md-dark md-26">add_circle</span>
						</button>
						<button id="alterar" class="btn btn-default nav-btn nv-1" rel="tooltip" data-placement="bottom" title="Alterar registro selecionado">
							<span class="material-icons md-dark md-26">edit</span>
						</button>
						<button id="excluir" class="btn btn-default nav-btn nv-1" rel="tooltip" title="Excluir registro selecionado" data-placement="right">
							<span class="material-icons md-dark md-26">delete</span>
						</button>
					</div>


				</nav>
				<table id="tabPlataforma" class="table table-condensed table-hover tableSection">
					<thead>
						<tr>
							<th>Código</th>
							<th>Nome</th>
							<th>Grupo</th>
						</tr>
					</thead>
					<tbody>

					</tbody>
				</table>

			</div>
			<div id="modal" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div id="modalHeader" class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title"><span id="funcModal"></span>plataforma</h4>
						</div>
						<div class="modal-body">
							<form action="" role="" id="formPlataforma">
								<div class="row">
									<div class="form-group col-sm-6">
										<label for="nome">Nome</label>
										<input type="text" maxlength="45" name="nome" placeholder="Nome da plataforma" id="nome" class="form-control" data-placement="bottom">
									</div>

									<div class="form-group col-sm-6">
										<label for="grupo">Grupo</label>
										<select class="form-control" id="grupoSelectPlat" name="grupo">

										</select>
									</div>
								</div>
								<div id="reativarBox" class="checkbox-inline">
									<label>
										<input type="checkbox" value="" id="reativar">Reativar</input>
									</label>
								</div>

						</div>


						<div id="modalFooter" class="modal-footer">
							<button type="button" id="salvarModal" class="btn btn-info">Salvar</button>
							<button type="reset" class="btn btn-default" data-dismiss="modal">Voltar</button>
						</div>
						</form>
					</div>
				</div>
			</div>
			<div id="modalEx" class="modal fade" role="dialog">
				<div class="modal-dialog">
					<div class="modal-content">
						<div id="modalHeader" class="modal-header">
							<h4 class="modal-title"><span id="funcModalEx"></span>Plataforma</h4>
						</div>
						<div class="modal-body">
							<form action="" id="plataformaEx">
								<h4>Deseja mesmo excluir este registro ?</h4>
						</div>
						<div id="modalFooter" class="modal-footer">
							<button type="button" id="confirmaEx" class="btn btn-info" data-dismiss="modal">Confirmar</button>
							<button type="reset" class="btn btn-default" data-dismiss="modal">Cancelar</button>
							</form>
						</div>
					</div>
				</div>
			</div>
		</section>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/valida.js"></script>
		<script src="js/script.js"></script>
	</body>

	</html>