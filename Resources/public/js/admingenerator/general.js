$(document).ready(function(){
    // Enable tooltips
    $('td.actions button[rel="tooltip"]').tooltip();
    
    // Convert object action buttons into POST requests
    $("td.actions button, .form-actions .btn-toolbar button").click(function(e) {
        e.preventDefault();
        
        // Create hidden form
        var form = $('<form />').attr({
            method: 'POST',
            action: $(this).data('action'),
            style:  'visibility: hidden'
        }).appendTo($('body'));
        
        if($(this).data('csrf-token')) {
            // Add csrf protection token
            $('<input />').attr({
                type:   'hidden',
                name:   '_csrf_token',
                value:  $(this).data('csrf-token')
            }).appendTo(form);
        }
        
        // Submit POST request, if required promt for confirmation
        if(!$(this).data('confirm') || confirm($(this).data('confirm'))) {
            form.submit();
        }
    });
});
