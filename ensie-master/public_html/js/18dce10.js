$(function() {
  	
    // Show modal
    $('[data-action]').click(function(event) {
        event.preventDefault();

        var selector = $(this).attr('data-action');

        $('.modal:visible').fadeOut('fast', function() {
            $('[data-selector="' + selector + '"]').fadeIn('fast');
        });

        $('.overlay:hidden').fadeIn('fast', function() {
            $(this).find('[data-selector="' + selector + '"]').fadeIn('fast');
        });
    });


    // Hide modal when clicking outside elemn
	$('.overlay').mouseup(function(e)
    {
        var subject = $('.modal');

        if(e.target.className != subject.attr('class') && !subject.has(e.target).length)
        {
            subject.fadeOut('fast');
            $('.overlay').fadeOut('fast');
        }
    });

    // Hide modal when clicking header
    $('.modal header').click(function(event) {
        event.preventDefault();
        $(this).parent('.modal').fadeOut('fast').parent('.overlay').fadeOut('fast');
    });

    // Hide modal when clicking cancel button in modal
    $('[data-action="close-modal"]').click(function(event) {
        event.preventDefault();
        $(this).parent('li').parent('ul').parent('div').parent('.modal').fadeOut('fast').parent('.overlay').fadeOut('fast');
    });


    // Adding bgcolor to header on scroll
    $(window).on('scroll', function () {
	    if ($(this).scrollTop() > 456) {
	        $('.pageheader').addClass('not-transparent');
	    }
	    else {
	        $('.pageheader').removeClass('not-transparent');
	    }
	});

    // Adding shadow to header on scroll
	$(window).on('scroll', function () {
	    if ($(this).scrollTop() > 0) {
	        $('.sub').addClass('shadow');
	    }
	    else {
	        $('.sub').removeClass('shadow');
	    }
	});


	// Autocomplete
    $.widget("custom.catcomplete", $.ui.autocomplete, {
        _create: function () {
            this._super();
            this.widget().menu("option", "items", "> :not(.ui-autocomplete-category)");
        },
        _renderMenu: function (ul, items) {
            var that = this,
                currentCategory = "";
            $.each(items, function (index, item) {
                var li;
                if (item.category != currentCategory) {
                    ul.append("<li class='ui-autocomplete-category'>" + item.category + "</li>");
                    currentCategory = item.category;
                }
                li = that._renderItemData(ul, item);
                if (item.category) {
                    li.attr("aria-label", item.category + " : " + item.label);
                }
            });
        }
    });

    $.ui.autocomplete.prototype._renderItem = function (ul, item) {
        var term = this.term.split(' ').join('|');
        var re = new RegExp("(" + term + ")", "gi");
        var t = item.label.replace(re, "<strong>$1</strong>");
        return $("<li></li>")
            .data("item.autocomplete", item)
            .append(t)
            .appendTo(ul);
    };

    var searchWidth = $('#globalSearch').outerWidth();
    $('#autocomplete').outerWidth(searchWidth);

     $('#globalSearch').catcomplete({
        source: $('#auto-complete-url').val(),
        appendTo: '#autocomplete',
        minLength: 2,
        position: { my: 'center top', at: 'center top', of: '#autocomplete' },
        delay: 500,
        select: function (event, ui) {
            window.location.href = ui.item.url;
            return false;
        }
    });

    // Sharebox switch
    $('[data-show="share-box"]').click(function (event) {
        event.preventDefault();

        $('.share-box').toggleClass('show');
    });

    // Language switch
    $('.switch-language').click(function (event) {
        event.preventDefault();

        $('.language-box').toggleClass('show');
    });


    // Hide popout when clicked outside element
    $(document).mouseup(function (e)
    {
        var container = $('.popout');

        if (!container.is(e.target) // if the target of the click isn't the container...
            && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            if (container.is(':visible'))
            container.removeClass('show');
        }
    });

    // Notfications
    $('.notification .close').click(function (event) {
        event.preventDefault();
        $(this).parent('.notification').fadeOut('fast');
    });

    setTimeout(function() {
          $('.notification:visible').fadeOut('fast');
    }, 3000);

    // Dropdown user
    $('[data-show="dropdown-user"]').click(function (event) {
        event.preventDefault();
        $('.dropdown-user').toggleClass('active');
    });

    // Dropdown user
    $('[data-show="dropdown-search"]').click(function (event) {
        event.preventDefault();
        $('.search').toggleClass('active');
    });

    // Select toggle
    $('[data-action="show-select"]').click(function(event) {
        event.preventDefault();
        $(this).toggleClass('active').parent().find('[data-selector="dropdown-select"]').toggleClass('active');
    });

    $('.selectbox input:radio').change(function(event) {
        if ($(this).parent().parent().find('.new input:radio').is(':checked')) {
            $(this).parent().find('.textbox-wrapper').addClass('active');
        }
        else if ($(this).parent().parent().find('.new input:radio').is(':not(:checked)')) {
            $(this).parent().parent().find('.textbox-wrapper').removeClass('active');
        }
    });

    // Slider
    /* TODO: set height of .blocks.has-slider to height of .slider + (tallest of .wrapper) within .slider-content */

    // Textbox tooltip checker
    $('input:text').each(function() {
        if ($(this).next().is('.tooltip')) {
            $(this).addClass('has-tooltip');
        }
    });

    $('.tooltip').bind('touchstart touchend', function(e) {
        e.preventDefault();
        $(this).toggleClass('hover_effect');
    });

    // Resultpage border hack
    var resultHeight = $('.resultpage').find('.wrapper').outerHeight();
    $('.resultpage').find('.wrapper').height(resultHeight);

    // Profilepage minimal (equal)height
    var profileHeight = $('.profilepage').find('aside.profile').outerHeight();
    $('.profilepage').find('.work-list').css('min-height', profileHeight + 'px');

    // Resultdetail minimal (equal)height
    var detailHeight = $('.resultdetail').find('aside.profile').outerHeight();
    $('.resultdetail').find('.content').css('min-height', detailHeight + 'px');

    // Profilepage aside bio height hack
    if($('.profilepage aside.profile').css('float') == "none"){
        var asideHeight = $('.profilepage .profilewrapper').outerHeight();
        $('.profilepage .work-list').css('margin-top',asideHeight + 40 + 'px');
    }

    // Tooltip textarea hack
    $('.admin .tooltip').each(function() {
        if($(this).prev().is('.textarea-wrapper')) {
            var offset = $(this).prev('.textarea-wrapper').outerHeight();
            $(this).css('bottom',offset - 30 + 'px');
        }
    });

    // Delete Ensie
    $('[data-action="delete-item"]').click(function(event) {
        event.preventDefault();
        $('.overlay:hidden').fadeIn('fast', function() {
            $('[data-action="delete-item"]').fadeIn('fast');
        });
    });

    // Set stars based on rating in data-rating
    var ratingWidth = $('.rating').find('.stars').data('rating') * 20;
    $('.rating').find('.stars span').css('width', ratingWidth + '%');

    $('.rating :radio').bind('change click',
        function(){
            var rating = $(this).val();
            var ratingWidth = rating * 20;
            $(this).parent('.stars').find('span').css('width', ratingWidth + '%');

            if(rating < 3) {
                $('.modal:visible').fadeOut('fast', function() {
                    $('[data-selector="low-rating"]').fadeIn('fast');
                });

                $('.overlay:hidden').fadeIn('fast', function() {
                    $(this).find('[data-selector="low-rating"]').fadeIn('fast');
                });
            }
        }
    )

    $('.rating :radio').hover(
      function(){
        var ratingWidth = $(this).val() * 20;
        $(this).parent('.stars').find('span').css('width', ratingWidth + '%');
      } 
    )

    // Button spinner
    $('form').submit(function() {
        var width = $(this).find('input:submit').outerWidth();
        $(this).find('.spinner').css('width',width + 'px').show();
    });

    // Add active classes to submenu
    $(window).load(function() {
        var currentPage = url('2');
        $('.subnav a[href$="'+ currentPage +'"]').addClass('active');
    });
    
});