$(document).ready(function(){
    // Enable tooltips
    $('td.actions *[rel="tooltip"]').tooltip();
    
    // Convert object action buttons into POST requests
    $("td.actions a, .form-actions .btn-toolbar a").on('click', function(e) {
    	// On CSRF protected actions only, we force POST
    	if ($(this).data('csrf-token')) {
    		e.preventDefault();
    		// If required, prompt for confirmation
            if($(this).data('confirm') && !confirm($(this).data('confirm'))) {
            	return;
            }
            // Transform in POST request
            var form = $('<form />').attr({
                method: 'POST',
                action: $(this).attr('href'),
                style:  'visibility: hidden'
            }).appendTo($('body'));
            // Add csrf protection token
            $('<input />').attr({
                type:   'hidden',
                name:   '_csrf_token',
                value:  $(this).data('csrf-token')
            }).appendTo(form);
            // Send action
            form.submit();
    	}
    });
});
