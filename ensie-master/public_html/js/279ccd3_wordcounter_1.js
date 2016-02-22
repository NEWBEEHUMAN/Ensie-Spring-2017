/**
 * jQuery.textareaCounter
 * Version 1.0
 * Copyright (c) 2011 c.bavota - http://bavotasan.com
 * Dual licensed under MIT and GPL.
 * Date: 10/20/2011
**/
(function($){
	$.fn.textareaCounter = function(options) {
		// setting the defaults
		// $("textarea").textareaCounter({ limit: 100 });
		var defaults = {
			limit: 100
		};	
		var options = $.extend(defaults, options);
 
		// and the plugin begins
		return this.each(function() {
			var obj, text, wordcount, limited;
			
			obj = $(this);
			obj.after('<div><span class="wordcount" data-selector="counter-text">Maximaal '+options.limit+' woorden</span></div>');

			obj.bind('keyup paste', function() {
			    text = obj.val();//.replace(/\n/g, "");
			    if(text === "") {
			    	wordcount = 0;
			    } else {
                    wordcount = $.trim(text).split(/[\s\.\?]+/).length;
				}
			    if(wordcount >= options.limit) {
			        $(this).parent().find("[data-selector='counter-text']").html('<em class="error">0 woorden over</em>');
					limited = $.trim(text).split(" ", options.limit);
					limited = limited.join(" ");
					$(this).val(limited);
			    } else {
			        $(this).parent().find("[data-selector='counter-text']").html((options.limit - wordcount)+' woorden over');
			    } 
			});
		});
	};

    $.fn.charCounter = function(options) {
        // setting the defaults
        // $("textarea").textareaCounter({ limit: 100 });
        var defaults = {
            limit: 100
        };
        var options = $.extend(defaults, options);

        // and the plugin begins
        return this.each(function() {
            var obj, text, charCount, limited;

            obj = $(this);
            obj.after('<div><span class="wordcount" data-selector="counter-text">Maximaal '+options.limit+' tekens</span></div>');

            obj.bind('keyup paste', function() {
                text = obj.val();//.replace(/\n/g, "");
                if(text === "") {
                    charCount = 0;
                } else {
                    charCount = text.length;
                }
                if(charCount >= options.limit) {
                    $(this).parent().find("[data-selector='counter-text']").html('<em class="error">0 tekens over</em>');
                    limited = text.substring(0, options.limit);
                    $(this).val(limited);
                } else {
                    $(this).parent().find("[data-selector='counter-text']").html((options.limit - charCount)+' tekens over');
                }
            });
        });
    };
})(jQuery);

//$(document).ready(function() {
//  $("textarea").keypress(function(event) {
//    if(event.which == '13') {
//		event.preventDefault();
//		$(this).val($(this).val() + ' \n');
//    }
//  });
//});