 /*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for List Filters
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
        defaults = {
            group_prototype: "",
            html_prototypes: {},
            js_prototypes: {}
        };

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
                group:              '[role="filters-group"]',
                groupDismiss:       '[role="filters-group-dismiss"]',
                groupToggle:        '[role="filters-group-toggle"]',
                groupPanel:         '[role="filters-group-panel"]',
                groupList:          '[role="filters-group-list"]',
                filter:             '[role="filter"]',
                filterDismiss:      '[role="filter-dismiss"]',
                filterPrototype:    '[role="filter-prototype"]'
            };

            that.elementId      = that.$element.attr('id');
            that.$rootList      = that.$element.find('[role="filters-root-list"]').first();
            that.$ctrlReset     = that.$element.find('[role="filters-ctrl-reset"]').first();
            that.$ctrlAddGroup  = that.$element.find('[role="filters-ctrl-add-group"]').first();

            // init toggle and dismiss on existing groups
            that.$rootList.children(that.selector.group).each(function(i, group) {
                that._enableGroup(group);
            });

            // enable reset groups button
            that.$ctrlReset.on('click', function(e) {
                e.preventDefault();

                that.$rootList.children(that.selector.group).remove();
            });

            // enable add group button
            that.$ctrlAddGroup.on('click', function(e) {
                e.preventDefault();

                var gIndex = that.$rootList.data('index');
                var gPrototype = that._unescapeHtml(that.options.group_prototype);
                var $newGroup = $(gPrototype.replace(/__filter_group_name__/g, gIndex));

                that.$rootList.append($newGroup);
                that.$rootList.data('index', gIndex+1);
                that._enableGroup($newGroup);
            });
        },

        /**
         * Enable group functionality
         */
        _enableGroup: function(group) {
            // Plugin-scope helper
            var that = this;

            var $group          = $(group);
            var $gToggle        = $group.find(that.selector.groupToggle);
            var $gDismiss       = $group.find(that.selector.groupDismiss);
            var $gList          = $group.find(that.selector.groupList);
            var $gPanel         = $group.find(that.selector.groupPanel);
            var $ctrlAddFilter  = $group.find(that.selector.filterPrototype);
            var gIndex          = $group.prevAll(that.selector.group).length;
            var groupId         = $group.attr('id');
            var groupName       = $group.attr('name');

            // enable collapse toggle
            $gPanel.collapse({
                parent: that.$rootList,
                toggle: false
            });

            $gToggle.on('click', function(e) {
                e.preventDefault();
                $gPanel.collapse('toggle');
            });

            // enable dismiss group            
            $gDismiss.on('click', function(e) {
                e.preventDefault();
                $group.remove();
            });

            // enable filters
            $gList.children(that.selector.filter).each(function(i, filter) {
                that._enableFilter(filter);
            });

            // enable add filter button
            $ctrlAddFilter.on('click', function(e) {
                e.preventDefault();

                var fIndex  = $gList.data('index');
                var fField  = $(this).data('field');
                var fHtmlPrototype  = that._unescapeHtml(that.options.html_prototypes[fField]);

                var idRegex = new RegExp('id="'+that.elementId, "g");
                var nameRegex = new RegExp('name="'+that.elementId, "g");

                var $newFilter = $(
                    fHtmlPrototype
                        .replace(idRegex, 'id="'+groupId)
                        .replace(nameRegex, 'name="'+groupName)
                        .replace(/__filter_form_name__/g, fIndex)
                );

                $gList.append($newFilter);
                $gList.data('index', fIndex+1);

                that._enableFilter($newFilter);
            });
        },

        _enableFilter: function(filter) {
            // Plugin-scope helper
            var that = this;

            var $filter = $(filter);
            var $dismissFilter = $filter.find(that.selector.filterDismiss);
            var fField = $('#'+$filter.attr('id')+'_field').val();
            var fJsPrototype    = that.options.js_prototypes[fField];

            // run prototype js
            fJsPrototype.call(window, $newFilter.attr('id'));

            $dismissFilter.on('click', function(e) {
                e.preventDefault();
                $filter.remove();
            });
        },

        _unescapeHtml: function(safe) {
            return $('<div />').html(safe).text();
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