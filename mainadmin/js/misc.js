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
		
		function getimage(i){
			var point = "#" + i;
			var Str = $(point).attr("src");
			var smp = /[^\/]+(?=\.jpg)/;
			var Str0 = Str.match(smp);
			var one_td = "<td><img src='" + Str + "' alt='" + Str0 + "' width='114' height='86' /><input type='hidden' name='Image_ID' value='" + i +"' /></td>";
			$("#picture").replaceWith(one_td);
		}