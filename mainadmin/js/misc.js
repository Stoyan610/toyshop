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
  var bb = $("#big").val();
  if (bb != '') {
    var x = bb.match(tpl);
    if (x == null) {
      ind = false;
      text += 'Какое-то странное указано имя файла большого изображения\n';
    }
  }
  var ss = $("#small").val();
  if (ss == '')     $("#small").val(bb);
  else {
    var x = ss.match(tpl);
    if (x == null) text += 'Какое-то странное указано имя файла маленького изображения\n';
  }
  var nn = $("#nail").val();
  if (nn == '')     $("#nail").val(bb);
  else {
    var x = nn.match(tpl);
    if (x == null) text += 'Какое-то странное указано имя файла изображения-ноготка\n';
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