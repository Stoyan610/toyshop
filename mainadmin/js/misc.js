function galery() {
  var sitepict = $("#hide0").text();
  var a_id = $("#hide1").text();
  var a_txt = $("#hide2").text();
  var arr_id = new Array();
  var arr_txt = new Array();
  arr_id = a_id.split("~");
  arr_txt = a_txt.split("~");
  var n = arr_txt.length;
  var new_td = "<td id='picture'>Щёлкни по выбранному изображению<br />";
  var str;
  for (var i = 0; i < n; i++) {
    str = "&nbsp;<img id='" + arr_id[i] +"' src='" + sitepict + "mult114x86/" + arr_txt[i] + ".jpg' alt='" + arr_txt[i] + "' width='114' height='86' onclick='getimage(" + arr_id[i] +")' />&nbsp;";
    new_td += str;
  }
  new_td += "</td>";
  $("#pictures").replaceWith(new_td);
}
		
function getimage(i) {
  var point = "#" + i;
  var Str = $(point).attr("src");
  var smp = /[^\/]+(?=\.jpg)/;
  var Str0 = Str.match(smp);
  var one_td = "<td><img src='" + Str + "' alt='" + Str0 + "' width='114' height='86' /></td>";
  $("#picture").replaceWith(one_td);
  $("#imageid").val(i);
}

function gettoyimg(i) {
  var point = "#" + i;
  var Str = $(point).attr("src");
  var smp = /[^\/]+(?=\.jpg)/;
  var Str0 = Str.match(smp);
  var one_td = "<img src='" + Str + "' alt='" + Str0 + "' width='70' height='70' />";
  $("#toys").replaceWith(one_td);
  $("#imageid").val(i);
}

function checkjpg() {
  var vv = $("#file").val();
  var tpl = /\.jpe?g$/;
  var x = vv.match(tpl);
  if (x == null) {
    alert('Файл должен быть только с расширением jpg или jpeg');
    $("#file").val(null);
  }
}

function valid() {
  var ind = true;
  var text = '';
  var tpl = /^[-\w]*\w+[-\w]*$/;
  var ff = $("#file").val();
  if (ff == '') text += 'Не выбран файл-изображение\n'; 
  var bb = $("#filename").val();
  if (bb != '') {
    var x = bb.match(tpl);
    if (x == null) {
      ind = false;
      text += 'Какое-то странное указано имя файла изображения\n';
    }
  }
  if (text != '') alert(text);
}

function checknum(id) {
  var str = $("#"+id).val();
  var reg = /\d+/g;
  var result = str.match(reg);
  var num = parseFloat(result[0] + "." + result[1]);
  $("#"+id).val(num);
}

function checkall() {
  var r = mustbe();
  var smp_eml = /^([a-z0-9][\w\.-]*[a-z0-9])@((?:[a-z0-9]+[\.-]?)*[a-z0-9]\.[a-z]{2,})$/;
  var smp_phn = /^\+\d{1,3}\(\d{2,5}\)\d{1,3}(\-\d{2}){2}$/;
  var mail = $("#mail").val();
  if (!smp_eml.test(mail)) {
    alert("Е-мейл некорректный");
    $("#mail").val('');
    r = false;
  }
  var phone = $("#phone").val();
  phone = phone.replace(/\D/g, "");
  var lng = phone.length;
  if ((lng > 11) || (lng < 7)) {
    alert("Телефон некорректный");
    $("#phone").val('');
    r = false;
  }
  else {
    var arr_phone = phone.split("");
    switch(lng) {
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
    $("#phone").val(phone);
    var stroka = phone + " - Телефон понят правильно ?";
    if (!confirm(stroka))  r = false;
  }
  return r;
}

function toylist2(num) {
  var sitepict = $("#hide0").text();
  var str_toys = $("#hide1").text();
  var one_toy = new Array();
  var arr_toys = new Array();
  one_toy = str_toys.split("^");
  var n = one_toy.length;
  for (var i =0; i < n; i++) {
    arr_toys[i] = one_toy[i].split("~");
  }
  var new_td = "<tr id='chosen'><td colspan='4'>";
  document.cookie = "old_td=" + str_toys;
  document.cookie = "sitepict=" + sitepict;
  var str;
  for (var i = 0; i < n; i++) {
    new_td += "<div id='" + i + "' style='display: inline-block; width: 130px; text-align: center; vertical-align: top;'><span id='0-" + i + "' hidden>" + arr_toys[i][0] + "</span><span id='4-" + i + "' hidden>" + arr_toys[i][4] + "</span>";
    str = "<img src='" + sitepict + arr_toys[i][5] + ".jpg' alt='" + arr_toys[i][5] + "' width='70' height='70' /><br /><span id='1-" + i + "'>" + arr_toys[i][1] + "</span><br /><span id='2-" + i + "'>" + arr_toys[i][2] + "</span> руб.<br /><input type='text' name='" + i + "' value=''  size='3' /> шт<br />максимум <span id='3-" + i + "'>" + arr_toys[i][3] + "</span>";
    new_td += str;
    new_td += "</div>";
  }
  new_td += "<br /><button onclick='gettoys2(" + n + ", " + num + ")'>Выбрано</button>" + "</td></tr>";
  $("#products").replaceWith(new_td);
  $("#edit").hide();
}

function gettoys2(n, num) {
  var j = 0;
  var a;
  var choice = new Array();
  var chosen_toy = new Array();
  var deadline = $("#DTime").val();
  for (var i = 0; i < n; i++) {
    a = $("#" + i + " input").val();
    if (a != "") {
      chosen_toy[j] = [$("#0-" + i).text(), $("#1-" + i).text(), $("#2-" + i).text(), $("#3-" + i).text(), $("#4-" + i).text()];
      var k = Number(a);
      var mx = Number(chosen_toy[j][3]);
      if (k > mx) {
        a = chosen_toy[j][3];
      }
      chosen_toy[j].splice(3, 1, a);
      var z = Number(chosen_toy[j][4]);
      var x = Math.max(deadline, z);
      deadline = x;
      j++;
    }
  }
  if (j == 0)    {
    alert("Ни одной игрушки не добавлено");
    var r = document.cookie.match("(^|;) ?sitepict=([^;]*)(;|$)");
		if (r) var sitepict = r[2];
    var r = document.cookie.match("(^|;) ?old_td=([^;]*)(;|$)");
		if (r) var str_toys = r[2];
    var old_td = "<tr id='products'><td colspan='4' ondblclick='toylist2(" + num + ")'><span style='text-decoration: underline;' >Для добавления игрушек дважды кликни здесь</span><div id='hide0' hidden>" + sitepict + "</div><div id='hide1' hidden>" + str_toys + "</div></td></tr>";
    $("#chosen").replaceWith(old_td);
  }
  else {
    $("#chosen").replaceWith("");
    $("#edit").show();
    $("#DTime").val(deadline);
    for (var i = 0; i < j; i++) {
      var inum = i + num;
      $("#toy" + inum).show();
      $("#first" + inum).val(chosen_toy[i][0]);
      $("#second" + inum).val(chosen_toy[i][1]);
      $("#third" + inum).val(chosen_toy[i][2]);
      $("#fourth" + inum).val(chosen_toy[i][3]);
      var numj = num + j;
      $("#count").val(numj);
    }
  }
}

function toylist() {
  var sitepict = $("#hide0").text();
  var str_toys = $("#hide1").text();
  var one_toy = new Array();
  var arr_toys = new Array();
  one_toy = str_toys.split("^");
  var n = one_toy.length;
  for (var i =0; i < n; i++) {
    arr_toys[i] = one_toy[i].split("~");
  }
  var new_td = "<td id='chosen' colspan='3'>";
  document.cookie = "old_td=" + str_toys;
  document.cookie = "sitepict=" + sitepict;
  var str;
  for (var i = 0; i < n; i++) {
    new_td += "<div id='" + i + "' style='display: inline-block; width: 130px; text-align: center; vertical-align: top;'><span id='0-" + i + "' hidden>" + arr_toys[i][0] + "</span><span id='4-" + i + "' hidden>" + arr_toys[i][4] + "</span>";
    str = "<img src='" + sitepict + arr_toys[i][5] + ".jpg' alt='" + arr_toys[i][5] + "' width='70' height='70' /><br /><span id='1-" + i + "'>" + arr_toys[i][1] + "</span><br /><span id='2-" + i + "'>" + arr_toys[i][2] + "</span> руб.<br /><input type='text' name='" + i + "' value=''  size='3' /> шт<br />максимум <span id='3-" + i + "'>" + arr_toys[i][3] + "</span>";
    new_td += str;
    new_td += "</div>";
  }
  new_td += "<br /><button onclick='gettoys1(" + n + ")'>Выбрано</button>" + "</td>";
  $("#products").replaceWith(new_td);
  $("#edit").hide();
}

function gettoys1(n) {
  var j = 0;
  var a;
  var choice = new Array();
  var chosen_toy = new Array();
  var deadline = $("#DTime").val();
  for (var i = 0; i < n; i++) {
    a = $("#" + i + " input").val();
    if (a != "") {
      chosen_toy[j] = [$("#0-" + i).text(), $("#1-" + i).text(), $("#2-" + i).text(), $("#3-" + i).text(), $("#4-" + i).text()];
      var k = Number(a);
      var m = Number(chosen_toy[j][3]);
      if (k > m) {
        a = chosen_toy[j][3];
      }
      chosen_toy[j].splice(3, 1, a);
      var z = Number(chosen_toy[j][4]);
      var x = Math.max(deadline, z);
      deadline = x;
      j++;
    }
  }
  if (j == 0)    {
    alert("Ни одной игрушки не добавлено");
    var r = document.cookie.match("(^|;) ?sitepict=([^;]*)(;|$)");
		if (r) var sitepict = r[2];
    var r = document.cookie.match("(^|;) ?old_td=([^;]*)(;|$)");
		if (r) var str_toys = r[2];
    var old_td = "<tr id='products'><td colspan='4' ondblclick='toylist()'><span style='text-decoration: underline;' >Для добавления игрушек дважды кликни здесь</span><div id='hide0' hidden>" + sitepict + "</div><div id='hide1' hidden>" + str_toys + "</div></td></tr>";
    $("#chosen").replaceWith(old_td);
  }
  else {
    $("#chosen").replaceWith("<td colspan='3'></td>");
    $("#edit").show();
    $("#DTime").val(deadline);
    for (var i = 0; i < j; i++) {
      $("#toy" + i).show();
      $("#first" + i).val(chosen_toy[i][0]);
      $("#second" + i).val(chosen_toy[i][1]);
      $("#third" + i).val(chosen_toy[i][2]);
      $("#fourth" + i).val(chosen_toy[i][3]);
      $("#count").val(j);
    }
  }
}

function mustbe() {
  var ann;
  ann = $("#req1").val();
  if (ann == '') {
    alert("Поля, отмеченные * , обязательны к заполнению !");
    return false;
  }
  ann = $("#req_text").val();
  if (ann == '') {
    alert("Текстовое поле, отмеченное * , тоже обязательно к заполнению !");
    return false;
  }
  return true;
}

function mustbeimg() {
  var ann;
  ann = $("#alt").val();
  if (ann == '') {
    alert("Поля, отмеченные * , обязательны к заполнению !");
    return false;
  }
  ann = $("#big").val();
  if (ann == '') {
    alert("Все поля, отмеченные * , обязательны к заполнению !");
    return false;
  }
  return true;
}