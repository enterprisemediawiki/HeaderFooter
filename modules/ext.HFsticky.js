/*globals $:false */
$(window).scroll(function() {
	var page = $("#content");
	var body = $("#bodyContent");
	var header = $("#hfStickyHeader");
	var hWidth = null;
	if ( hWidth == null ) {
		// The width magically changes.
		hWidth = header.width();
	}
	var scroll = $(window).scrollTop();
	if (page.offset().top < scroll) {
		page.addClass("fixed");
		body.css("top", header.height());
		header.width(hWidth);
	} else {
		page.removeClass("fixed");
		body.css("top", 0);
	}
});
