 /*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for Nested Tree
 *  License:        MIT
 *  
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
 */
// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ($, window, undefined) {
    
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.
    
    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'agen$nestedTree';

    // The actual plugin constructor
    function Plugin( element, options ) {
    	this._defaults = {
    		urls: {
    			move: '/'
    		},
    		labels: {
    			expand: 'Expand',
    			collapse: 'Collapse'
    		}
    	};
        this._name = pluginName;
        
        this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( 
        	{},
        	this._defaults,
	    	options
	    );
        
        this._init();
    }
    
    Plugin.prototype = {

        _init: function() {
            this._treeInit();
            this._dragNDropInit();
        },
        
        _treeInit: function() {
    		var self = this;
    		
    		$(this.element).treeTable({
                stringExpand: this.options.labels.expand,
                stringCollapse: this.options.labels.collapse,
                doubleclickMode: true,
                initialRootState: "expanded",
                initialIndent: -19,
                onNodeInit: function(node, expandable, isRootNode) {
                	self._treeOnNodeInitCallback(node, expandable, isRootNode);
                },
                onNodeReinit: function(node, expandable, isRootNode) {
                	self._treeOnNodeReinitCallback(node, expandable, isRootNode);
                }
            });
        },
        
        _treeOnNodeInitCallback: function(node, expandable, isRootNode) {
    		(isRootNode) ? node.addClass('ui-helper-hidden') : '';
            if (expandable) {
	            var cell = $(node).children().first();
	            var expander = $('<i />').addClass('glyphicon glyphicon-expander');
	            cell.append(expander);
        	}
    	},
    	
    	_treeOnNodeReinitCallback: function(node, expandable, isRootNode) {
    		$(node).find('.glyphicon-expander').remove();
    	},
        
        _dragNDropInit: function() {
    		var self = this;
    		
    		$(this.element).find("tbody tr").draggable({
                helper: function(e) {
                	return self._dragHelperCallback(this, e);
                },
                drag: function(e, ui) {
                	self._dragCallback(this, e, ui);
                },
                refreshPositions: true, // Performance?
                revert: "invalid",
                revertDuration: 300,
                scroll: true
            });

        	$(this.element).find("tbody tr").droppable({
                accept: $(self.element).selector + " tbody tr",
                over: function(e, ui) {
                    self._dropOverCallback(this, e, ui);
                },
                out: function(e, ui) {
                    self._dropOutCallback(this, e, ui);
                },
                drop: function(e, ui) {
                    self._dropCallback(this, e, ui);
                }
            });
        },
    	
    	_dropIsAccepted: function($droppable, $draggable, ui) {
    	    if ($draggable.nodeParent() 
    	    		&& $draggable.nodeParent()[0].id == $droppable[0].id 
    	    		&& this._dropActionName(ui, $droppable) == 'after') {
    	    	return false;
    	    }

    	    return ($.inArray($droppable, $draggable.selectBranch()) == -1);
    	},
    	
    	_dropActionName: function(ui, $droppable) {
    	    var $draggable = ui.draggable || ui.helper.find('table tbody tr:first');    
    	    var draggableX = ui.offset.top + $draggable.outerHeight() / 2;
    	    var droppableOffset = $droppable.offset().top;
    	    var droppableHeight = $droppable.outerHeight();
    	    
    	    if (droppableOffset <= draggableX && draggableX < droppableOffset + droppableHeight * 1/3 ) { 
    	    	return 'before';
    	    } else if (droppableOffset + droppableHeight * 1/3 <= draggableX && draggableX  <= droppableOffset + droppableHeight * 2/3 ) { 
    	    	return 'in'; 
    	    } else if (droppableOffset + droppableHeight * 2/3 < draggableX && draggableX <= droppableOffset + droppableHeight ) {
    	    	return 'after';
    	    }
    	},
    	
    	_dragHighlightAction: function(ui, $droppable) {
    	    var className = 'droppable-' + this._dropActionName(ui, $droppable);
    	    $droppable.removeClass('droppable-before').removeClass('droppable-in').removeClass('droppable-after').addClass(className);
    	},
    	        
    	_dragUnhighlightAction: function(ui, $droppable) {
    	    ui.draggable.removeData('current-droppable');
    	    $droppable.removeClass('droppable-before').removeClass('droppable-in').removeClass('droppable-after');
    	},
    	
    	_dragHelperCallback: function(element, e) {
            var row = $(e.target).closest('tr');
            var branch = row.selectBranch();
            
            return $('<div />').addClass('nested-helper').css({
              'width': branch.closest('table').width()+'px',
              'height': row.outerHeight()
            }).append($('<table />').css('width', '100%').append(branch.clone()));
        },
        
        _dragCallback: function(element, e, ui) {
            var $draggable = $(element);
            var $droppable = $draggable.data('current-droppable');
            if($droppable && this._dropIsAccepted($droppable, $draggable, ui)) {
            	this._dragHighlightAction(ui, $droppable);
            }
        },
        
        _dropOverCallback: function (element, e, ui) {
        	var $droppable = $(element);
            var $draggable = ui.draggable;
            if(this._dropIsAccepted($droppable, $draggable, ui)) {
            	this._dragHighlightAction(ui, $droppable);
                $draggable.data('current-droppable', $droppable);
            }
        },
        
        _dropOutCallback: function(element, e, ui) {
        	var $droppable = $(element);
            var $draggable = ui.draggable;
            if (this._dropIsAccepted($droppable, $draggable, ui)) {
            	this._dragUnhighlightAction(ui, $droppable);
            }
        },
        
        _dropCallback: function(element, e, ui) {
        	var $droppable = $(element);
            var $draggable = ui.draggable;
          
            if (this._dropIsAccepted($droppable, $draggable, ui)) {
            	this._dragUnhighlightAction(ui, $droppable);
                var action = this._dropActionName(ui, $droppable); 
                
                var url = this.options.urls.move;
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
                        if(action == 'in') {
                        	$droppable.nodeReinitialize();
                        }
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
    };

    // You don't need to change something below:
    // A really lightweight plugin wrapper around the constructor,
    // preventing against multiple instantiations and allowing any
    // public function (ie. a function whose name doesn't start
    // with an underscore) to be called via the jQuery plugin,
    // e.g. $(element).defaultPluginName('functionName', arg1, arg2)
    $.fn[pluginName] = function ( options ) {
        var args = arguments;

        // Is the first parameter an object (options), or was omitted,
        // instantiate a new instance of the plugin.
        if (options === undefined || typeof options === 'object') {
            return this.each(function () {

                // Only allow the plugin to be instantiated once,
                // so we check that the element has no plugin instantiation yet
                if (!$.data(this, 'plugin_' + pluginName)) {

                    // if it has no instance, create a new one,
                    // pass options to our plugin constructor,
                    // and store the plugin instance
                    // in the elements jQuery data object.
                    $.data(this, 'plugin_' + pluginName, new Plugin( this, options ));
                }
            });

        // If the first parameter is a string and it doesn't start
        // with an underscore or "contains" the `init`-function,
        // treat this as a call to a public method.
        } else if (typeof options === 'string' && options[0] !== '_' && options !== 'init') {

            // Cache the method call
            // to make it possible
            // to return a value
            var returns;

            this.each(function () {
                var instance = $.data(this, 'plugin_' + pluginName);

                // Tests that there's already a plugin-instance
                // and checks that the requested public method exists
                if (instance instanceof Plugin && typeof instance[options] === 'function') {

                    // Call the method of our plugin instance,
                    // and pass it the supplied arguments.
                    returns = instance[options].apply( instance, Array.prototype.slice.call( args, 1 ) );
                }

                // Allow instances to be destroyed via the 'destroy' method
                if (options === 'destroy') {
                  $.data(this, 'plugin_' + pluginName, null);
                }
            });

            // If the earlier cached method
            // gives a value back return the value,
            // otherwise return this to preserve chainability.
            return returns !== undefined ? returns : this;
        }
    };

}(jQuery, window));
