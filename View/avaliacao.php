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
	<title>Avaliação</title>
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
					<li><a href="" class="nav-bc nav-brand">Chamados</a></li>
					<li><a href="" class="nav-bc-final nav-brand">Avaliação</a></li>
				</ul>
			</div>

			<ul class="nav navbar-nav navbar-right navbar-top hidden-xs">
				
				<li class="dropdown">
					<a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
						<span class="material-icons md-light md-26">account_circle</span>
					</a>
					<ul class="dropdown-menu">
						<li class="disabled"><a href="#"><?php echo $_SESSION["nome"] ?></a></li>
						<li><a href="#">Sobre o sistema</a></li>
						<li role="separator" class="divider"></li>
						<li><a id="sair" href="#">Sair</a></li>
					</ul>
				</li>
			</ul>

		</nav>
	</header>

	<section>
		<div class="panel panel-default container panel-aval">
			<legend><h4>Avaliação de atendimento</h4></legend>
			<form action="" role="form" class="form-chamado" id="formAvalicao">				
				<div class="row">
					<div class="form-group col-sm-4">
						<label for="tempoproblema">O tempo de resolução do problema foi satisfatório?</label>
						<select class="form-control" id="tempoproblema" name="tempoproblema" >
							<option selected disabled hidden value=""></option>
							<option value="5">Ótimo</option>
							<option value="4">Bom</option>
							<option value="3">Regular</option>
							<option value="2">Ruim</option>
							<option value="1">Péssimo</option>
						</select>
					</div>

					<div class="form-group col-sm-4">
						<label for="solucaoproblema">A solução do problema foi satisfatória?</label>
						<select class="form-control" id="solucaoproblema" name="solucaoproblema" required>
							<option selected disabled hidden value=""></option>
							<option value="5">Ótimo</option>
							<option value="4">Bom</option>
							<option value="3">Regular</option>
							<option value="2">Ruim</option>
							<option value="1">Péssimo</option>
						</select>
					</div>

					<div class="form-group col-sm-4">
						<label for="categoria">O retorno dado pelo atendente foi satisfatório?</label>
						<select class="form-control" id="retorno" name="solucaoproblema" required>
							<option selected disabled hidden value=""></option>
							<option value="5">Ótimo</option>
							<option value="4">Bom</option>
							<option value="3">Regular</option>
							<option value="2">Ruim</option>
							<option value="1">Péssimo</option>
						</select>
					</div>
				</div>
				<div class="row">
					<div class="form-group col-sm-8">
						<label for="descricao">Observação</label>
						<textarea rows="4" maxlenght="150" placeholder="Algo em especial a citar?" name="obs" id="obs" class="form-control"></textarea>
					</div>
				</div>
				<button type="button" class="btn btn-info" id="enviarAval">Enviar</button>
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