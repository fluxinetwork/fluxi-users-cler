/*

  Fluxi users JS - 11.05.16
 -------------------------
  User Login
  User Registration
  Update user infos
  Update user password
  Reset user password
  Launch validation & ajax resquest
  Popin login
  Generate a password string
  Generate a password

 */


$( document ).ready(function() {
    // Vars
    popinIsOpen = false;

    // Login
    initLoginForm();
    initPopinLogin();

    // Registration
    if($('.page-template-user-registration').length){
        initRegistrationForm();
        initPasswordGenBtn();
    }

    // Update
    if($('.page-template-user-profil-update').length){
        initUpdateUserForm();
        initUpdateUserPasswordForm();
        initPasswordGenBtn();
    }
    if($('.page-template-user-password-reset').length){
        initResetUserPasswordForm();
    }

});


$(window).load(function(){
    // Label's animation on init page
    /*$( '.js-input-effect' ).each(function( i ) {
        if($(this).val() != ''){
            $(this).addClass('has-content');
        }else{
            $(this).removeClass('has-content');
        }
    });
    // Label's animation on focus
    $('.js-input-effect').focusout(function(){
        if($(this).val() != ''){
            $(this).addClass('has-content');
        }else{
            $(this).removeClass('has-content');
        }
    });
    // Select label
    $( '.form__select select' ).each(function( i ) {
        if($(this).val() != null)
            $(this).parent().addClass('has-content');
    });
    $('.form__select select').change(function(){
        if($(this).val() != '')
            $(this).parent().addClass('has-content');
    });*/

});

/*------------------------------*\

    #FORMS

\*------------------------------*/

/*
 * User Login
 */
function initLoginForm(){
    //console.log('ok');
    $('#form-login').on('submit', function(e){
        //console.log('ok on submit');
        e.preventDefault();
        var params = $(this).serialize();
        var $form = $('#form-login');
        var btnLabel = '<i class="fa fa-sign-in"></i>Connexion';

        $('.js-submit-login').prop('disabled', true).html('<i class="fa fa-cog fa-spin js-spinner mgRight--xs"></i>En cours');

        $.ajax({
            type: 'POST',
            dataType: 'JSON',
            url: ajax_object.ajax_url,
            data: params+'&action=fluxi_login_user',
            success: function(data){
                $('.js-submit-login').html(btnLabel);

                if(data[0].validation == 'error'){
                    $('.js-submit-login').prop('disabled', false);
                }else{
                    $('.js-submit-login').hide();
                }
                $('#form-login .js-notify').html('<span class="'+data[0].validation+'">'+data[0].message+'</span>');
                // redirect after login
                setTimeout(function() {
                    $(location).attr( 'href', data[0].redirect );
                }, 500);
            },
            error : function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
                $('.js-submit-login').prop('disabled', false).html(btnLabel);
            }

        });
        return false;
    });
}

/*
 * User Registration
 */
function initRegistrationForm(){
    var formID = '#form-registration';
    $(formID+' button[type=submit]').prop('disabled', false);
    $formObj = $('#form-registration');

    // Structure fields
    jQuery('#if_adherent1').prop('checked', true);
    jQuery('.js-has-structure').addClass('is-none');
    // Display structure's select or text field
    jQuery('input[type=radio][name=if_adherent]').change(function() {
        if (this.value == 'oui') {
            jQuery('.js-has-structure').removeClass('is-none');
            jQuery('.js-no-structure').addClass('is-none');
        }
        else {
            jQuery('.js-has-structure').addClass('is-none');
            jQuery('.js-no-structure').removeClass('is-none');
        }
    });

    fluxiAjaxTry( formID, $formObj, 'fluxi_create_user', false, false );
}

/*
 * Update user infos
 */
function initUpdateUserForm(){
    var formID = '#form-update-profil';
    $(formID+' button[type=submit]').prop('disabled', false);
    $formObj = $('#form-update-profil');

    // Structure fields
    let ifAdherent = jQuery('input[type=radio][name=if_adherent]:checked').val();
    // Display on init
    if(ifAdherent == 'oui'){
        jQuery('.js-has-structure').removeClass('is-none');
        jQuery('.js-no-structure').addClass('is-none');
    }else{
        jQuery('.js-has-structure').addClass('is-none');
        jQuery('.js-no-structure').removeClass('is-none');
    }
    // Display on change structure's select or text field
    jQuery('input[type=radio][name=if_adherent]').change(function() {
        if (this.value == 'oui') {
            jQuery('.js-has-structure').removeClass('is-none');
            jQuery('.js-no-structure').addClass('is-none');
        }
        else{
            jQuery('.js-has-structure').addClass('is-none');
            jQuery('.js-no-structure').removeClass('is-none');
        }
    });

    fluxiAjaxTry( formID, $formObj, 'fluxi_update_user', false, true );
}

/*
 * Update user password
 */
function initUpdateUserPasswordForm(){
    var formID = '#form-update-password';
    $(formID+' button[type=submit]').prop('disabled', false);
    $formObj = $('#form-update-password');
    fluxiAjaxTry( formID, $formObj, 'fluxi_password_user', false, true );
}

/*
 * Reset user password
 */
function initResetUserPasswordForm(){
    var formID = '#form-password-reset';
    $(formID+' button[type=submit]').prop('disabled', false);
    $formObj = $('#form-password-reset');
    fluxiAjaxTry( formID, $formObj, 'fluxi_password_reset_user', true, false );
}

/*
 * Launch validation & ajax resquest
 *
 * @param formID : form ID
 * @param $formObj : jQuery object
 * @param ajaxAction
 * return nothing, display results
 *
 */
function fluxiAjaxTry (formID, $formObj, ajaxAction, redirect, button ) {

    /*modules : 'security',
        onModulesLoaded : function() {
            var optionalConfig = {
              fontSize: '12pt',
              padding: '4px',
              bad : 'Trop faible',
              weak : 'Faible',
              good : 'Bien',
              strong : 'Fort'
            };

            $('input[name="password"]').displayPasswordStrength(optionalConfig);
        },*/
    var labelBtn = '';

    $.validate({
        form : formID,
        scrollToTopOnError : true,
        lang : 'fr',
        validateOnBlur : true,
        onError : function($form) {

            $form.find('button[type=submit]').prop('disabled', false).html(labelBtn);
            
        },
        onSuccess : function($form) {           

            var params = $formObj.serialize();

            $.ajax({
                type: 'POST',
                dataType: 'JSON',
                url: ajax_object.ajax_url,
                data: 'action='+ajaxAction+'&'+params,
                success: function(data){
                    $formObj.find('button[type=submit]').html(labelBtn);

                    if(data[0].validation == 'error'){
                        $formObj.find('button[type=submit]').prop('disabled', false);
                    }else{

                        if (redirect == true) {
                            setTimeout(function() {
                                $(location).attr( 'href', data[0].redirect );
                            }, 500);
                        }

                        if (button == true) {
                            $formObj.find('.c-form__submit').html('<a class="c-btn" href="'+data[0].redirect+'"><i class="fa fa-arrow-left mgRight--xs" aria-hidden="true"></i>Retour</a>');
                        }

                        //$formObj.find('button[type=submit]').hide();
                    }
                    $formObj.find('.js-notify').html('<span class="'+data[0].validation+'">'+data[0].message+'</span>');

                },
                error : function(jqXHR, textStatus, errorThrown) {
                    //console.log(jqXHR + ' :: ' + textStatus + ' :: ' + errorThrown);
                    $formObj.find('button[type=submit]').prop('disabled', false).html(labelBtn);
                }

            });
            return false;
        },
        onValidate : function($form) {
            labelBtn = $formObj.find('button[type=submit]').html();
            $formObj.find('.js-notify').html('');
            $formObj.find('button[type=submit]').prop('disabled', true).html('<i class="fa fa-cog fa-spin js-spinner mgRight--xs" aria-hidden="true"></i>En cours');            
        }
    });
}

/*
 * Popin login
 */
function initPopinLogin(){

    $('.js-popin-show').click(function(e){
        e.preventDefault();
        if (isLogged) {
            notify("Vous êtes déjà connecté");
        } else {

            popinName = $(this).attr('href');
            if( popinName == 'connexion' ){
                $('#popin').addClass('is-active');
            }
            /*else if(popinName == 'recuperation-password'){}*/
            popinIsOpen = true;
        }
    });


    $('.js-popin-close').click(function(e){
        e.preventDefault();
        $('#popin').removeClass('is-active');
        /*setTimeout(function() {
            $('#popin').removeClass('effect--out');
        }, 300);*/
        popinIsOpen = false;
    });


}

/*
 * Generate a password string
 */
function randString(id){
    var dataSet = $(id).attr('data-character-set').split(',');
    var possible = '';
    if($.inArray('a-z', dataSet) >= 0){
        possible += 'abcdefghijklmnopqrstuvwxyz';
    }
    if($.inArray('A-Z', dataSet) >= 0){
        possible += 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    }
    if($.inArray('0-9', dataSet) >= 0){
        possible += '0123456789';
    }
    if($.inArray('#', dataSet) >= 0){
        possible += '![]{}()%&*$#^<>~@|';
    }
    var text = '';
    for(var i=0; i < $(id).attr('data-size'); i++) {
        text += possible.charAt(Math.floor(Math.random() * possible.length));
    }
    return text;
}


/*
 * Generate a password
 */
function initPasswordGenBtn(){
    $(".js-generate-password").click(function(){
        var field = $(this).closest('div').find('input[rel="gp"]');
        field.val(randString(field));
        field.focus();
    });
}