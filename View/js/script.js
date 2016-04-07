/* global $ */
// função para identificar reativação de um registro
function ativFunc() {
	if ($("#reativar").prop("checked")) {
		return "reativar";
	}
	else {
		return "alterar";
	}
}

// função para exibir tabela
function carregaTabela(tab, arg1) {
	switch (tab) {
		case "tabUsuario":
			$.post("../Controller/_usuario.php", { statusPesquisa: arg1, op: "visualizar" })
				.done(function (data) {
					$("tbody").html(data);
					$(".invisivel").hide();
				});
			break;
		case "tabPlataforma":
			$.post("../Controller/plataforma.php", { statusPesquisa: arg1, op: "visualizar" })
				.done(function (data) {
					$("tbody").html(data);
				});
			break;
		case "tabDepartamento":
			$.post("../Controller/departamento.php", { statusPesquisa: arg1, op: "visualizar" })
				.done(function (data) {
					$("tbody").html(data);
				});
			break;
		case "tabCategoria":
			$.post("../Controller/categoria.php", { statusPesquisa: arg1, op: "visualizar" })
				.done(function (data) {
					$("tbody").html(data);

				});
			break;
		case "tabGrupo":
			$.post("../Controller/grupo.php", { statusPesquisa: arg1, op: "visualizar" })
				.done(function (data) {
					$("tbody").html(data);
				});
			break;
		case "tabCargo":
			$.post("../Controller/cargo.php", { statusPesquisa: arg1, op: 'visualizar' })
				.done(function (data) {
									
					$("tbody").html(data);					
				})
				.fail(function () {

				});
			break;
		case "tabManejaGrupo":
			$.post("../Controller/atendente.php", { idGrupo: arg1, op: 'visualizar' })
				.done(function (data) {
					$("tbody").html(data);
				});
			break;
		case "tabGenChamado":
			if(arg1 == null){
				arg1 = "ativo";
			}
			$.post("../Controller/chamado.php", {statusPesquisa: arg1, op: 'visualizarTodos' })
				.done(function (data) {
					$("tbody").html(data);
					$(".invisivel").hide();
				});
			break;
		case "tabLogChamado":
			$.post("../Controller/chamado.php", { op: 'visualizarLog' })
					.done(function (data) {
						$("tbody").html(data);
					});
			break;
		case "tabAvalGrupo":
			$.post("../Controller/avaliacao.php", { op: 'exibirAvalicoesGrupo' })
					.done(function (data) {
						$("tbody").html(data);
					});
			break;
		case "tabAvalAtendente":
			$.post("../Controller/avaliacao.php", {idGrupo:0, op: 'exibirAvalicoesAtendente' })
					.done(function (data) {
						$("tbody").html(data);
					});
			break;		
		default:
			break;
	}
}

$(document).ready(function () {	
	/*Declaração de váriaveis */
	
	// variaveis de seleção dos dados da tabela 
	var idSelecionado = null;
	var linha = null;
	
	// variaveis dos elementos da pagina
	var formNome = $("#modal form"); /* recebe o nome do formulário da página*/
	var formNomeEx = $("#modalEx form");  /* recebe o nome do formulário de exclusão*/
	var tabNome = $("body table").prop("id");  /* recebe o nome o nome da tabela*/
	var pagina = $("title").text(); // recebe o nome da pagina
	var operacao = null;
	
	//variaveis para pesquisa 
	var statPesquisa = $("input[name=optpesquisa]:radio:checked").val(); /* status da pesquisa (ativo/inativo) */
	//var datainicio;
	//var datafim;
	
	// objetos
	var usuario;
	var chamado;
	var avaliacao;
	
	// exibe tabelas
	if (tabNome != "tabManejaGrupo") {
		carregaTabela(tabNome, statPesquisa);
	}
			
	/* requisição para saber qual o tipo de sessão */
	$.post("../Controller/_usuario.php", { op: 'verificarSessao' })
		.done(function (data) {
			usuario = $.parseJSON(data);
			if(usuario.avalPendente && pagina != "Avaliação"){
				document.location.href = "avaliacao.php";
			}						
			if (usuario.perfil == "Comum") {
				$(".nv-0").hide();
			}
			if (usuario.perfil == "Atendente") {
				$(".nv-1").hide();
			}						
			if(tabNome == "tabGenChamado"){
				if(usuario.perfil == "Atendente" || usuario.perfil == "Administrador"){
					$("#at").hide();				
				}
				carregaTabela(tabNome, statPesquisa);
			}
			if(usuario.pwdpass){
				$("#altpwdVoltar").show();
			}
			else{
				$("#altpwdVoltar").hide();
			}
		});
		
	/* Função de seleção da linha tabela do crud*/
	$("tbody").on('click', 'tr', function () {
		$("tr").removeClass("selecionado");
		$(this).toggleClass("selecionado");
		linha = $(this).children("td");
		var id = linha.eq(0).text();		
		if (idSelecionado == id && tabNome == "tabGenChamado") {
			if(statPesquisa == "inativo"){
				if(usuario.perfil != "Atendente"){	
					$.post("../Controller/chamado.php", {idchamado: id ,op:"selecionarUm"});		
					document.location.href = "avaliacaovisualizar.php";
				}
			}
			else {
				//seleciona chamado ativo
				$.post("../Controller/chamado.php", {idchamado: id ,op:"selecionarUm"});		
				document.location.href = "atendimentochamado.php";
			}
			
		}
		else {
			if (tabNome == "tabUsuario") {
				idSelecionado = linha.eq(0).prop("id");
			}
			else {
				idSelecionado = id;				
			}
		}
	});
	
		
	/* função para saber valor da pesquisa se é ativo ou inativo */
	$("input[name=optpesquisa]:radio").change(function () {
		statPesquisa = $("input[name=optpesquisa]:radio:checked").val();
		if (statPesquisa == "inativo") {
			$("#excluir").hide();
			$("#inserir").hide();
			$("#filtroData").show();
			$("#resetSenha").prop("checked",false)
			if(tabNome == "tabGenChamado"){
				$("#dt").text(" de encerramento");
			}
			carregaTabela(tabNome, statPesquisa);
			idSelecionado = null;
		}
		else {
			$("#excluir").show();
			$("#inserir").show();
			$("#filtroData").hide();
			if(tabNome == "tabGenChamado"){
				$("#dt").text(" de abertura");
			}
			carregaTabela(tabNome, statPesquisa);
			idSelecionado = null;
		}
	});
	
	/* função para deslogar */
	$("#sair").click(function () {
		$.post("../Controller/_usuario.php", { op: "deslogar" })
			.done(function (data) {
				document.location.href = "../index.html";
			});
	});
	
	/*Animação menu */
	$('#dl-menu').dlmenu({
		animationClasses: { classin: 'dl-animate-in-1', classout: 'dl-animate-out-1' }
	});
			
	// Função para sair do log e voltar para o chamado
	$("#voltarLog").click(function(){
		document.location.href = "atendimentochamado.php";
	});
				
    /*Função de visualização de senha */
	$(".form-control-feedback").click(function () {
		var pwdInput = $(this).prev();
		var px = pwdInput.prop("type");
		if (px == "password") {
			pwdInput.removeProp("type");
			$(this).text("visibility_off")
		}
		else {
			pwdInput.prop("type", "password");
			$(this).text("visibility")
		}
	});
	
	/* função de preenchimento dos selects separando as requisições por pagina */
	switch (tabNome) {
		case "tabUsuario":
			/* Função de preenchimento do select de departamentos */
			$.post("../Controller/departamento.php", { op: "gerarLista" })
				.done(function (data) {
					$("#departamentoSelect").html(data);
				});
	
			/* Função de preenchimento do select de cargo */
			$.post("../Controller/cargo.php", { op: "gerarLista" })
				.done(function (data) {
					$("#cargoSelect").html(data);
				});
			break;
		case "tabManejaGrupo":
			// Função para preencher o select de grupo no manejamento de grupo
			$.post("../Controller/grupo.php", { op: "gerarLista" })
				.done(function (data) {
					$("#grupoSelect").html(data);
				});
			/* Função para visualização de atendentes de acordo com o grupo selecionado */
			$("#grupoSelect").change(function () {
				// preenche a tabela com os atendentes pertecentes ao grupo selecionado
				$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "visualizar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				// preenche a lista de atentendes com os atendentes que ainda não pertecem ao grupo
				$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarLista" })
					.done(function (data) {
						$("#atendenteSelect").html(data);
					});
				// preenche a lista de atendentes com os atendentes com matricula inativa no grupo selecionado
				$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarListaInativos" })
					.done(function (data) {
						$("#atendenteInativoSelect").html(data);
					});
			});	
			break;
		case "tabCategoria":
			// Função para preencher o select de plataforma na tela de categoria
			$.post("../Controller/plataforma.php", { op: "gerarLista" })
				.done(function (data) {
					$("#plataformaSelect").html(data);
				});
			break;
		case "tabPlataforma":
			// Função para preencher o select de grupo na tela de plataforma
			$.post("../Controller/grupo.php", { op: "gerarLista" })
				.done(function (data) {
					$("#grupoSelectPlat").html(data);
					
				});
			break;		
		case "tabAvalAtendente":
			// Função para preencher o select de grupo no manejamento de grupo
			$.post("../Controller/grupo.php", { op: "gerarLista" })
				.done(function (data) {
					$("#grupoSelect").html(data);
					$('#grupoSelect option').each(function () {
						if ($(this).text() == "Genérico") {
							$(this).hide();
						}
					});
					$('#grupoSelect option').each(function () {
						if ($(this).text() == "") {
							$(this).show();
							$(this).prop("disabled",false);
						}
					});
				});
			$("#grupoSelect").change(function(){
				$.post("../Controller/avaliacao.php", { idGrupo: $("#grupoSelect").val(),op: "exibirAvalicoesAtendente" })
					.done(function (data) {
						$("tbody").html(data);
					});
			});
			break;
		default:
			break;
	}		
	
	// Imprimir relatorio	   
	$("#imprimir").click(function () {
		window.print();
	});
	                       
	/*Configuração e abertura dos modais */	
	/*Abertura do modal alterar */
	$('#alterar').click(function () {
		if (idSelecionado == null) {
			alert("selecione um registro");
		}
		else if (linha.eq(1).text() == "Outra" || linha.eq(1).text() == "Genérico"){
			alert("Este registro não pode ser alterado");
		}
		else {
			$("#modal").modal("show");
			$('#funcModal').text('Alterar ');
			$('#salvarModal').show();
			$('#reativarBox').hide();
			$('#resetBox').hide();
			if (statPesquisa == "inativo") { $('#reativarBox').show(); };
			if (statPesquisa == "ativo" && usuario.perfil != "Comum") { $('#resetBox').show(); };
			$('.form-control').prop('disabled', false);
			operacao = "alterar";
			//preenche valores selecionados do formulário
			switch (formNome.prop("id")) {
				case "formUsuario":
					//preenchimento do formulário de alteração usuario
					$("#pwdBox").hide();
					$("#cpf").val(linha.eq(0).text());
					$("#nome").val(linha.eq(1).text());
					$("#login").val(linha.eq(2).text());
					$("#email").val(linha.eq(3).text());
					$("#ramal").val(linha.eq(4).text());
					$("#celular").val(linha.eq(5).text());
					$('#departamentoSelect option').each(function () {
						if ($(this).val() == linha.eq(6).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					$('#cargoSelect option').each(function () {
						if ($(this).val() == linha.eq(7).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					$('#nivelacesso option').each(function () {
						if ($(this).text() == linha.eq(8).text()) {
							$(this).prop("selected", true);
						}
					});
				case "formPlataforma":
					//preenchimento do formulário de alteração de plataforma
					$("#nome").val(linha.eq(1).text());
					$('#grupoSelect option').each(function () {
						if ($(this).val() == linha.eq(2).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					break;
				case "formDepartamento":
					//preenchimento do formulário de alteração de departamento
					$("#nome").val(linha.eq(1).text());
					$('#prioridade option').each(function () {
						if ($(this).val() == linha.eq(2).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					break;
				case "formCategoria":
					//preenchimento do formulário de alteração  de categoria
					$("#nome").val(linha.eq(1).text());
					$('#plataformaSelect option').each(function () {
						if ($(this).val() == linha.eq(2).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					$('#prioridade option').each(function () {
						if ($(this).val() == linha.eq(3).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					break;
				case "formGrupo":
					//preenchimento do formulário de alteração  de grupo
					$("#nome").val(linha.eq(1).text());
					break;
				case "formCargo":
					//preenchimento do formulário de alteração de cargo
					$("#nome").val(linha.eq(1).text());
					$('#prioridade option').each(function () {
						if ($(this).val() == linha.eq(2).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					break;
				default:
					break;
			}
		}
	});	
	
	/*Abertura do modal inserir */
	$('#inserir').click(function () {
		$("#modal").modal("show");
		$('#funcModal').text('Criar ');
		$('#salvarModal').show();
		$('#reativarBox').hide();
		$('#resetBox').hide();
		$('.form-control').prop('disabled', false);
		operacao = "adicionar";
		/* reset formulário */
		switch (formNome.prop("id")) {
			case "formUsuario":
				// reset do formulario inserir de usuario
				$("#pwdBox").show();
				$("#cpf").val("");
				$("#nome").val("");
				$("#login").val("");
				$("#pwd").val("");
				$("#email").val("");
				$("#ramal").val("");
				$("#celular").val("");
				$("#departamentoSelect").val(0).prop("selected", true);
				$("#cargoSelect").val(0).prop("selected", true);
				$("#nivelacesso").val(0).prop("selected", true);
				break;
			case "formPlataforma":
				// reset do formulario inserir de plataforma
				$("#nome").val("");
				$('#grupoSelect').val(0).prop("selected", true);
				break;
			case "formDepartamento":
				// reset do formulario inserir de  departamento
				$("#nome").val("");
				$('#prioridade').val(0).prop("selected", true);
				break;
			case "formCategoria":
				// reset do formulario inserir de  categoria
				$("#nome").val("");
				$('#plataformaSelect').val(0).prop("selected", true);
				$('#prioridade').val(0).prop("selected", true);
				break;
			case "formGrupo":
				// reset do formulario inserir de grupo
				$("#nome").val("");
				break;
			case "formCargo":
				// reset do formulario inserir de  cargo
				$("#nome").val("");
				$('#prioridade').val(0).prop("selected", true);
				break;
			case "formManejaGrupo":
				// reset do formulario inserir de manejamento de grupo
				$('#atendenteSelect').val(0).prop("selected", true);
				break;
			default:
				break;
		}
	});
	
	/* Reativação de atendente */
	$("#reativarAtendente").click(function () {
		$("#modalReativar").modal("show");
	});
	$("#reativarModal").click(function () {
		var idGrupo = $("#grupoSelect").val();
		$.post("../Controller/atendente.php", { idGrupo: idGrupo, idUsuario: $("#atendenteInativoSelect").val(), op: "reativarAtendente" })
			.done(function (msg) {
				carregaTabela(tabNome, idGrupo);
				idSelecionado = null;
				// preenche a lista de atentendes com os atendentes que ainda não pertecem ao grupo
				$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarLista" })
					.done(function (data) {
						$("#atendenteSelect").html(data);
					});
				// preenche a lista de atendentes com os atendentes com matricula inativa no grupo selecionado
				$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarListaInativos" })
					.done(function (data) {
						$("#atendenteInativoSelect").html(data);
					});
			});
	});
	
	// envia dados dos formulários para o controle.
	switch (formNome.prop("id")) {
		case "formUsuario":
			//validação do nome
			$("#login").blur(function(){
				$.post("../Controller/_usuario.php",{nomeUsuario:$("#login").val(), op:"validaLogin"})
					.done(function(data){
						var validaLogin = $.parseJSON(data);
						alert(operacao);												
						if (validaLogin.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			//validação do cpf
			$("#cpf").blur(function(){
				$.post("../Controller/_usuario.php",{cpf:$("#cpf").val(), op:"validaCPF"})
					.done(function(data){
						var validaCPF = $.parseJSON(data);
						alert(operacao);												
						if (validaCPF.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			
			// envio do formulário de usuario
			$("#salvarModal").click(function () {
				var caminho;
				var cpf = $("#cpf").val();
				var nomeUsuario = $("#nome").val();
				var login = $("#login").val();
				var pwd = $("#pwd").val();
				var email = $("#email").val();
				var ramal = $("#ramal").val();
				var cel = $("#celular").val();
				var depId = $("#departamentoSelect").val();
				var cargoId = $("#cargoSelect").val();
				var nvlacesso = $("#nivelacesso").val();
				if (operacao == "alterar") {
					if ($("#resetSenha").prop("checked")) {
						operacao = "resetSenha"
					}
					else {
						operacao = ativFunc();
					}
				}
				if (nvlacesso == "Atendente") {
					caminho = "../Controller/atendente.php";
				}
				else {
					caminho = "../Controller/_usuario.php";
				}
				$.ajax({
					method: "POST",
					url: caminho,
					data: { idUsuario: idSelecionado, cpf: cpf, nomeUsuario: nomeUsuario, login: login, senha: pwd, email: email, ramal: ramal, cel: cel, depId: depId, cargoId: cargoId, nvlacesso: nvlacesso, status: status, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formPlataforma":
			//validação do nome
			$("#nome").blur(function(){
				$.post("../Controller/plataforma.php",{nomePlataforma:$("#nome").val(), op:"validaNome"})
					.done(function(data){
						var validaNome = $.parseJSON(data);
						alert(operacao);												
						if (validaNome.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			// envio do formulário de plataforma
			$("#salvarModal").click(function () {
				var nomePlataforma = $("#nome").val();
				var grupoId = $("#grupoSelectPlat").val();
				if (operacao == "alterar") {
					operacao = ativFunc();
				}
				$.ajax({
					method: "POST",
					url: "../Controller/plataforma.php",
					data: { idPlataforma: idSelecionado, nomePlataforma: nomePlataforma, grupoId: grupoId, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formDepartamento":
			//validação do nome
			$("#nome").blur(function(){
				$.post("../Controller/departamento.php",{nomeDepartamento:$("#nome").val(), op:"validaNome"})
					.done(function(data){
						var validaNome = $.parseJSON(data);
						alert(operacao);												
						if (validaNome.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			// envio do formulário de departamento
			$("#salvarModal").click(function () {
				var nomeDepartamento = $("#nome").val();
				var prioridadeDep = $("#prioridade").val();
				if (operacao == "alterar") {
					operacao = ativFunc();
				}
				$.ajax({
					method: "POST",
					url: "../Controller/departamento.php",
					data: { idDepartamento: idSelecionado, nomeDepartamento: nomeDepartamento, prioridadeDep: prioridadeDep, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formCategoria":
			//validação do nome
			$("#nome").blur(function(){
				$.post("../Controller/categoria.php",{nomeCategoria:$("#nome").val(), op:"validaNome"})
					.done(function(data){
						var validaNome = $.parseJSON(data);
						alert(operacao);												
						if (validaNome.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			// envio do formulário de categoria
			$("#salvarModal").click(function () {
				var nomeCategoria = $("#nome").val();
				var plataformaId = $("#plataformaSelect").val();
				var prioridadeCat = $("#prioridade").val();
				if (operacao == "alterar") {
					operacao = ativFunc();
				}
				$.ajax({
					method: "POST",
					url: "../Controller/categoria.php",
					data: { idCategoria: idSelecionado, nomeCategoria: nomeCategoria, plataformaId: plataformaId, prioridadeCat: prioridadeCat, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formGrupo":
			//validação do nome
			$("#nome").blur(function(){
				$.post("../Controller/grupo.php",{nomeGrupo:$("#nome").val(), op:"validaNome"})
					.done(function(data){
						var validaNome = $.parseJSON(data);
						alert(operacao);												
						if (validaNome.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			// envio do formulário de grupo
			$("#salvarModal").click(function () {
				var nomeGrupo = $("#nome").val();
				if (operacao == "alterar") {
					operacao = ativFunc();
				}
				$.ajax({
					method: "POST",
					url: "../Controller/grupo.php",
					data: { idGrupo: idSelecionado, nomeGrupo: nomeGrupo, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formCargo":
			//validação do nome
			$("#nome").blur(function(){
				$.post("../Controller/cargo.php",{nomeCargo:$("#nome").val(), op:"validaNome"})
					.done(function(data){
						var validaNome = $.parseJSON(data);
						alert(operacao);												
						if (validaNome.valido || operacao =="alterar"){
							alert("valido");
						}
						else{
							alert("invalido");
						}
					});
			});
			// envio do formulário de cargo
			$("#salvarModal").click(function () {
				var nomeCargo = $("#nome").val();
				var prioridadeCar = $("#prioridade").val();
				if (operacao == "alterar") {
					operacao = ativFunc();
				}
				$.ajax({
					method: "POST",
					url: "../Controller/cargo.php",
					data: { idCargo: idSelecionado, nomeCargo: nomeCargo, prioridadeCar: prioridadeCar, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "formManejaGrupo":
			// envio do formulário de manejamento de grupo
			$("#salvarModal").click(function () {
				var idUsuario = $("#atendenteSelect").val();
				var idGrupo = $("#grupoSelect").val();
				$.ajax({
					method: "POST",
					url: "../Controller/atendente.php",
					data: { idUsuario: idUsuario, idGrupo: idGrupo, op: "adicionarNoGrupo" },
				})
					.done(function (msg) {
						carregaTabela(tabNome, idGrupo);
						idSelecionado = null;
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		default:
			break;
	}
				
	/*Abertura do modal visualizar */
	$('#visualizar').click(function () {
		if (idSelecionado == null) {
			alert("selecione um registro");
		}
		else {
			$("#modal").modal("show");
			$('#funcModal').text('Visualizar ');
			$('#salvarModal').hide();
			$('#reativarBox').hide();
			$('.form-control').prop('disabled', true);
			$('#pesquisa').prop('disabled', false);
			switch (tabNome) {
				case "tabUsuario":
					// preenche o formulario de visualização de usuario
					$("#pwdBox").hide();
					$("#cpf").val(linha.eq(0).text());
					$("#nome").val(linha.eq(1).text());
					$("#login").val(linha.eq(2).text());
					$("#email").val(linha.eq(3).text());
					$("#ramal").val(linha.eq(4).text());
					$("#celular").val(linha.eq(5).text());
					$('#departamentoSelect option').each(function () {
						if ($(this).val() == linha.eq(6).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					$('#cargoSelect option').each(function () {
						if ($(this).val() == linha.eq(7).prop("id")) {
							$(this).prop("selected", true);
						}
					});
					$('#nivelacesso option').each(function () {
						if ($(this).text() == linha.eq(8).text()) {
							$(this).prop("selected", true);
						}
					});
					break;
				default:
					break;
			}
		}
	});
	
	/*Botão de excluir */
	$("#excluir").click(function () {
		if (idSelecionado == null) {
			alert("selecione um registro");
		}
		else if (linha.eq(1).text() == "Outra" || linha.eq(1).text() == "Genérico"){
			alert("Este registro não pode ser excluido");
		}
		else {
			$("#modalEx").modal("show");
			$('#funcModalEx').text('Exclusão de ');
			operacao = "excluir";
		}
	});
	
	/* envio de dados para exclusão */
	switch (formNomeEx.prop("id")) {
		case "usuarioEx":
			// exclusão de usuario 
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/_usuario.php",
					data: { idUsuario: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});

			break;
		case "plataformaEx":
			// exclusão de plataforma
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/plataforma.php",
					data: { idPlataforma: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});

			break;
		case "departamentoEx":
			// exclusão de departamento
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/departamento.php",
					data: { idDepartamento: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "categoriaEx":
			// exclusão de categoria
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/categoria.php",
					data: { idCategoria: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "grupoEx":
			// exclusão de grupo
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/grupo.php",
					data: { idGrupo: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		case "cargoEx":
			// exclusão de cargo
			$("#confirmaEx").click(function () {
				$.ajax({
					method: "POST",
					url: "../Controller/cargo.php",
					data: { idCargo: idSelecionado, op: operacao },
				})
					.done(function (msg) {
						carregaTabela(tabNome, statPesquisa);
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});

			break;
		case "manejaGrupoEx":
			// exclusão de manejamento de grupo (inativa atendente selecionado)
			$("#confirmaEx").click(function () {
				var idGrupo = $("#grupoSelect").val();
				$.ajax({
					method: "POST",
					url: "../Controller/atendente.php",
					data: { idUsuario: idSelecionado, idGrupo: idGrupo, op: "removerDoGrupo" },
				})
					.done(function (msg) {
						carregaTabela(tabNome, idGrupo);
						// preenche a lista de atentendes com os atendentes que ainda não pertecem ao grupo
						$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarLista" })
							.done(function (data) {
								$("#atendenteSelect").html(data);
							});
						// preenche a lista de atendentes com os atendentes com matricula inativa no grupo selecionado
						$.post("../Controller/atendente.php", { idGrupo: $("#grupoSelect").val(), op: "gerarListaInativos" })
							.done(function (data) {
								$("#atendenteInativoSelect").html(data);
							});
					})
					.fail(function (msg) {
						alert("Erro ao enviar dados");
					});
			});
			break;
		default:
			break;
	}
	
	/*Abertura do chamado selecionado */
	$('#abrir').click(function () {
		if (idSelecionado == null) {
			alert("selecione um registro");
		}
		else {			
			if(statPesquisa == "inativo"){
				if(usuario.perfil != "Atendente"){
					$.post("../Controller/chamado.php", {idchamado: idSelecionado ,op:"selecionarUm"});		
					document.location.href = "avaliacaovisualizar.php";
				}				
			}
			else {
				//seleciona chamado ativo
				$.post("../Controller/chamado.php", {idchamado: idSelecionado ,op:"selecionarUm"});		
				document.location.href = "atendimentochamado.php";
			}
		}
	});
	
	/* repassar chamado */
	$("#repassar").click(function () {
		if (idSelecionado == null) {
			alert("selecione um registro");
		}
		else {			
			$("#modal").modal("show");
			$.post("../Controller/atendente.php", { idGrupo: linha.eq(5).text(), op: "gerarListaRepasse" })
					.done(function (data) {
						$("#atendenteSelect").html(data);
					});						
		}
	});
	$("#repassarEnviar").click(function () {
			
			$.post("../Controller/chamado.php", { idusuario:usuario.id, idchamado: idSelecionado, atendente: $("#atendenteSelect").val() , op: "repassar" })
					.done(function (data) {
						carregaTabela(tabNome, statPesquisa);						
					});
	});

	
	/* Form pesquisa */
	/*Botão de pesquisa */
	$("#btnpesquisa").click(function () {
		var pesquisa = $("#pesquisa").val();
		switch (tabNome) {
			case "tabUsuario":
				//pesquisa de usuario 
				$.post("../Controller/_usuario.php", { pesquisa: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
						$(".invisivel").hide();
					});
				break;
			case "tabPlataforma":
				//pesquisa de plataforma
				$.post("../Controller/plataforma.php", { pesquisa: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabDepartamento":
				//pesquisa de departamento
				$.post("../Controller/departamento.php", { pesquisa: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabCategoria":
				//pesquisa de categoria
				$.post("../Controller/categoria.php", { pesquisa: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabGrupo":
				//pesquisa de grupo
				$.post("../Controller/grupo.php", { pesquisaGrupo: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabCargo":
				//pesquisa de cargo
				$.post("../Controller/cargo.php", { pesquisa: pesquisa, statusPesquisa: statPesquisa, op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabManejaGrupo":
				//pesquisa de manejamento de grupo
				$.post("../Controller/atendente.php", { pesquisa: pesquisa, idGrupo: $("#grupoSelect").val(), op: "pesquisar" })
					.done(function (data) {
						$("tbody").html(data);
					});
				break;
			case "tabAvalGrupo":
			    //pesquisa de avaliação de grupo
				$.post("../Controller/avaliacao.php", { pesquisa: pesquisa, pag: "gp", op: "pesquisar"})
						.done(function (data) {
							$("tbody").html(data);
						});
				break;
			case "tabAvalAtendente":
			 	//pesquisa de avaliação de atendente
				$.post("../Controller/avaliacao.php", {pesquisa: pesquisa, pag: "at" ,idGrupo: $("#grupoSelect").val(), op: "pesquisar" })
						.done(function (data) {
							$("tbody").html(data);
						});
				break;
			case "tabGenChamado":
			 	//pesquisa de avaliação de atendente
				var intervalo = $("#intervaloTempo").val();
				if (intervalo != "" && statPesquisa == "inativo") {
					$.post("../Controller/chamado.php", {pesquisa: pesquisa, intervalo: intervalo,  statusPesquisa: statPesquisa, op: "pesquisar" })
						.done(function (data) {
							$("tbody").html(data);
							$(".invisivel").hide();
					});	
				} else {
					$.post("../Controller/chamado.php", {pesquisa: pesquisa,  statusPesquisa: statPesquisa, op: "pesquisar" })
						.done(function (data) {
							$("tbody").html(data);
							$(".invisivel").hide();
						});
				}
				
				break;
			default:
				break;
		}
	});
		
	//filtro data
	$("#filtroData").hide();
	$("#filtroData").click(function () {
		$("#modalData").modal("show");			
	});
	$("#filtrar").click(function () {
			var intervalo = $("#intervaloTempo").val();
			var pesquisa = $("#pesquisa").val();
			
			$.post("../Controller/chamado.php", {pesquisa: pesquisa, intervalo: intervalo,  statusPesquisa: statPesquisa, op: "pesquisar" })
						.done(function (data) {
							$("tbody").html(data);
							$(".invisivel").hide();
			});
		});

	/* novo chamado*/
	/* submissão do form  de novo chamado*/
	/* função para definir visibilidade do "assunto" */
	//$(".opNovChamado").hide();
	$("#plataformaSelectChamado").change(function () {		
			if ($("#plataformaSelectChamado option:selected").text() == "Outra") {
				$("#caixaassunto").show();
				$("#caixacategoria").hide();
				$.post("../Controller/categoria.php", {plataformaId: $("#plataformaSelectChamado").val(), op :"gerarLista"})
				.done(function(data){
					$("#categoriaSelectChamado").html(data);
					$('#categoriaSelectChamado option').each(function () {
						if ($(this).text() == "Outra") {
							$(this).prop("selected", true);
						} });
				});
			} else {
				$("#caixaassunto").hide();
				$("#caixacategoria").show();
				$.post("../Controller/categoria.php", {plataformaId: $("#plataformaSelectChamado").val(), op :"gerarLista"})
				.done(function(data){
					$("#categoriaSelectChamado").html(data);
				});
			}
	});
	$("#categoriaSelectChamado").change(function () {
		if ($("#categoriaSelectChamado option:selected").text() == "Outra") {
			$("#caixaassunto").show();
		} else {
			$("#caixaassunto").hide();
		}
	});
	if(pagina == "Novochamado"){
		$.post("../Controller/plataforma.php", {op:"gerarListaChamado"})
				.done(function(data){
					$("#plataformaSelectChamado").html(data);
				});
	}
	
	$("#formNovoChamado").submit((function(e) {
		e.preventDefault();
		$.ajax({
        	url: "../Controller/chamado.php",
			type: "POST",
			data: new FormData(this),
			contentType: false,
    	    cache: false,
			processData:false})
			.done(function(data){
				alert("Chamado realizado com sucesso");
				document.location.href = "gerenciarchamado.php";
			})
			.fail(function(data){
				alert("erro no envio do arquivo");
			});			   
	   }));
	// envia form do novo chamado
	$("#novchamadoenv").click(function () {
		$("#formNovoChamado").submit();
		//$('#formNovoChamado').each (function(e){
  		//		this.reset();
		//});
		//$(".badge").text("");				
	});
	// voltar ao inicio
	$("#novchamadovolt").click(function () {
		document.location.href = "inicio.php";
	});
	
	/* Form atendimento chamado */
	if(pagina == "AtendimentoDeChamado"){
		$("#conversa").prop("disabled", true);
		$("#assunto").prop("disabled", true);
		$("#descricao").prop("disabled", true);
		$("#interacao").prop("disabled", true);
		$(".sysstatus").prop("disabled", true);
		$.post("../Controller/chamado.php",{op: "visualizarUm"})
		.done(function(data){
			chamado = $.parseJSON(data);			
			$("#descricao").val(chamado.descricao);
			$("#assunto").val(chamado.assunto);
			$("#interacao").val(chamado.interacao);
			$("#stAtual").html(chamado.status);
			//$('#status option').each(function () {
			//	if ($(this).text() == chamado.status) {
			//		$(this).prop("selected", true);
			//	}
			//});
			
		});
	}	
	
	// visualizar anexo
	$("#btnLogChamado").click(function(){
		document.location.href = "logchamado.php";
	});
	
	// visualizar log do chamado
	$("#btnAnexo").click(function(){
		window.open(chamado.anexo ,"_blank");		
	});
	
	$("#atendchamadovolt").click(function () {
		document.location.href = "gerenciarchamado.php";
	});
	$("#atendchamadoenv").click(function () {		
		$("#interacao").prop("disabled", false);
		var statusChamado = $("#status option:selected").text();
		var interacao = $("#interacao").val();
		var resposta = $("#resposta").val();
		
		if(interacao == ""){
			interacao = "Vazio";
		}
		$("#interacao").prop("disabled", true);
		switch (statusChamado) {
			case "Homologado":				
				$.post("../Controller/chamado.php", { interacao: interacao ,idusuario: usuario.id,  statusChamado: "Homologado", resposta: resposta, op: "alterar" })
					.fail(function () {
						alert("Erro ao enviar dados")
					});
				$.post("../Controller/avaliacao.php", { idchamado: usuario.idChamado, op: "gerar" })
					.done(function(){
						document.location.href = "avaliacao.php";
					})
					.fail(function () {
						alert("Erro ao enviar dados");
					});				
				break;
			case "Em homologação":
				var msg = "Chamado " + chamado.idChamado +" ,aguarda para ser homologado";				
				$.post("../Controller/chamado.php", {interacao: interacao, atendente: usuario.login,  statusChamado: "Em homoloção", resposta: resposta, op: "alterar" })
					.done(function(){
						alert(chamado.email);
						window.location.href = "mailto:"+chamado.email+"?subject= Homologar chamado&body="+msg;
					})
					.fail(function () {
						alert("Erro ao enviar dados")
					});
				break;
			case "Aguardando Terceiros":
			$.post("../Controller/chamado.php", { interacao: interacao,idusuario: usuario.id,  statusChamado: "Aguardando terceiros", resposta: resposta, op: "alterar" })
					.done(function(){
						window.location.href = "mailto:";
					})
					.fail(function () {
						alert("Erro ao enviar dados")
					});
				break;
			default:
				if (resposta != ""){
					if(usuario.perfil == "Comum"){
						statusChamado = "Aguardando atendente"
					}
					if(usuario.perfil == "Atendente"){
						statusChamado = "Aguardando usuário"
					}					
				}	
				$.post("../Controller/chamado.php", { interacao: interacao,idusuario: usuario.id,  statusChamado: statusChamado, resposta: resposta, op: "alterar" })
					.done(function(){
						
					})
					.fail(function () {
						alert("Erro ao enviar dados")
					});				
				break;
		}

	
	});	
	
	/* Form de avaliação */
	$("#enviarAval").click(function () {
		var tmpproblema = $("#tempoproblema").val();
		var slcproblema = $("#solucaoproblema").val();
		var retorno = $("#retorno").val();
		var obs = $("#obs").val();		
		$.ajax({
			method: "POST",
			url: "../Controller/avaliacao.php",
			data: { tmpproblema: tmpproblema, slcproblema: slcproblema, retorno: retorno, obs: obs, op:"avaliar"},
		})
			.done(function (msg) {
				alert("Dados enviados");
			})
			.fail(function (msg) {
				alert("Erro ao enviar dados");
			});
	});
	$("#voltarGenChamado").click(function(){
		document.location.href = "gerenciarchamado.php";
	});
	if(pagina == "Visualização de avaliação"){
		$.post("../Controller/avaliacao.php", { op: 'exibirAvaliacao' })
		.done(function (data) {
			avaliacao = $.parseJSON(data);
			$('#tempoproblema option').each(function () {
						if ($(this).val() == avaliacao.avTempo) {
							$(this).prop("selected", true);
						}
			});
			$('#solucaoproblema option').each(function () {
						if ($(this).val() == avaliacao.avSolucao) {
							$(this).prop("selected", true);
						}
			});
			$('#retorno option').each(function () {
						if ($(this).val() == avaliacao.avFeedback) {
							$(this).prop("selected", true);
						}
			});
			
			if(avaliacao.Observacao ==null){
				$("#obs").val("Nenhuma observação foi realizada.");
			}
			else{
				$("#obs").val(avaliacao.Observacao);		
			}				
		});
		$("#tempoproblema").prop("disabled", true);
		$("#solucaoproblema").prop("disabled", true);
		$("#retorno").prop("disabled", true);
		$("#obs").prop("disabled", true);
	}
	
	/* Form para trocar a senha */
	/* submissão do form */
	$("#altpwd").click(function () {
		var pwdnova = $("#pwdnova").val();
		$.ajax({
			type: "POST",
			url: "../Controller/_usuario.php",
			data: { idUsuario: usuario.id, senha: pwdnova, op: "alterarSenha" },
			success: function (data) {
				document.location.href = "../View/inicio.php";
			},
			error: function (error) {
				alert("Houve um erro no envio!");
			}
		});
	});
	
	$("#altpwdVoltar").click(function(){
		document.location.href ="../View/inicio.php"; 
	});		
		
	/* tooltips	*/
	$('[rel="tooltip"]').tooltip(); 
	
	/*Esconde o assunto no chamado e exibe caso seja selecionado opção "outros"*/
	$('.assunto').hide();
});

/* Menu */
/**
 * jquery.dlmenu.js v1.0.1
 * http://www.codrops.com
 *
 * Licensed under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 * 
 * Copyright 2013, Codrops
 * http://www.codrops.com
 */
; (function ($, window, undefined) {

	'use strict';

	// global
	var Modernizr = window.Modernizr, $body = $('body');

	$.DLMenu = function (options, element) {
		this.$el = $(element);
		this._init(options);
	};

	// the options
	$.DLMenu.defaults = {
		// classes for the animation effects
		animationClasses: { classin: 'dl-animate-in-1', classout: 'dl-animate-out-1' },
		// callback: click a link that has a sub menu
		// el is the link element (li); name is the level name
		onLevelClick: function (el, name) { return false; },
		// callback: click a link that does not have a sub menu
		// el is the link element (li); ev is the event obj
		onLinkClick: function (el, ev) { return false; }
	};

	$.DLMenu.prototype = {
		_init: function (options) {

			// options
			this.options = $.extend(true, {}, $.DLMenu.defaults, options);
			// cache some elements and initialize some variables
			this._config();

			var animEndEventNames = {
				'WebkitAnimation': 'webkitAnimationEnd',
				'OAnimation': 'oAnimationEnd',
				'msAnimation': 'MSAnimationEnd',
				'animation': 'animationend'
			},
				transEndEventNames = {
					'WebkitTransition': 'webkitTransitionEnd',
					'MozTransition': 'transitionend',
					'OTransition': 'oTransitionEnd',
					'msTransition': 'MSTransitionEnd',
					'transition': 'transitionend'
				};
			// animation end event name
			this.animEndEventName = animEndEventNames[Modernizr.prefixed('animation')] + '.dlmenu';
			// transition end event name
			this.transEndEventName = transEndEventNames[Modernizr.prefixed('transition')] + '.dlmenu',
			// support for css animations and css transitions
			this.supportAnimations = Modernizr.cssanimations,
			this.supportTransitions = Modernizr.csstransitions;

			this._initEvents();

		},
		_config: function () {
			this.open = false;
			this.$trigger = this.$el.children('.dl-trigger');
			this.$menu = this.$el.children('ul.dl-menu');
			this.$menuitems = this.$menu.find('li:not(.dl-back)');
			this.$el.find('ul.dl-submenu').prepend('<li class="dl-back"><a href="#"> &nbsp; Voltar</a></li>');
			this.$back = this.$menu.find('li.dl-back');
		},
		_initEvents: function () {

			var self = this;

			this.$trigger.on('click.dlmenu', function () {

				if (self.open) {
					self._closeMenu();
				}
				else {
					self._openMenu();
				}
				return false;

			});

			this.$menuitems.on('click.dlmenu', function (event) {

				event.stopPropagation();

				var $item = $(this),
					$submenu = $item.children('ul.dl-submenu');

				if ($submenu.length > 0) {

					var $flyin = $submenu.clone().css('opacity', 0).insertAfter(self.$menu),
						onAnimationEndFn = function () {
							self.$menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classout).addClass('dl-subview');
							$item.addClass('dl-subviewopen').parents('.dl-subviewopen:first').removeClass('dl-subviewopen').addClass('dl-subview');
							$flyin.remove();
						};

					setTimeout(function () {
						$flyin.addClass(self.options.animationClasses.classin);
						self.$menu.addClass(self.options.animationClasses.classout);
						if (self.supportAnimations) {
							self.$menu.on(self.animEndEventName, onAnimationEndFn);
						}
						else {
							onAnimationEndFn.call();
						}

						self.options.onLevelClick($item, $item.children('a:first').text());
					});

					return false;

				}
				else {
					self.options.onLinkClick($item, event);
				}

			});

			this.$back.on('click.dlmenu', function (event) {

				var $this = $(this),
					$submenu = $this.parents('ul.dl-submenu:first'),
					$item = $submenu.parent(),

					$flyin = $submenu.clone().insertAfter(self.$menu);

				var onAnimationEndFn = function () {
					self.$menu.off(self.animEndEventName).removeClass(self.options.animationClasses.classin);
					$flyin.remove();
				};

				setTimeout(function () {
					$flyin.addClass(self.options.animationClasses.classout);
					self.$menu.addClass(self.options.animationClasses.classin);
					if (self.supportAnimations) {
						self.$menu.on(self.animEndEventName, onAnimationEndFn);
					}
					else {
						onAnimationEndFn.call();
					}

					$item.removeClass('dl-subviewopen');

					var $subview = $this.parents('.dl-subview:first');
					if ($subview.is('li')) {
						$subview.addClass('dl-subviewopen');
					}
					$subview.removeClass('dl-subview');
				});

				return false;

			});

		},
		closeMenu: function () {
			if (this.open) {
				this._closeMenu();
			}
		},
		_closeMenu: function () {
			var self = this,
				onTransitionEndFn = function () {
					self.$menu.off(self.transEndEventName);
					self._resetMenu();
				};

			this.$menu.removeClass('dl-menuopen');
			this.$menu.addClass('dl-menu-toggle');
			this.$trigger.removeClass('dl-active');

			if (this.supportTransitions) {
				this.$menu.on(this.transEndEventName, onTransitionEndFn);
			}
			else {
				onTransitionEndFn.call();
			}

			this.open = false;
		},
		openMenu: function () {
			if (!this.open) {
				this._openMenu();
			}
		},
		_openMenu: function () {
			var self = this;
			// clicking somewhere else makes the menu close
			$body.off('click').on('click.dlmenu', function () {
				self._closeMenu();
			});
			this.$menu.addClass('dl-menuopen dl-menu-toggle').on(this.transEndEventName, function () {
				$(this).removeClass('dl-menu-toggle');
			});
			this.$trigger.addClass('dl-active');
			this.open = true;
		},
		// resets the menu to its original state (first level of options)
		_resetMenu: function () {
			this.$menu.removeClass('dl-subview');
			this.$menuitems.removeClass('dl-subview dl-subviewopen');
		}
	};

	var logError = function (message) {
		if (window.console) {
			window.console.error(message);
		}
	};

	$.fn.dlmenu = function (options) {
		if (typeof options === 'string') {
			var args = Array.prototype.slice.call(arguments, 1);
			this.each(function () {
				var instance = $.data(this, 'dlmenu');
				if (!instance) {
					logError("cannot call methods on dlmenu prior to initialization; " +
						"attempted to call method '" + options + "'");
					return;
				}
				if (!$.isFunction(instance[options]) || options.charAt(0) === "_") {
					logError("no such method '" + options + "' for dlmenu instance");
					return;
				}
				instance[options].apply(instance, args);
			});
		}
		else {
			this.each(function () {
				var instance = $.data(this, 'dlmenu');
				if (instance) {
					instance._init();
				}
				else {
					instance = $.data(this, 'dlmenu', new $.DLMenu(options, this));
				}
			});
		}
		return this;
	};
})(jQuery, window);