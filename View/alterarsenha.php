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
		<title>AterarSenha</title>
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
								
						</ul>
					</div>
					<ul class="nav navbar-nav nav-breadcrumbs">
						<li><a href="" class="nav-bc nav-brand">Inicio</a></li>
						<li><a href="" class="nav-bc nav-brand">Usuários</a></li>
						<li><a href="" class="nav-bc-final nav-brand">Alteração de senha</a></li>
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
			<div class="panel panel-default container panel-senha">
				<legend><h4>Alteração de senha</h4></legend>

				<form action="" role="form" class="form-senha" id="formAlterarSenha">
					<div class="row">
						<div class="form-group has-feedback col-sm-8">
							<label for="pwdatual">Nova senha</label>
							<input type="password" maxlength="6" class="form-control pwd" id="pwdnova" data-placement="right">
							<span class="material-icons md-dark md-20 form-control-feedback">visibility</span>
						</div>
					</div>
					<button type="button" class="btn btn-info" id="altpwd">Alterar</button>
					<button type="button" class="btn" id="altpwdVoltar">Voltar</button>
				</form>
			</div>

		</section>
		<script src="js/jquery-1.11.3.min.js"></script>
		<script src="js/modernizr.custom.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/valida.js"></script>
		<script src="js/script.js"></script>
	</body>

	</html>