var search = {
    submitSearch: function(){
        var form = $('#searchSubmitButton').closest('form');
        $globalSearch = $('#globalSearch').val().replace('\\', '');
        $url = form.attr("action") + this.convertToSlug($globalSearch) + '?q=' + $globalSearch;
        window.location.href = $url;
    },

    convertToSlug: function convertToSlug(Text)
    {
        return Text
            .toLowerCase()
            .replace(/ /g,'-')
            .replace(/[^\w-]+/g,'')
            ;
    }
}

var register = {
    submitRegisterForm: function(){
        var form = $('.fos_user_registration_register');
        $.post( form.attr("action"), form.serialize()).done(function(data){
            $('[data-selector="register-company"] .modal-content').html(data);
        });
    },
    submitLoginForm: function(){
        var form = $('.fos_user_login');
        $.post( form.attr("action"), form.serialize()).done(function(data){
            $('[data-selector="login"] .modal-content').html(data);
        });
    },
    resettingForm: function(){
        var form = $('.fos_user_resetting_request');
        $.post( form.attr("action"), form.serialize()).done(function(data){
            $('[data-selector="resetting"] .modal-content').html(data);
        });
    },
    resetForm: function(){
        var form = $('.fos_user_resetting_reset');
        $.post( form.attr("action"), form.serialize()).done(function(data){
            $('[data-selector="reset"] .modal-content').html(data);
        });
    }
}

var contact = {
    contactWriterForm: function(){
        var form = $('.contact_writer_form');
        $.post( form.attr("action"), form.serialize()).done(function(data){
            var newData = $(data).find('.modal-content').html();
            if(newData) {
                $('[data-selector="contact-writer"] .modal-content').html($(data).find('.modal-content').html());
            } else {
                $('[data-selector="contact-writer"] .modal-content').html(data);
            }
        });
    }
}

$(document).ready(function(){
    $('#searchForm').submit(function(e){
        e.preventDefault();
        search.submitSearch();
    });

    $('#searchSubmitButton').click(function(){
        search.submitSearch()
    });

    $(document).on('click', '#submitRegisterForm', function(e){
        e.preventDefault();
        register.submitRegisterForm();
    });

    $(document).on('click', '#submitLoginForm', function(e){
        e.preventDefault();
        register.submitLoginForm();
    });

    $(document).on('click', '#submitResettingForm', function(e){
        e.preventDefault();
        register.resettingForm();
    });

    $(document).on('click', '#submitResetForm', function(e){
        e.preventDefault();
        register.resetForm();
    });

    $(document).on('click', '#submitContactWriter', function(e){
        e.preventDefault();
        contact.contactWriterForm();
    });

    $('[data-action="register-company"]').click(function(){
        var subscriptionTitle = $(this).data('title');
        var subscriptionId = $(this).data('id');
        $('#selected_subscription_title').html(subscriptionTitle);
        console.info($('input[name="fos_user_registration_form[subscription]"]').filter('[value="'+ subscriptionId +'"]').prop("checked", true));
    });

    $('.login-username-show').click(function(){
        $(this).hide();
        $('.login-username-hidden').show();
    });

});