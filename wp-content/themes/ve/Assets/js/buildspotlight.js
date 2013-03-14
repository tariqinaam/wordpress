jQuery.fn.buildSpotlight = function() {
	this.append('<div class="caption"></div>');
	this.children('.slides')
	.after('<ul id="spotlight-thumbs" class="no-print">')
	.cycle({
		fx: "fade",
		pager: "#spotlight-thumbs",
		timeout: 0,
		speed: 400,
		width: 573,
		height: 371,
		pagerAnchorBuilder: function(idx, slide) { 
        //return '<li><a href="#"><img src="' + slide.getAttribute('data-thumb') + '" /><p>' + slide.getAttribute('data-short-title') + '</p></a></li>'; /* alternate source for thumbnail */
        return '<li><a href="#"><img src="' + slide.getElementsByTagName('img')[0].src + '" /><p>' + slide.getAttribute('data-short-title') + '</p></a></li>';
		},
		before: function() {
			$('#spotlightMenu .caption').html($(this).children('h4').clone());
			$('#spotlightMenu .caption').append($(this).children('p').clone());
		}
	});
	
};