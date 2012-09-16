$(document).ready(function(){
    $("#tree-table").treeTable();
    $("#tree-table tr").draggable({
      helper: "clone",
      opacity: .75,
      refreshPositions: true, // Performance?
      revert: "invalid",
      revertDuration: 300,
      scroll: true
    });
    
    $("#tree-table .expander").each(function() {
      $("#tree-table tr").droppable({
        accept: "*",
        drop: function(e, ui) {
            
            var to = this;
            var from = $($(ui.draggable));
            var url = admingeneratorNestedUrl.replace('%7Csource%7C', (from.attr('id')).replace('node-',''));
            url = url.replace('%7Cto%7C', (to.id).replace('node-',''));
            
            $('.admin_list').addClass('loading');
            $('.nested_loading_box').css('display', 'block');
            
            $.ajax({
                url: url,
                success: function() {
                    from.appendBranchTo(to);
                    $('.admin_list').removeClass('loading');
                    $('.nested_loading_box').css('display', 'none');
                },
                error: function() {
                    alert('Error mouving element');
                    $('.admin_list').removeClass('loading');
                    $('.nested_loading_box').css('display', 'none');
                }
            })
          
        },
        hoverClass: "accept",
        over: function(e, ui) {
          if(this.id != $(ui.draggable).id && !$(this).is(".expanded")) {
            $(this).expand();
          }
        }
      });
    });
    
    // Make visible that a row is clicked
    $("#tree-table tr").mousedown(function() {
      $("tr.selected").removeClass("selected"); // Deselect currently selected rows
      $(this).addClass("selected");
    });
    /*
    // Make sure row is selected when span is clicked
    $("#tree-table tbody tr span").mousedown(function() {
      $($(this).parents("tr")[0]).trigger("mousedown");
    });*/
});