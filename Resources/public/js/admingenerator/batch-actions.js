/*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for List Batch actions
 *  Author:         loostro <loostro@gmail.com>
 *  License:        MIT
 */

// the semi-colon before function invocation is a safety net against concatenated
// scripts and/or other plugins which may not be closed properly.
;(function ( $, window, undefined ) {
    
    // undefined is used here as the undefined global variable in ECMAScript 3 is
    // mutable (ie. it can be changed by someone else). undefined isn't really being
    // passed in so we can ensure the value of it is truly undefined. In ES5, undefined
    // can no longer be modified.
    
    // window is passed through as local variable rather than global
    // as this (slightly) quickens the resolution process and can be more efficiently
    // minified (especially when both are regularly referenced in your plugin).

    // Create the defaults once
    var pluginName = 'agen$batchActions',
        document = window.document,
        defaults = {
            submitSelector: 'input[type=submit]',
            actionsSelector: 'select[name=action]',
            noneSelected: "You have to select at least one element."
        };

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( {}, defaults, options) ;
        
        this._defaults = defaults;
        this._name = pluginName;
        
        this._init();
    }
    
    Plugin.prototype = {

        _init: function() {
            // Plugin-scope helper
            var that = this;

            // Hide submit button
            $(that.element).find(that.options.submitSelector).hide();
            
            // Select container
            var $batch    = $(that.element).find('input[name="selected[]"]');
            var $batchAll = $(that.element).find('input[name="batchAll"]');
            
            // Grant plugin-scope access to selectors
            that.$batch  = $batch;
            that.$selector = $(that.element).find(that.options.actionsSelector); 
            
            // bind onSelect to button click event
            that.$selector.on('change', function(e){
                e.preventDefault();
                that._onSelect();
            });
            
            // bind onChange to button click event
            $batchAll.on('change', function(e){
                that._onSelectAll($(e.target));
            });
        },
                
        _onSelectAll: function($button) {
            this.$batch.prop('checked', $button.is(':checked'));
        },

        _onSelect: function() {
        	if (this.$selector.val()=='none') {
        		return false;
        	}
            // Alert if none selected
            if (this.$batch.filter(':checked').length == 0) {
                alert(this.options.noneSelected);
                this.$selector.val('none');
                return false;
            }
            
            // Confirm action
            if (this.$selector.data('confirm') && !confirm(this.$selector.data('confirm'))) {
            	this.$selector.val('none');
            	return false;
            }
            
            $(this.element).find(this.options.submitSelector).click();
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