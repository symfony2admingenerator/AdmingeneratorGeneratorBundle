 /*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for List Object actions
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
    var pluginName = 'agen$filters',
        document = window.document,
        defaults = {};

    // The actual plugin constructor
    function Plugin( element, options ) {
        this.element = element;
        this.$element = $(element);

        // jQuery has an extend method which merges the contents of two or
        // more objects, storing the result in the first object. The first object
        // is generally empty as we don't want to alter the default options for
        // future instances of the plugin
        this.options = $.extend( {}, defaults, options);
        
        this._defaults = defaults;
        this._name = pluginName;
        
        this._init();
    }
    
    Plugin.prototype = {

        _init: function() {
            // Plugin-scope helper
            var that = this;

            // setup selectors and vars necessary to operate
            that.selector = {
                group:      '[role="filters-group"]',
                list:       '[role="filters-list"]',
                toggle:     '[data-toggle="collapse"]',
                dismiss:    '[data-dismiss="filters-group"]',
                counter:    '[role="counter"]' 
            };

            that.$root      = that.$element.find('#'+that.element.id+'_filters_root');
            that.$resetBtn  = that.$element.find('#'+that.element.id+'_filters_btn_reset');
            that.$addBtn    = that.$element.find('#'+that.element.id+'_filters_btn_add');

            that.vars = {
                index:      that.$root.children(that.selector.group).length,
                prototype:  that.$root.data('prototype')
            };

            // init toggle and dismiss on existing groups
            that.$root.children(that.selector.group).each(function(i, group) {
                that._enableToggleGroup(group);
                that._enableDismissGroup(group);
            });

            // enable reset button
            that.$resetBtn.on('click', function(e) {
                e.preventDefault();

                that.$root.children(that.selector.group).remove();
                that.vars.index = 0;
            });

            // enable add button
            that.$addBtn.on('click', function(e) {
                e.preventDefault();

                var $newGroup = $(that.vars.prototype.replace(/__name__/g, that.vars.index));
                that.vars.index++;

                that.$root.append($newGroup);
                that._enableToggleGroup($newGroup);
                that._enableDismissGroup($newGroup);
            });
        },

        /**
         * Enables collapse functionality on given group
         * 
         * @param $group Element matching that.selector.group
         */
        _enableToggleGroup: function(group) {
            // Plugin-scope helper
            var that = this;

            var $group  = $(group);   
            var $toggle = $group.find(that.selector.toggle);
            var $list   = $group.find(that.selector.list);

            $list.collapse({
                parent: that.$root
            });

            $toggle.on('click', function(e) {
                e.preventDefault();
                $list.collapse('toggle');
            });
        },

        /**
         * Enables dismiss functionality on given group
         * 
         * @param $group Element matching that.selector.group
         */
        _enableDismissGroup: function(group) {
            // Plugin-scope helper
            var that = this;

            var $group      = $(group);            
            var $dismiss    = $group.find(that.selector.dismiss);
            var $list       = $group.find(that.selector.list);

            $dismiss.on('click', function(e) {
                e.preventDefault();

                var isOpen      = $list.hasClass('in');
                var $siblings   = $group.siblings(that.selector.group);
                var $nextItems  = $group.nextAll(that.selector.group);
                var $prevItem   = $group.prev(that.selector.group);
                var $nextItem   = $group.next(that.selector.group);

                $group.remove();
                that.vars.index--;

                $nextItems.each(function(i, group) {
                    var $group  = $(group);
                    var $toggle = $group.find(that.selector.toggle);
                    var $list   = $group.find(that.selector.list);
                    var index   = $siblings.index($group);

                    var oldid = $list.attr('id');
                    var newid = oldid.replace(/\d+_list$/g, index + '_list');

                    $toggle.attr('href', newid);
                    $list.attr('id', newid);
                    $toggle.find(that.selector.counter).html(index);
                });

                if (isOpen) {
                    if ($nextItem.length) {
                        $nextItem.find(that.selector.list).collapse('show');
                    } else {
                        $prevItem.find(that.selector.list).collapse('show');
                    }
                }            
            });
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