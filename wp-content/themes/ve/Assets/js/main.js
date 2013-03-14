
var sizeStored = 'large';
var today = new Date();
var expireDate = new Date();
expireDate.setDate(today.getDate()+10);
var isClicking = false;
var KEYCODE_ESC = 27;
function setSizeCookie(size) {
	sizeStored = size;
	document.cookie = 'size='+sizeStored+'; expires='+expireDate+'; path=/';
};

function closeSearch() {
	$('#header-nav form.search div').fadeOut('fast', function() {
	});
}

function buildSearchWidget() {
	$('#header-nav form label').wrap('<a href="#header-search"></a>');
	$('#header-nav form.search a').click( function(e) {
		$('#header-nav form.search div').slideDown('fast', function() {
			$('#header-search').focus();
		});
		e.preventDefault();
	});
	$('#header-nav .search *').click( function () {
		$('#header-search').focus();
	});
	$('html').click(closeSearch);
	$('#header-nav .search').click(function(event){
		event.stopPropagation();
	});
	$('html').keyup( function(e) {
		if (e.keyCode == KEYCODE_ESC) {
			closeSearch();
		}
	});
	
};

function ibroHeader() {
	buildSearchWidget();
	
	$('#text-size-control .size1').click( function() {
		$('#page').addClass('size-normal');
		$('#page').removeClass('size-large size-larger');
		setSizeCookie('normal');
	});
	$('#text-size-control .size2').click( function() {
		$('#page').addClass('size-large');
		$('#page').removeClass('size-normal size-larger');
		setSizeCookie('large');
	});
	$('#text-size-control .size3').click( function() {
		$('#page').addClass('size-larger');
		$('#page').removeClass('size-large size-normal');
		setSizeCookie('larger');
	});
	$('#text-size-control a').attr('href', '#');
}

$(document).ready(function() { 
    
    
    //
    $("body").toggleClass("no-js js"); 
    buildSlider( 609, 341 ); 
    ibroHeader(); 
    
    
    
    
    
});



function buildSlider( width, height) {
	$('.carousel-inner').append('<div id="caption-area"></div><div id="slide-nav2" class="no-print"><a href="#" role="button" id="prev">Next</a><a href="#" role="button" id="next">Prev</a></div><div id="slide-nav3"><a href="#" role="button" class="pause">Pause/Play</a></div>');
	$('.carousel-inner').addClass('building');
	$('.carousel-inner ul')
	.before('<div id="slide-nav" class="no-print">')
	.cycle({
		fx: "fade",
		pager: "#slide-nav",
		next: '#next', 
		prev: '#prev',
		width: 341,
		timeout: 6000,
		before: function() {
			$('#caption-area').slideUp(300, function() {
			});
		},
		after: function () {
			$('#caption-area').html($(this).children('p').clone());
			$('#caption-area').slideDown(300, function() {
			});
		}
	});
	$('.carousel-inner').removeClass('building');
	//fixing Chrome glitch: height occasionally being set to 19px
	$('.carousel-inner .slides').css('width', width+'px');
	$('.carousel-inner .slides').css('height', height+'px');
	
	$('#slide-nav3 a').click( function(e) {
	//depends on hover events defined below
		if ($(this).hasClass('pause')) {
			//$('#.carousel-inner ul').cycle('pause');
			$('#slide-nav3 a').toggleClass('pause play');
		}
		else {
			//$('#.carousel-inner ul').cycle('resume');
			$('#slide-nav3 a').toggleClass('pause play');
		}
		e.preventDefault();
	});
	/* 'pause: 1' option on cycle would interfere with following mouse events */
	$('.carousel').mouseenter( function() {
		$('#.carousel-inner ul').cycle('pause');
	});
	$('.carousel').mouseleave( function() {
		if ($('#slide-nav3 a').hasClass('pause')) {
			$('#.carousel-inner ul').cycle('resume');
		}
	});
}

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