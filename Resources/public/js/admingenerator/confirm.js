(function(){
	$("td.actions a").click(function(e) {
        e.preventDefault();

        if ($(this).data('confirm')) {
            if(!confirm($(this).data('confirm'))) 
              return false;
        }

        window.location.href = $(this).attr('href');
    });
})();
    