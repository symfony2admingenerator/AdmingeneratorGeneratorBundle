/*!
 * Modified jQuery treeTable plugin
 * ================================
 * Based on jQuery treeTable Plugin 2.3.0
 * 
 * http://ludo.cubicphuse.nl/jquery-plugins/treeTable/doc/
 * 
 * Copyright 2011, Ludo van den Boom
 * Dual licensed under the MIT or GPL Version 2 licenses.
 * 
 * Modifications:
 * - remove option clickableNodeNames
 * + add option clickableElement:
 *   Specify element in row node on which expander event will be listened for.
 *   (default) If not set event will be listened on the row itself.
 * + add option doubleclickMode:
 *   If true, listen for double click event.
 *   (default) If false, listen for click event.
 * + add option initialRootState:
 *   Overwrites initialState setting for root nodes. Can be "expanded" or "collapsed".
 *   (default) "collapsed"
 * + add option initialIndent:
 *   Global indent modifier.
 *   (default) 0
 * + add function $.fn.moveBranch:
 *   Move +this+ node's entire branch before/after target element.
 *   (arg) +where+   >> where to move the branch ("before" or "after")
 *   (arg) +element+ >> target element
 * + add function $.fn.selectBranch:
 *   Select +this+ node's entire branch.
 * + add function $.fn.branchLast:
 *   Select +this+ node's entire branch last node.
 * + add function $.fn.nodeParent:
 *   Return +this+ node's parent.
 * + add callback onNodeInit
 *   (arg) +node+        >> initialized node
 *   (arg) +expandable+  >> is node expandable?
 *   (arg) +isRootNode+  >> is node a root node?
 * + add callback onNodeReinit
 *   (arg) +node+        >> reinitialized node
 *   (arg) +expandable+  >> is node expandable?
 *   (arg) +isRootNode+  >> is node a root node?
 * Modified internal functions:
 * + added selectBranch function
 * + added branchLast function
 * - removed move function
 * + added insert function
 * + modified initialize function
 * + added reinitialize function
 * + modified indent function
 * + modified getPaddingLeft function
 * 
 * Modified 2013, loostro:
 * Released under the MIT license.
 */
;(function($) {
  // Helps to make options available to all functions
  // TODO: This gives problems when there are both expandable and non-expandable
  // trees on a page. The options shouldn't be global to all these instances!
  var admTTopts;
  var admTTdefaultPaddingLeft;
  var persistStore;

  $.fn.treeTable = function(opts) {
      admTTopts = $.extend({}, $.fn.treeTable.defaults, opts);

      if(admTTopts.persist) {
          persistStore = new Persist.Store(admTTopts.persistStoreName);
      }

      return this.each(function() {
          $(this).addClass("treeTable").find("tbody tr").each(function() {
              // Skip initialized nodes.
              if (!$(this).hasClass('initialized')) {
                  var isRootNode = ($(this)[0].className.search(admTTopts.childPrefix) == -1);

                  // To optimize performance of indentation, I retrieve the padding-left
                  // value of the first root node. This way I only have to call +css+
                  // once.
                  if (isRootNode && isNaN(admTTdefaultPaddingLeft)) {
                      admTTdefaultPaddingLeft = admTTopts.initialIndent + parseInt($($(this).children("td")[admTTopts.treeColumn]).css('padding-left'), 10);
                  }

                  // Set child nodes to initial state if we're in expandable mode.
                  if (!isRootNode && admTTopts.expandable && admTTopts.initialState == "collapsed") {
                      $(this).addClass('ui-helper-hidden');
                  }

                  // If we're not in expandable mode, initialize all nodes.
                  // If we're in expandable mode, only initialize root nodes.
                  if (!admTTopts.expandable || isRootNode) {
                      initialize($(this));
                  }
              }
          });
      });
  };

  $.fn.treeTable.defaults = {
      childPrefix: "child-of-",
      clickableElement: false,
      doubleclickMode: false,
      expandable: true,
      indent: 19,
      initialIndent: 0,
      initialState: "collapsed",
      initialRootState: "collapsed",
      onNodeShow: null,
      onNodeHide: null,
      onExpandableInit: null,
      onNonExpandableInit: null,
      treeColumn: 0,
      persist: false,
      persistStoreName: 'treeTable',
      stringExpand: "Expand",
      stringCollapse: "Collapse"
  };

  //Expand all nodes
  $.fn.expandAll = function() {
      $(this).find("tr").each(function() {
          $(this).expand();
      });
  };

  //Collapse all nodes
  $.fn.collapseAll = function() {
      $(this).find("tr").each(function() {
          $(this).collapse();
      });
  };

  // Recursively hide all node's children in a tree
  $.fn.collapse = function() {
      return this.each(function() {
          $(this).removeClass("expanded").addClass("collapsed");

          if (admTTopts.persist) {
              persistNodeState($(this));
          }

          childrenOf($(this)).each(function() {
              if(!$(this).hasClass("collapsed")) {
                  $(this).collapse();
              }

              $(this).addClass('ui-helper-hidden');

              if($.isFunction(admTTopts.onNodeHide)) {
                  admTTopts.onNodeHide.call(this);
              }

          });
      });
  };

  // Recursively show all node's children in a tree
  $.fn.expand = function() {
      return this.each(function() {
          $(this).removeClass("collapsed").addClass("expanded");

          if (admTTopts.persist) {
              persistNodeState($(this));
          }

          childrenOf($(this)).each(function() {
              initialize($(this));

              if($(this).is(".expanded.parent")) {
                  $(this).expand();
              }

              $(this).removeClass('ui-helper-hidden');

              if($.isFunction(admTTopts.onNodeShow)) {
                  admTTopts.onNodeShow.call(this);
              }
          });
      });
  };

  // Reveal a node by expanding all ancestors
  $.fn.reveal = function() {
      $(ancestorsOf($(this)).reverse()).each(function() {
          initialize($(this));
          $(this).expand().show();
      });

      return this;
  };

  // Add an entire branch to +destination+
  $.fn.appendBranchTo = function(destination) {
      var node    = $(this);
      var parent  = parentOf(node);
      var target  = $(destination);

      var ancestorNames = $.map(ancestorsOf(target), function(a) { return a.id; });

      // Conditions:
      // 1: +node+ should not be inserted in a location in a branch if this would
      //    result in +node+ being an ancestor of itself.
      // 2: +node+ should not have a parent OR the destination should not be the
      //    same as +node+'s current parent (this last condition prevents +node+
      //    from being moved to the same location where it already is).
      // 3: +node+ should not be inserted as a child of +node+ itself.
      if ($.inArray(node[0].id, ancestorNames) == -1 && (!parent || (target.id != parent[0].id)) && target.id != node[0].id) {
          insert(node, 'after', target); // Move nodes to new location
          
          if (parent) { 
              node.removeClass(admTTopts.childPrefix + parent[0].id); 
          }  // Remove parent
          
          node.addClass(admTTopts.childPrefix + target[0].id); // Set new parent
          indent(node, ancestorsOf(node).length * admTTopts.indent); // Set new indentation
      }

      return this;
  };

  // Move +this+ node's entire branch before/after target +element+.
  $.fn.moveBranch = function(where, element) {
      // use appendBranchTo to handle 'in' action
      if (where == 'in') { 
          $(this).appendBranchTo(element); return; 
      }

      // sanity check
      if ($.inArray(where, ['before','after']) == -1) { 
        return; 
      }

      var node          = $(this);
      var parent        = parentOf(node);

      var target        = $(element);
      var targetParent  = parentOf(target);

      var ancestorNames = $.map(ancestorsOf(target), function(a) { return a.id; });

      // Conditions:
      // 1: +node+ should not be inserted in a location in a branch if this would
      //    result in +node+ being an ancestor of itself.
      // 2: +node+ should not be inserted before/after itself.
      if ($.inArray(node[0].id, ancestorNames) == -1 && target[0].id != node[0].id) {
          insert(node, where, target); // Move nodes to new location

          if (parent) { 
              node.removeClass(admTTopts.childPrefix + parent[0].id); 
          } // Remove parent

          if (targetParent) { 
              node.addClass(admTTopts.childPrefix + targetParent[0].id); 
          } // Set new parent

          indent(node, ancestorsOf(node).length * admTTopts.indent); // Set new indentation
      }

      return this;
  };

  // Add reverse() function from JS Arrays
  $.fn.reverse = function() {
      return this.pushStack(this.get().reverse(), arguments);
  };

  // Toggle an entire branch
  $.fn.toggleBranch = function() {
      if($(this).hasClass("collapsed")) {
          $(this).expand();
      } else {
          $(this).collapse();
      }

      return this;
  };
  
  // Get node's parent
  $.fn.nodeParent = function () {
      var $node = $(this);
      var match = $node[0].className.match(new RegExp(admTTopts.childPrefix+'[^\\s]+', 'i'));
      return (match) ? $('#node-'+match[0].substring(14)) : null;
  }
  
  // Reinitialize node
  $.fn.nodeReinitialize = function() {
      reinitialize($(this));

      return this;
  };
    
  // Select an entire branch
  $.fn.selectBranch = function() {
      return selectBranch(this);
  };
    
  // Select branch last node
  $.fn.branchLast = function() {
      return branchLast(this);
  };

  // === Private functions
  function ancestorsOf(node) {
      var ancestors = [];
      
      while(node = parentOf(node)) {
          ancestors[ancestors.length] = node[0];
      }
      
      return ancestors;
  };

  function childrenOf(node) {
      return $(node).siblings("tr." + admTTopts.childPrefix + node[0].id);
  };
    
  // note: this function assumes that 
  // last node of branch is the one with the highest index
  function branchLast(node) {
      return (childrenOf(node).length) ? branchLast(childrenOf(node).last()) : $(node);
  };
  
  // note: this function assumes that 
  // all nodes between node and branchLast(node) belong to this branch
  // that is true after initializing treeTable and as long as you use only 
  // provided API to move treeTable rows
  //
  // If node has no children, return that node
  function selectBranch(node) {
      var nodelast = branchLast(node);
      return (node[0].id === nodelast[0].id) ? node : node.nextUntil(nodelast).addBack().add(nodelast);
  };

  function getPaddingLeft(node) {
      return ancestorsOf(node).length * admTTopts.indent;
  }

  function indent(node, value) {
      var cell = $(node.children("td")[admTTopts.treeColumn]);
      cell[0].style.paddingLeft = admTTopts.initialIndent + value + 'px';

      childrenOf(node).each(function() {
          indent($(this), value + admTTopts.indent);
      });
  };

  function initialize(node) {
      if (!node.hasClass("initialized")) {
          node.addClass("initialized");

          var isRootNode = (node[0].className.search(admTTopts.childPrefix) == -1);
          var childNodes = childrenOf(node);
          var expandable = childNodes.length > 0;

          if (!node.hasClass("parent") && expandable) {
              node.addClass("parent");
          }

          if ($.isFunction(admTTopts.onNodeInit)) {
              admTTopts.onNodeInit.call(this, node, expandable, isRootNode);
          }

          if (expandable) {
              indent(node, getPaddingLeft(node));

              if (admTTopts.expandable) {
                  var handle = (admTTopts.clickableElement) ? node.find(admTTopts.clickableElement) : node;
                  handle.attr('title', admTTopts.stringExpand).addClass('expander');

                  handle.on((admTTopts.doubleclickMode) ? 'dblclick' : 'click', function(e){
                      e.preventDefault;
                      node.toggleBranch();
                  });

                  if (admTTopts.persist && getPersistedNodeState(node)) {
                      node.addClass('expanded');
                  }

                  // Check for a class set explicitly by the user, otherwise set the default class
                  if (!(node.hasClass("expanded") || node.hasClass("collapsed"))) {
                      (isRootNode) ? node.addClass(admTTopts.initialRootState) : node.addClass(admTTopts.initialState);
                  }

                  if(node.hasClass("expanded")) {
                      node.expand();
                  }
              }
          }
      }
  };
  
  function reinitialize(node) {
      if (node.hasClass("initialized")) {      
          node.removeClass('initialized').removeClass('parent')
              .removeClass('expanded').removeClass('collapsed');

          var isRootNode = (node[0].className.search(admTTopts.childPrefix) == -1);
          var childNodes = childrenOf(node);
          var expandable = childNodes.length > 0;

          if (admTTopts.expandable) {
              var handle = (admTTopts.clickableElement) ? node.find(admTTopts.clickableElement) : node;
              handle.removeAttr('title').removeClass('expander');

              handle.off((admTTopts.doubleclickMode) ? 'dblclick' : 'click');
          }

          if ($.isFunction(admTTopts.onNodeReinit)) {
              admTTopts.onNodeReinit.call(this, node, expandable, isRootNode);
          }

          if (expandable) { 
              node.addClass('expanded'); 
          }
          
          initialize(node);
      }
  };
  
  // note: this function assumes that 
  // all nodes between node and branchLast(node) belong to this branch
  // that is true after initializing treeTable and as long as you use only 
  // provided API to move treeTable rows
  function insert(node, where, target) {
      if (where == 'before') {
          // no problems here
          // simply insert before +target+
          selectBranch(node).insertBefore(target);
      }
      if (where == 'after') {
          // target may have children
          // insert after +target+ last child
          var targetlast = branchLast(target);
          selectBranch(node).insertAfter(targetlast);
      }
  };

  function parentOf(node) {
    var classNames = node[0].className.split(' ');

    for (var key=0; key<classNames.length; key++) {
        if (classNames[key].match(admTTopts.childPrefix)) {
          return $(node).siblings("#" + classNames[key].substring(admTTopts.childPrefix.length));
        }
    }

    return null;
  };

  //saving state functions, not critical, so will not generate alerts on error
  function persistNodeState(node) {
      if (node.hasClass('expanded')) {
          try {
              persistStore.set(node.attr('id'), '1');
          } catch (err) {

          }
      } else {
          try {
              persistStore.remove(node.attr('id'));
          } catch (err) {

          }
      }
  }

  function getPersistedNodeState(node) {
      try {
          return persistStore.get(node.attr('id')) == '1';
      } catch (err) {
          return false;
      }
  }
})(jQuery);
 
