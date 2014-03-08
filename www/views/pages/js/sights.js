function change(id) {
	var sight = $(id);
	var smallpic = sight.attr("src");
	var bigpic = $("#big").attr("src");
	var rexp = /[^\/]*$/;
	smallpic = smallpic.match(rexp); 
	bigpic = bigpic.match(rexp); 
		if (smallpic != bigpic) {
			var newsrc = "views/pages/pictures/toy400x400/" + smallpic;
			$("#big").attr("src", newsrc);
      $("#big").removeAttr("width");
			$("#big").removeAttr("height");
      $("#small img").addClass("dim");
			sight.removeClass("dim");
		}
}