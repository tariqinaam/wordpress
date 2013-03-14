$(document).ready(function() {
	
    $('body').toggleClass("no-js js");
	ibroHeader();
        
    if(twt){
        var url = 'http://search.twitter.com/search.json?callback=twtcb&q=westminstersu&rpp=5';
        var script = document.createElement('script');
        $("#twitter_feed").html('Loading...');
        script.setAttribute('src', url);
        document.body.appendChild(script);
    }
    
  
    
    $('a[rel="external"]').click(function(){
	window.open(this.href);
	return false;
    });
    
	//add wmode to iframes
    $('iframe[src*="?"]').each(function(index) {
		var newSrc= $(this).attr('src') + "&wmode=opaque";
		$(this).attr('src',newSrc);
	});
	$('iframe').not('[src*="?"]').each(function(index) {
		var newSrc= $(this).attr('src') + "?wmode=opaque";
		$(this).attr('src',newSrc);
	});
	
	//carousel
	$('.carousel ul').prepend('<li>&nbsp;</li>'); 
	$('.carousel ul').before('<a class="prev" href="#na">Previous</a><a class="next" href="#na">Next</a>')		
	$('.carousel ul').jcarousel({
		scroll: 1,
        start: 2,
		initCallback: mycarousel_initCallback,
        buttonNextHTML: null,
        buttonPrevHTML: null
	});
	
	//a-z
        $('.az.results').hide();
        
	
        $('.a-to-z li a').each(function(){
            var showMe = $(this).attr('id');
            if($(this).attr('id')=='a'){
                $(this).addClass('current');
                $('#child-'+showMe).show();
            }
            
        });
        
        
        $('.a-to-z li a').click(function() {

            var showMe = $(this).attr('id');
            $('.az.results').hide();
            $('#child-'+showMe).show();
            $('.a-to-z .current').removeClass('current');
            $(this).addClass('current');

        });
	
	
	$(".comment, .contact-accom, .agent-contact a").fancybox({
		maxWidth	: 735,
		maxHeight	: 450,
		fitToView	: false,
		width		: '100%',
		height		: '90%',
		autoSize	: false,
		closeClick	: false,
		openEffect	: 'none',
		closeEffect	: 'none'
	});
	
	$('#comment-form, .contact-details').hide();
	
	
	/* odd class */
	$('.js-oddify li:even').addClass('odd');
	
	/* Gallery */
	$('.thumb-list li').delegate('img','click', function(){
	    $('.active-image img').attr('src',$(this).attr('src').replace('thumb','large'));
	}); 
	
	/* Post to marketplace form */
	$('.jshidden').hide();
	$('#categorys, #wanted-type').on('change', function(event) {
			if (this.id === "categorys"){
				$('.jshidden').hide();
			}else{
				$('#wanted .jshidden').hide();
			};
			var showMe ="#" + $(this).attr('value');
			$(showMe).fadeTo(0,0).slideDown(200,function(){
                            
                            $(this).fadeTo(900,1);
                        });
	        return false;
		});
	
});//end document ready

function twtcb(twitters) {
    
  var tweets = twitters.results;
  var statusHTML = [];
  for (var i=0; i<tweets.length; i++)
  {
    var username = tweets[i].from_user_name;
    var profile  = tweets[i].profile_image_url;
    
    var img = '<img alit="'+username+' profile" src="'+profile+'"/>';
    
    var status = tweets[i].text.replace(/((https?|s?ftp|ssh)\:\/\/[^"\s\<\>]*[^.,;'">\:\s\<\>\)\]\!])/g, 
	function(url) {return '<a href="'+url+'">'+url+'</a>';
    }).replace(/\B@([_a-z0-9]+)/ig, function(reply) {
      return  reply.charAt(0)+'<a href="http://twitter.com/'+reply.substring(1)+'">'+reply.substring(1)+'</a>';
    });
    statusHTML.push(
         '<article class="twitter_date">'
        + '<header><h3>'+username+'</h3></header>'
        
        +'<p>'+status+'</p>'
        + img
        + '<footer><p>' + prettyDate(tweets[i].created_at) + '</p></footer>'
        +'</article>');
  }
  document.getElementById('twitter_feed').innerHTML = statusHTML.join('');
}


function prettyDate(time){
    var date = new Date((time || "").replace(/-/g,"/").replace(/[TZ]/g," ")),
            diff = (((new Date()).getTime() - date.getTime()) / 1000),
            day_diff = Math.floor(diff / 86400);

    if ( isNaN(day_diff) || day_diff < 0 || day_diff >= 31 )
            return;

    return day_diff == 0 && (
                    diff < 60 && "just now" ||
                    diff < 120 && "1 minute ago" ||
                    diff < 3600 && Math.floor( diff / 60 ) + " minutes ago" ||
                    diff < 7200 && "1 hour ago" ||
                    diff < 86400 && Math.floor( diff / 3600 ) + " hours ago") ||
            day_diff == 1 && "Yesterday" ||
            day_diff < 7 && day_diff + " days ago" ||
            day_diff < 31 && Math.ceil( day_diff / 7 ) + " weeks ago";
}



function mycarousel_initCallback(carousel) {
    $('.carousel').on('click', '.next', function(event) { carousel.next();
        return false;
    });

    $('.carousel').on('click', '.prev', function(event) { carousel.prev();
        return false;
    });
};

