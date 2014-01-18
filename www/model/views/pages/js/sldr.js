	$(document).ready(function() {
		var woi = 70;
		var quantity = $("#pic img").length;
		var wow;
		var k = 0;
		var count;
		var ni = 0;
		var woh = 0;
		var id = setInterval(slider, 3000);
		
		function slider() {
			if (woh != $("#overheader").width()) {
				var last = $("#pic a").length - 1;
				for (var i = last; i > (last - ni); i--) $("#pic a").eq(i).remove();
				woh = $("#overheader").width();
				wow = woh - 20;
				wow = wow - (wow % woi);
				ni = wow / woi;
				if (quantity <= ni) {
					wide();
				}
				else {
					narrow();
					show();
				}
			}
			if (k == 1) show();
		}
	
		function wide() {
			wow = quantity * woi;
			wow = wow - 2;
			$("#wndw").width(wow);
			$("#pic").width(wow + woi);
			k = 0;
		}
		
		function narrow() {
			wow = wow - 2;
			$("#wndw").width(wow);
			var wop = woi * (quantity + ni + 1);
			$("#pic").width(wop);
			for (var i = 0; i < ni; i++) $("#pic a").eq(i).clone(true).addClass("wider").appendTo("#pic");
			count = quantity;
			k = 1;
		}
		
		function shifttostart() {
			$("#pic").css("left", "0");
			shift = parseInt($("#pic").css("left"));
			return shift;
		}
		
		function show() {
			var elem = $("#pic");
			var shift = parseInt(elem.css("left")) - woi;
			if (count < quantity) count++;
			else {
				count = 1;
				shift = shifttostart() - woi;
			}
			elem.stop().animate({left: shift}, 200);
		}
	
	})
