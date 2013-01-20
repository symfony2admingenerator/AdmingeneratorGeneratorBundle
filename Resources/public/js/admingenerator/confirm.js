
(function(){
    $("td.actions a").click(function(event) {
        event.preventDefault();

    	if (!confirm($(this).data('confirm'))) {	
              return false;
        }
        else {
			window.location.href = $(this).attr('href');
        }
    });
})();
