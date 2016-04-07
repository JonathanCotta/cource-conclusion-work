(function ($) {
	$.fn.validar = function (op) {
		var pattern
		if (this.val() == "" || this.val() == null) {
			if(op == "senha"){
				return true
			}
			else{
				this.addClass("invalido");
				return false;
			}			
		}
		else {
			switch (op) {
				case "letra":
					pattern = new RegExp(/[0-9]/);
					if (pattern.test(this.val())) {
						this.removeClass("invalido");
						this.tooltip({ title: "Apenas letras", delay: { show: 50, hide: 3000 }});
						this.tooltip("show");
						return false;
					}
					else {
						this.removeClass("invalido");						
						this.tooltip('destroy');
						return true;
					}
					break;
				case "numero":
					pattern = new RegExp(/[^0-9]/);
					if (pattern.test(this.val())) {
						this.removeClass("invalido");
						this.tooltip({ title: "apenas números", delay: { show: 50, hide: 3000 }});
						this.tooltip("show");
						return false;

					}
					else {
						this.removeClass("invalido");						
						this.tooltip('destroy');
						return true;
					}
					break;
				case "senha":
					pattern = new RegExp(/[^0-9]/);
					if (pattern.test(this.val())) {
						this.removeClass("invalido");
						this.tooltip({ title: "apenas numeros", delay: { show: 50, hide: 3000 }});
						this.tooltip("show");
						return false;

					}
					else {
						this.removeClass("invalido");						
						this.tooltip('destroy');
						return true;
					}
					break;
				case "email":
					pattern = new RegExp(/@+/);
					if (!pattern.test(this.val())) {
						this.removeClass("invalido");
						this.tooltip({ title: "formato inválido", delay: { show: 50, hide: 3000 }});
						this.tooltip("show");
						return false;
					}
					else {
						this.removeClass("invalido");						
						this.tooltip('destroy');
						return true;
					}
					break;
				default:
					this.removeClass("invalido");						
					this.tooltip('destroy');
					return true;
					break;
			}
		}
	}
})(jQuery);