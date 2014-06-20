function deltoy(toy) {
	var line = $(toy);
	line.hide(500, function() {
		line.remove();
    var n = Number(toy.substr(1));
    $.post("handle_1.php", {toyid: n});
 		recalc();
	});
}

function delall() {
	var maxn = $("#dtls input").size();
  var toyid;
	for (var i = 0; i < maxn; i++) {
    toyid = Number($("#dtls input").eq(i).attr('name'));
    deltoy("#" + toyid);
 	}
}

function recalc() {
  var price;
	var quant;
	var t;
  var toyid;
  var toyname;
  var items = 0;
	var amount = 0;
	var maxn = $("#dtls input").size();
  for (var i = 0; i < maxn; i++) {
		quant = Number($("#dtls input").eq(i).val());
    toyid = Number($("#dtls input").eq(i).attr('name'));
    t = $("#dtls td").eq(5*i+4).text();
		price = parseFloat(t);
		amount += quant*price;
    items += quant;
    toyname = $("#dtls td.td1 a").eq(i).text();
    $.post("handle_2.php", {num: i, toyid: toyid, toyname: toyname, price: price, toyitems: quant});
  }
	$("#dtls td").eq(maxn*5+2).text(amount + " руб.");
  
  var suf;  
  var ind = items % 10;
  if (items == 0) items="нет";
  if ((items >= 10) && (items <= 20)) suf="ек";
  else {
    switch(ind) {
      case 1: {
        suf="ка";
        break;
      }
      case 2: {
        suf="ки";
        break;
      }
      case 3: {
        suf="ки";
        break;
      }
      case 4: {
        suf="ки";
        break;
      }
      default: {
        suf="ек";
      }
    }
  }
  $("#inmenu span").html(" В корзине <b>" + items + "</b> игруш" + suf);
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
	var regexp1 = /[^\w\sа-яА-Я]+/;
	if (regexp1.test(client)) {
    $(".ord input[name='name']").val("В имени присутствуют посторонние символы !");
		$(".ord input[name='name']").css("backgroundcolor", "#FFA1A1");
		flag = false;
	}
//Проверка телефона
	var phone = $(".ord input[name='phone']").val();
  phone = phone.replace(/\D/g, "");
  if ((phone.length > 11) || (phone.length < 7)) {
    $(".ord input[name='phone']").val("Проверьте номер телефона !");
		$(".ord input[name='phone']").css("backgroundcolor", "#FFA1A1");
		flag = false;
  }
  else {
    var arr_phone = phone.split("");
    switch(phone.length) {
      case 7: {
          phone = "+7(495)" + arr_phone[0] + arr_phone[1] + arr_phone[2] + "-" + arr_phone[3] + arr_phone[4] + "-" + arr_phone[5] + arr_phone[6];
          break;
      }
      case 8: {
          phone = "+7(49" + arr_phone[0] + ")" + arr_phone[1] + arr_phone[2] + arr_phone[3] + "-" + arr_phone[4] + arr_phone[5] + "-" + arr_phone[6] + arr_phone[7];
          break;
      }
      case 9: {
         phone = "+7(9" + arr_phone[0] + arr_phone[1] + ")" + arr_phone[2] + arr_phone[3] + arr_phone[4] + "-" + arr_phone[5] + arr_phone[6] + "-" + arr_phone[7] + arr_phone[8];
          break;
      }
      case 10: {
          phone = "+7(" + arr_phone[0] + arr_phone[1] + arr_phone[2] + ")" + arr_phone[3] + arr_phone[4] + arr_phone[5] + "-" + arr_phone[6] + arr_phone[7] + "-" + arr_phone[8] + arr_phone[9];
          break;
      }
      case 11: {
          phone = "+7(" + arr_phone[1] + arr_phone[2] + arr_phone[3] + ")" + arr_phone[4] + arr_phone[5] + arr_phone[6] + "-" + arr_phone[7] + arr_phone[8] + "-" + arr_phone[9] + arr_phone[10];
          break;
      }
    }
    
    if (phone != $(".ord input[name='phone']").val()) {
      $(".ord input[name='phone']").val(phone);
      var stroka = phone + " - Телефон понят правильно ?";
      if (!confirm(stroka))  flag = false;
    }
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