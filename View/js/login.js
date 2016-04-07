/* global $ */
$(document).ready(function () {
	$("#entrar").click(function () {
		var login = $("#login").validar("letra") ;
		var pwd = $("#password").validar("numero") ;
		if (login && pwd)
		{
			
			$.post("Controller/_usuario.php", { login: $("#login").val(), senha: $("#password").val(), op: "logar" })
				.done(function (data) {
					var obj = $.parseJSON(data);
					if (obj.pass) {
						if (obj.pwdreset) {
							document.location.href = "view/alterarsenha.php";
						}
						else {
							document.location.href = "view/inicio.php";
						}
					}
					else {
						alert("login ou senha incorretos");
					}
				})
				.fail(function () {
					alert("houve um erro na aplicação!");
				});
		}
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
});