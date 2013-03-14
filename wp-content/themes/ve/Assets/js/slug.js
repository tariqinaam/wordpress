
/*
 *
 * This plugin will create a slug and check database for existing slugs
 *
 */

(function ($) {

	$.fn.slugify = function (source,options) {
            
                //grab this element
		var $target = this;
                
                //make the string an element
		var $source = $(source);

		var settings = $.extend({
			slugFunc: (function (val, originalFunc) { return originalFunc(val); })
		}, options);


		var convertToSlug = function(val) {
			return settings.slugFunc(val, 
				(function(v) {
					if (!v) return '';
					var from = "ƒ±ƒ∞√∂√ñ√º√ú√ß√áƒüƒû≈ü≈û√¢√Ç√™√ä√Æ√é√¥√î√ª√õƒòƒô√ì√≥ƒÑƒÖ≈ö≈õ≈Å≈Ç≈ª≈º≈π≈∫ƒÜƒá≈É≈Ñ";
					var to   = "iIoOuUcCgGsSaAeEiIoOuUEeOoAaSsLlZzZzCcNn";

					for (var i=0, l=from.length ; i<l ; i++) {
					    v = v.replace(from.charAt(i), to.charAt(i));
					}

					return v.replace(/'/g, '').replace(/\s*&\s*/g, ' and ').replace(/[^A-Za-z0-9]+/g, '-').replace(/^-|-$/g, '').toLowerCase();
				})  
			);
		};

		var setLock = function () {
			if($target.val() != null && $target.val() != '') {
				$target.addClass('slugify-locked');
			} else {
				$target.removeClass('slugify-locked');
			}
		};

		var updateSlug = function () {
			var slug = convertToSlug($(this).val());
			$target.filter(':not(.slugify-locked)').val(slug).text(slug);		
		};


		$source.keyup( updateSlug ).change( updateSlug ); 

		$target.change(function () {       
			var slug = convertToSlug($(this).val());
                        
			$target.val(slug).text(slug);
			setLock();
		});   

		setLock();         

		return this; 
	};
    
})(jQuery);   