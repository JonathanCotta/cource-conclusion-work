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
		<title>AtendimentoDeChamado</title>
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
					<ul class="nav navbar-nav nav-breadcrumbs ">
						<li><a href="inicio.php" class="nav-bc navbar-brand">Inicio</a></li>
						<li><a href="" class="nav-bc navbar-brand">Chamados</a></li>
						<li><a href="" class="nav-bc-final navbar-brand">Atendimento do chamado</a></li>
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
			<div class="panel panel-default container panel-chamado">
				<h4>Atendimento do chamado</h4>
				<h5>Status atual:&nbsp;<span id="stAtual"></span></h5>
				<form action="" role="form" class="form-chamado" id="formAtendimentoChamado">
					<div class="row">
						<div class="form-group col-sm-4">
							<label for="assunto">Assunto</label>
							<input type="text" maxlength="45" placeholder="Resumo do problema" name="assunto" id="assunto" class="form-control" readonly>
						</div>
						<div class="form-group col-sm-4">
							<label for="status">Status </label>
							<select class="form-control" id="status" name="status">
								<option selected disabled hidden></option>
								<option class="nv-0" value="1">Aguardando Terceiros</option>
								<option class="nv-0" value="2">Em homologação</option>
								<option class="nv-1" value="3">Homologado</option>
							</select>
						</div>
					</div>

					<div class="row">
						<div class="form-group col-sm-8">
							<label for="descricao">Descrição</label>
							<textarea rows="3" placeholder="Descreva seu problema" name="descricao" id="descricao" class="form-control" readonly></textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-8">
							<label for="resposta">Interação</label>
							<textarea rows="3" id="interacao" class="form-control" name="interacao" readonly></textarea>
						</div>
					</div>
					<div class="row">
						<div class="form-group col-sm-8">
							<label for="resposta">Resposta</label>
							<textarea rows="2" placeholder="Digite aqui a resposta à ocorrência" name="resposta" id="resposta" class="form-control"></textarea>
						</div>

					</div>
					<div class="btn-group" role="group" aria-label="...">
						<button type="button" class="btn btn-default" rel="tooltip" id="btnLogChamado" title="Visualizar log do chamado" data-placement="top">
							<span class="material-icons md-dark md-20">format_list_bulleted</span>
						</button>
						<button type="button" class="btn btn-default" rel="tooltip" id="btnAnexo" title="Visualizar anexo do chamado" data-placement="right">
							<span class="material-icons md-dark md-20">attach_file</span>
						</button>
					</div>
					<br/>
					<br/>
					<div class="form-group">
						<button type="button" class="btn btn-info" id="atendchamadoenv">Enviar</button>
						<button type="reset" class="btn btn-default" id="atendchamadovolt">Cancelar</button>
					</div>

				</form>
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