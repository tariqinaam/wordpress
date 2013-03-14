/* Interactive elements of the IBRO header
1. Drop down search box
2. Text resize buttons
File created Tuesday April 10, 2012
*/

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
