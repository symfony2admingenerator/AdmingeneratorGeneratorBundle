$(document).ready(function(){
    // Enable tooltips
    $('a[rel="tooltip"]').tooltip();
    
    // Enable confirm on object actions
    $("td.actions a[data-confirm]").click(function(e) {
        e.preventDefault();        
    	if(confirm($(this).data('confirm'))) { window.location.href = $(this).attr('href'); }
    });
});
