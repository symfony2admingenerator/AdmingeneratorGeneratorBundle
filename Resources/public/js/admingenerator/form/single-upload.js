/*
 *  Project:        Symfony2Admingenerator
 *  Description:    jQuery plugin for SingleUpload form widget
 *  Author:         loostro <loostro@gmail.com>
 *  License:        MIT
 *  Dependencies: 
 *                  - blueimp / JavaScript Load Image 1.2.3
 *                    https://github.com/blueimp/JavaScript-Load-Image
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
    var pluginName = 'agen$singleUpload',
        document = window.document,
        defaults = {
            maxWidth:   320,
            maxHeight:  180,
            minWidth:   16,
            minHeight:  16,
            previewImages:    true,
            previewAsCanvas:  true,
            isEmpty:          true,
            nameable:         false,
            deleteable:       false,
            filetypes:  {
                'audio':            "Audio",
                'archive':          "Archive",
                'html':             "HTML",
                'image':            "Image",
                'pdf-document':     "PDF<br />Document",
                'plain-text':       "Plain<br />Text",
                'presentation':     "Presentation",
                'spreadsheet':      "Spreadsheet",
                'text-document':    "Text<br />Document",
                'unknown':          "Unknown<br />Filetype",
                'video':            "Video"
            }
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
            
            // Select container
            var $widgetContainer  = $('#'+that.element.id+'_widget_container');
            var $cancelButton     = $widgetContainer.find('.singleupload-buttonbar .cancel');
            var $deleteButton     = $widgetContainer.find('.singleupload-buttonbar .delete');
            
            // Grant plugin-scope access to button selectors
            that.$cancelButton    = $cancelButton;
            that.$deleteButton    = $deleteButton;
            
            // Prepare nameable behavior if enabled
            if(that.options.nameable) {
                var $nameableInput    = $('#'+that.element.id+'_nameable');
                that.$nameableInput   = $nameableInput;
                that.originalName     = $nameableInput.val();
            }
            
            // Set isDeletable
            that.isDeletable      = (!that.options.isEmpty && that.options.deleteable);
            
            // Add deletable behaviour
            if (that.isDeletable) {
                var $deleteableInput    = $('#'+that.element.id+'_deleteable');
                that.$deleteableInput   = $deleteableInput;
                $deleteButton.show();
            }            
            
            // Make sure upload input is empty (prevent cached form data)
            that._resetInput(that);
            
            // bind onChange to file input change event
            $(that.element).on('change', function(){
                that._onChange(that);
            });
            
            // bind onCancel to button click event
            $cancelButton.click(function(){
                that._onCancel(that);
            });
            
            // bind onDelete to button click event
            $deleteButton.click(function(){
                that._onDelete(that);
            });
        },

        _onChange: function(that) {
            var file = that.element.files[0]; 
            
            // show cancel button
            that.$cancelButton.removeClass('disabled').show('slide', {direction: 'left'}, 'slow');
            // hide delete button
            (that.isDeletable) ? that.$deleteButton.addClass('disabled').hide('slide', {direction: 'right'}, 'slow') : '';
            
            // change name
            (that.options.nameable) ? that.$nameableInput.removeAttr('disabled').val(file.name) : '';
            
            // trigger preview
            (that.options.previewImages && that._isImage(file))
            ?   that._onPreviewImage(that)
            :   that._onPreviewFile(that);
        },
        
        _onCancel: function(that) {
            // hide cancel button
            that.$cancelButton.addClass('disabled').hide('slide', {direction: 'left'}, 'slow');            
            // show delete button
            (that.isDeletable) ? that.$deleteButton.removeClass('disabled').show('slide', {direction: 'right'}, 'slow') : '';
            
            // revert name
            (that.options.nameable) ? that.$nameableInput.val(that.originalName) : '';
            
            var $activePreview    = $('.'+that.element.id+'_preview_image.active');
            var $previewDownload  = $('.'+that.element.id+'_preview_image.download');
            
            // sanity-check
            if ($activePreview.hasClass('download')) return;
            
            // reset input
            that._resetInput(that);
            
            // animate preview toggle
            $previewDownload.slideDown({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).addClass('active'); }
            });  
            $activePreview.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).remove(); }
            });
        },

        _onDelete: function(that) {
            var $previewDownload  = $('.'+that.element.id+'_preview_image.download');
            
            // sanity check
            if (!that.isDeletable) return;
            
            // clear name
            (that.options.nameable) ? that.$nameableInput.attr('disabled', 'disabled').val('') : '';
            
            // hide and remove delete button
            that.$deleteButton.parent().addClass('removed').end()
            that.$deleteButton.addClass('disabled').hide('slide', {
                direction:  'left', 
                queue:      false,
                complete:   function(){ 
                    that.$deleteButton.parent().remove(); 
                }
            }, 'slow');
            
            // hide previewDownload and remove the image
            $previewDownload.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).empty(); }
            });
            
            // Set deletable flag
            that.$deletableInput.val(true);
            
            // Disable delete button animation
            that.isDeletable = false;
        },
        
        _onPreviewImage: function(that) {     
            var file = that.element.files[0];     
            // load preview image
            window.loadImage(
                file,
                function (img) {
                    var $activePreview = $('.'+that.element.id+'_preview_image.active');
                    var $previewUpload = $activePreview.clone().empty().hide().removeClass('download').addClass('upload');

                    var $filelabel = $('<div/>').addClass('row-fluid').text(file.name);
                    var $filesize = $('<div/>').addClass('row-fluid').text(that._bytesToSize(file.size));
                    
                    // create and insert new preview node
                    $previewUpload.appendTo($activePreview.parent())
                                  .html($(img).addClass('img-polaroid')).append($filelabel).append($filesize);
                    // animate preview toggle
                    $previewUpload.slideDown({
                        duration: 'slow', 
                        queue:    false,
                        complete: function() { $(this).addClass('active'); }
                    });
                    $activePreview.slideUp({ 
                        duration: 'slow', 
                        queue:    false,
                        complete: function() { 
                            ($(this).hasClass('download'))
                            ?   $(this).removeClass('active')
                            :   $(this).remove(); 
                        }
                    });
                }, {
                    maxWidth:   that.options.maxWidth,
                    maxHeight:  that.options.maxHeight,
                    minWidth:   that.options.minWidth,
                    minHeight:  that.options.minHeight,
                    canvas:     that.options.previewAsCanvas,
                    noRevoke:   true
                }
            );
        },
        
        _onPreviewFile: function(that) {
            var $activePreview = $('.'+that.element.id+'_preview_image.active');
            var $previewUpload = $activePreview.clone().empty().hide().removeClass('download').addClass('upload');
            
            var file = that.element.files[0];
            var filetype = that._checkFileType(that, file);
            var $fileicon = $('<div/>').addClass('fileicon').addClass(filetype)
                                       .html(that.options.filetypes[filetype]);
            
            var $filelabel = $('<div/>').addClass('row-fluid').text(file.name);
            var $filesize = $('<div/>').addClass('row-fluid').text(that._bytesToSize(file.size));
                                   
            // create and insert new preview node
            $previewUpload.appendTo($activePreview.parent())
                          .html($fileicon).append($filelabel).append($filesize);
            // animate preview toggle
            $previewUpload.slideDown({
                duration: 'slow', 
                queue:    false,
                complete: function() { $(this).addClass('active'); }
            });
            $activePreview.slideUp({ 
                duration: 'slow', 
                queue:    false,
                complete: function() { 
                    ($(this).hasClass('download'))
                    ?   $(this).removeClass('active')
                    :   $(this).remove(); 
                }
            });
        },
        
        _bytesToSize: function(bytes) {
            var kilobyte = 1024;
            var megabyte = kilobyte * 1024;
            var gigabyte = megabyte * 1024;
            var terabyte = gigabyte * 1024;

            if (bytes < kilobyte)         return bytes + ' B'; 
            else if (bytes < megabyte)    return (bytes / kilobyte).toFixed(2) + ' KB';
            else if (bytes < gigabyte)    return (bytes / megabyte).toFixed(2) + ' MB';
            else if (bytes < terabyte)    return (bytes / gigabyte).toFixed(2) + ' GB';
            else                          return (bytes / terabyte).toFixed(2) + ' TB';
        },
        
        _checkFileType: function(that, file) {            
            if (that._isAudio(file))        return 'audio';
            if (that._isArchive(file))      return 'archive';
            if (that._isHTML(file))         return 'html';
            if (that._isImage(file))        return 'image';
            if (that._isPDFDocument(file))  return 'pdf-document';
            if (that._isPlainText(file))    return 'plain-text';
            if (that._isPresentation(file)) return 'presentation';
            if (that._isSpreadsheet(file))  return 'spreadsheet';
            if (that._isTextDocument(file)) return 'text-document';
            if (that._isVideo(file))        return 'video';
            // else
            return 'unknown';
        },
        
        _isAudio: function(file) {
            return (file.type.match('audio/.*'));
        },
        
        _isArchive: function(file) {
            return (
                file.type.match('application/.*compress.*') || 
                file.type.match('application/.*archive.*') || 
                file.type.match('application/.*zip.*') || 
                file.type.match('application/.*tar.*') || 
                file.type.match('application/x\-ace') || 
                file.type.match('application/x\-bz2') || 
                file.type.match('gzip/document')
            );
        },
        
        _isHTML: function(file) {
            return (file.type.match('text/html'));
        }, 
        
        _isImage: function(file) {
            return (file.type.match('image/.*'));
        },
        
        _isPDFDocument: function(file) {
            return (
                file.type.match('application/acrobat') || 
                file.type.match('applications?/.*pdf.*') || 
                file.type.match('text/.*pdf.*')
            );
        }, 
        
        _isPlainText: function(file) {
            return (file.type.match('text/plain'));
        },
        
        _isPresentation: function(file) {
            return (
                file.type.match('application/.*ms\-powerpoint.*') || 
                file.type.match('application/.*officedocument\.presentationml.*') || 
                file.type.match('application/.*opendocument\.presentation.*')
            );
        },
        
        _isSpreadsheet: function(file) {
            return (
                file.type.match('application/.*ms\-excel.*') || 
                file.type.match('application/.*officedocument\.spreadsheetml.*') || 
                file.type.match('application/.*opendocument\.spreadsheet.*')
            );
        },
        
        _isTextDocument: function(file) {
            return (
                file.type.match('application/.*ms\-?word.*') || 
                file.type.match('application/.*officedocument\.wordprocessingml.*') || 
                file.type.match('application/.*opendocument\.text.*')
            );
        },
        
        _isVideo: function(file) {
            return (file.type.match('video/.*'));
        },
        
        _resetInput: function(that) {
            // create replacement input
            var $replacement = $(that.element).val('').clone(true);
            // replace inputs
            $(that.element).replaceWith( $replacement );
            // point plugin to new element
            that.element = $replacement[0];
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