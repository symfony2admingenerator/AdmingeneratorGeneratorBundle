function showFieldsetsForTab(name) {
    $('.admin_form fieldset.fieldset_tabbable').css('display','none');
    $('.form_tabs li').removeClass('active');

    var toDisplay = $('.form_tabs li[data-name="'+name+'"] a').data('display').split(' ');

    $(toDisplay).each(function(){
        $('.admin_form .form_fieldset_'+this).css('display','block');
    });
    
    $('.form_tabs li[data-name="'+name+'"]').addClass('active');
}