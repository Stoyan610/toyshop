function deltoy(toy) {
	var line = $(toy);
	line.hide(500, function() {
		line.remove();
		recalc();
	});
}

function recalc() {
	var maxn = $("#dtls input").size();
	var price;
	var quant;
	var t;
	var amount = 0;
	for (var i = 0; i < maxn; i++) {
		quant = $("#dtls input").eq(i).val();
		t = $("#dtls td").eq(5*i+4).text();
		price = parseFloat(t);
		amount = amount + quant*price;
	}
	$("#dtls td").eq(maxn*5+2).text(amount + " руб.")
}

function confirmer() {
	var flag = true;
//Проверка имени клиента
	var client = $(".ord input[name='name']").val();
	if (client.length < 3) {
		$(".ord input[name='name']").val("Слишком короткое имя !");
		$(".ord input[name='name']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
	if (client.length > 128) {
		$(".ord input[name='name']").val("Слишком длинное имя !");
		$(".ord input[name='name']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
	var regexp1 = /[^\w\s]+/;
	if (regexp1.test(client)) {
		$(".ord input[name='name']").val("В имени присутствуют посторонние сиволы !");
		$(".ord input[name='name']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
//Проверка телефона
	var phone = $(".ord input[name='phone']").val();
	var regexp2 = /[^-+()\d\s]+/;
	if ((regexp2.test(phone)) || (phone.length < 7) || (phone.length > 20)) {
		$(".ord input[name='phone']").val("Проверьте номер телефона !");
		$(".ord input[name='phone']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
//Проверка и-мейла
	var email = $(".ord input[name='email']").val();
	var regexp3 = /^([a-z0-9][\w\.-]*[a-z0-9])@((?:[a-z0-9]+[\.-]?)*[a-z0-9]\.[a-z]{2,})$/i;
	if ((!regexp3.test(email)) || (email.length == 0)) {
		$(".ord input[name='email']").val("Проверьте адрес электронной почты !");
		$(".ord input[name='email']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
//Проверка адреса доставки
	var addr = $(".ord input[name='addr']").val();
	if (addr.length == 0) {
		$(".ord input[name='addr']").val("Не указан адрес доставки !");
		$(".ord input[name='addr']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}

	if (flag) $("form[name='ordering']").submit();

}