	$(document).ready(function() {

		var dog = $("#pluto").attr("src");
		var rex = /^.*\/(?=[^\/]*$)/;
		var direct = dog.match(rex);
		$("#pluto").mouseover(function(){
			$("#pluto").attr("src", direct+"plutoserves.png");
		}).mouseout(function(){
			$("#pluto").attr("src", dog);
		});
		
		var woj = 260;
		var wog;
		var cols;
		$("#goods").mouseover(mults);
		
		function mults() {
			cols = $("#first td").length;
			wog = $("#footer").width() * 0.9;
			if (((wog / woj) < 3) && (cols == 3)) reducetable();
			if (((wog / woj) >= 3) && (cols != 3)) increasetable();
		}
		
		function reducetable() {
			$("#goods").append("<tr id='lastrow'></tr>");
			$("#first td").eq(2).clone(true).appendTo("#lastrow");
			$("#second td").eq(2).clone(true).appendTo("#lastrow");
			$("#first td").eq(2).remove();
			$("#second td").eq(2).remove();
		}

		function increasetable() {
			$("#lastrow td").eq(0).clone(true).appendTo("#first");
			$("#lastrow td").eq(1).clone(true).appendTo("#second");
			$("#lastrow td").eq(0).remove();
			$("#lastrow td").eq(1).remove();
			$("#lastrow").remove();
		}
		
	})
	
	function erasing() {
		if ($("#invitation").attr("value") == "Напиши, что найти...") $("#invitation").attr("value", "");
	}
