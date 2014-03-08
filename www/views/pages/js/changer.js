$(document).ready(function() {
	var imgnum = $("#toynails a").length;
	var wogap = $("#gap").width();
	var visnum = (wogap - (wogap % 70)) / 70;
	
	$("#arrowright").click(shiftleft);
	$("#arrowleft").click(shiftright);

	function shiftleft() {
		if (imgnum > visnum) {
			$("#toynails a:first").clone(true).appendTo("#toynails");
			$("#toynails a:first").remove();
			$("#toynails img:last").css("margin", "0");
		}
	}

	function shiftright() {
		if (imgnum > visnum) {
			$("#toynails a:last").clone(true).prependTo("#toynails");
			$("#toynails a:last").remove();
			$("#toynails img:first").css("margin", "0");
		}
	}
})