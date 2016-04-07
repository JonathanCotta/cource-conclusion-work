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
		<title>Novochamado</title>
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
						<li><a href="" class="nav-bc navbar-brand">Chamados</a></li>
						<li><a href="" class="nav-bc-final navbar-brand">Novo chamado</a></li>
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
			<div id="panel-novo-chamado" class="panel panel-default container panel-chamado">
				<form action="" role="form" class="form-chamado" id="formNovoChamado">
					<legend>Abertura de chamado</legend>
					<div class="row">

						<div class="form-group col-sm-4">
							<label for="plataforma">Plataforma</label>
							<select class="form-control" id="plataformaSelectChamado" name="plataforma" data-placement="left">

							</select>
						</div>
						<div id="caixacategoria" class="form-group col-sm-4">
							<label for="categoria">Categoria</label>
							<select class="form-control" id="categoriaSelectChamado" name="categoria" data-placement="bottom">

							</select>
						</div>
						<div id="caixaassunto" class="form-group col-sm-4 assunto">
							<label for="assunto">Assunto</label>
							<input type="text" maxlength="45" placeholder="Resumo do problema" id="assunto" name="assunto" class="form-control" data-placement="right">
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-8">
							<label for="descricao">Descrição</label>
							<textarea rows="4" placeholder="Descreva seu problema" id="descricao" name="descricao" class="form-control" data-placement="right"></textarea>
						</div>
						<input type="text" class="opNovChamado col-sm-2" name="op" value="inserir">
					</div>
					<div class="form-group">
						<input type="file" name="anexo" class="filestyle" data-input="false">
					</div>
					<button type="button" class="btn btn-info" id="novchamadoenv">Enviar</button>
					<button type="reset" class="btn btn-default" id="novchamadovolt">Cancelar</button>
				</form>
			</div>

		</section>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/bootstrap-filestyle.min.js"></script>
		<script src="js/valida.js"></script>
		<script src="js/script.js"></script>
	</body>

	</html>