$(document).ready(function(){
	// Twitter Bootstrap hack for menus on touch device
	$('body').on('touchstart.dropdown', '.dropdown-menu', function (e) { e.stopPropagation(); });
	
	$('a[rel=tooltip]').tooltip({
            container: 'body'
        });

    // Moved all scripts to dedicated widgets
    // Do not remove, this file is used when developing new features
});
