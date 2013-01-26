/*!
 * Nestedset drag & drop for Symfony2Admingenerator
 * ================================================
 * 
 * Created by loostro, 2013
 * Released under MIT license.
 * 
 * Uses: 
 *  > jquery-ui draggable
 *  > jquery-ui droppable
 *  > twitter bootstrap modal
 *  > jquery treeTable (modified version)
 *  
 *  Requires setting (in template) variables:
 *  admingeneratorNestedUrl  >>  url used for ajax call
 *  nestedsetStringExpand    >>  collapsed branch title translation
 *  nestedsetStringCollapse  >>  expanded branch title translation
 */   
$(document).ready(function(){
    nestedsetTreeTable(); 
    nestedsetDragAndDrop();
});

// Init treeTable

function nestedsetTreeTable() {  
    $('#tree-table').treeTable({
        stringExpand: nestedsetStringExpand,
        stringCollapse: nestedsetStringCollapse,
        doubleclickMode: true,
        initialRootState: "expanded",
        initialIndent: -19,
        onNodeInit: function(node, expandable, isRootNode) {
            (isRootNode) ? node.addClass('ui-helper-hidden') : '';
            var cell = $(node).children().first();
            var expander = $('<i />').addClass('icon-expander');
            cell.prepend(expander);
        },
        onNodeReinit: function(node, expandable, isRootNode) {
            $(node).find('.icon-expander').remove();
        }
    });
}

// Init drag and drop

function nestedsetDragAndDrop() {
    $("#tree-table tbody tr").draggable({
        helper: function(e) {
            var row = $(e.target).closest('tr');
            var branch = row.selectBranch();
            return $('<div />').addClass('nested-helper').css({
              'width': branch.closest('table').width()+'px',
              'height': row.outerHeight()
            }).append($('<table />').css('width', '100%').append(branch.clone()));
        },
        drag: function(e, ui) {
            var $draggable = $(this);
            var $droppable = $draggable.data('current-droppable');
            if($droppable && isAccepted($droppable, $draggable, ui)) { highlightAction(ui, $droppable); }
        },
        refreshPositions: true, // Performance?
        revert: "invalid",
        revertDuration: 300,
        scroll: true
    });

    $("#tree-table tbody tr").droppable({
        accept: "#tree-table tbody tr",
        over: function(e, ui) {
            var $droppable = $(this);
            var $draggable = ui.draggable;
            if(isAccepted($droppable, $draggable, ui)) {
                highlightAction(ui, $droppable);
                $draggable.data('current-droppable', $droppable);
            }
        },
        out: function(e, ui) {
            var $droppable = $(this);
            var $draggable = ui.draggable;
            if(isAccepted($droppable, $draggable, ui)) { unhighlightAction(ui, $droppable); }
        },
        drop: function(e, ui) {
            var $droppable = $(this);
            var $draggable = ui.draggable;
          
            if(isAccepted($droppable, $draggable, ui)) {
                unhighlightAction(ui, $droppable);
                var action = dropActionName(ui, $droppable); 
                
                var url = admingeneratorNestedUrl;
                url = url.replace('|dragged|', ($draggable[0].id).replace('node-',''));
                url = url.replace('|dropped|', ($droppable[0].id).replace('node-',''));
                url = url.replace('|action|', action);

                $('#nestedset_loading').modal('show');

                $.ajax({
                    url: url,
                    success: function() {
                        // reorganize tree
                        var $parent = $draggable.nodeParent();
                        $draggable.moveBranch(action, $droppable);
                        if($parent) { $parent.nodeReinitialize(); }
                        if(action == 'in') { $droppable.nodeReinitialize(); }
                        // hide modal, display success alert
                        var alert = $('#nestedset_success').clone();
                        $('#flashes').append(alert);
                        $('#nestedset_loading').modal('hide');
                    },
                    error: function() {
                        // hide modal, display error alert
                        var alert = $('#nestedset_error').clone();
                        $('#flashes').append(alert);
                        $('#nestedset_loading').modal('hide');
                    }
                });
            }
        }
    });
}

// Private functions

function isAccepted($droppable, $draggable, ui) {
    if($draggable.nodeParent() && $draggable.nodeParent()[0].id == $droppable[0].id && dropActionName(ui, $droppable) == 'after') { return false; }
    return ($.inArray($droppable, $draggable.selectBranch()) == -1);
}

function dropActionName(ui, $droppable)
{
    var $draggable = ui.draggable || ui.helper.find('table tbody tr:first');    
    var draggableX = ui.offset.top + $draggable.outerHeight() / 2;
    var droppableOffset = $droppable.offset().top;
    var droppableHeight = $droppable.outerHeight();
    
    if (droppableOffset <= draggableX && draggableX < droppableOffset + droppableHeight * 1/3 )
    { return 'before'; } else 
    if (droppableOffset + droppableHeight * 1/3 <= draggableX && draggableX  <= droppableOffset + droppableHeight * 2/3 )
    { return 'in'; } else
    if (droppableOffset + droppableHeight * 2/3 < draggableX && draggableX <= droppableOffset + droppableHeight )
    { return 'after'; }
}

function highlightAction(ui, $droppable) {
    var className = 'droppable-'+dropActionName(ui, $droppable);
    $droppable.removeClass('droppable-before').removeClass('droppable-in').removeClass('droppable-after').addClass(className);
}
        
function unhighlightAction(ui, $droppable) {
    ui.draggable.removeData('current-droppable');
    $droppable.removeClass('droppable-before').removeClass('droppable-in').removeClass('droppable-after');
}