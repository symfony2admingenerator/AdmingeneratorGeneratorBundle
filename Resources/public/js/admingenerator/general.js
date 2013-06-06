$(document).ready(function(){
    // Enable tooltips
    $('td.actions *[rel="tooltip"]').tooltip();
    
    // Convert object action buttons into POST requests
    // TODO: manage JS for edit templates
    $("td.actions a, .form-actions .btn-toolbar a").click(function(e) {
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
        
        // Submit POST request, if required prompt for confirmation
        if($(this).data('confirm') && !confirm($(this).data('confirm'))) {
            //form.submit();
        	e.preventDefault();
        }
    });
});
