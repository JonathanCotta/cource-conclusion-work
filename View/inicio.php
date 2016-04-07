<!DOCTYPE html>
<?php
	session_start();
	if ((!isset ($_SESSION['login']) == true) && (!isset ($_SESSION['senha']) == true)){
		header('location:../index.html');			
	}	
?>
	<html lang="pt-br">

	<head>
		<meta charset="utf-8">
		<title>Inicio</title>
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
						<li><a href="" class="nav-bc nav-brand">Inicio</a></li>
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
			<div id="acesso" class="panel panel-default container panel-chamado panel-inicio">

				<fieldset>
					<legend>
						<h4>Acesso rápido</h4>
					</legend>

					<a href="novochamado.php" class="a-ar">
						<span class="material-icons md-blue md-48" rel="tooltip" title="Novo chamado" data-placement="left">queue</span>
					</a>
					<a href="gerenciarchamado.php" class="a-ar">
						<span class="material-icons md-blue md-48" rel="tooltip" title="Gerenciar chamados" data-placement="top">assignment</span>
					</a>
					<a href="alterarsenha.php" class="a-ar">
						<span class="material-icons md-blue md-48" rel="tooltip" title="Alterar senha" data-placement="right">vpn_key</span>
					</a>
				</fieldset>
			</div>
			<div class="panel panel-default container panel-inicio">
				<nav class="navbar navbar-default">
					<div class="navbar-left">
						<h4>Meus chamados</h4>
					</div>
					<div class="navbar-right">
						<button id="abrir" class="btn btn-default nav-btn" rel="tooltip" title="Ir para chamado selecionado" data-placement="top">
							<span class="material-icons md-dark md-26">forum</span>
						</button>
						<button id="repassar" class="btn btn-default nav-btn nv-1 nv-0" rel="tooltip" title="Encaminhar chamado selecionado" data-placement="right">
							<span class="material-icons md-dark md-26">send</span>
						</button>

					</div>
				</nav>
				<table id="tabGenChamado" class="table table-condensed table-hover tableSection ">
					<thead>
						<tr>
							<th>Código</th>
							<th>Assunto</th>
							<th>Status</th>
							<th class="nv-0">Usuário</th>
							<th>Data de abertura</th>
							<th id="at">Atendente</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>
			<div id="modal" class="modal fade" role="dialog">
				<div class="modal-dialog modalManejameto">
					<div class="modal-content">
						<div id="modalHeader" class="modal-header">
							<button type="button" class="close" data-dismiss="modal">&times;</button>
							<h4 class="modal-title">Repassar chamado</h4>
						</div>
						<div class="modal-body">
							<div class="row">
								<form action="" role="form" id="formGerenciarChamado">
									<div class="form-group col-sm-12">
										<label for="atendente">Atendente</label>
										<select class="form-control" id="atendenteSelect" name="atendente" >

										</select>
									</div>
							</div>
						</div>
						<div id="modalFooter" class="modal-footer">
							<button type="button" id="repassarEnviar" class="btn btn-info">Repassar</button>
							<button type="reset" class="btn btn-default" data-dismiss="modal">Voltar</button>
						</div>
						</form>
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