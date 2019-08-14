/**
* jQuery org-chart/tree plugin.
*
* Author: Wes Nolte
* http://twitter.com/wesnolte
*
* Based on the work of Mark Lee
* http://www.capricasoftware.co.uk
*
* Copyright (c) 2011 Wesley Nolte
* Dual licensed under the MIT and GPL licenses.
*
*/
(function(jQuery) {

  jQuery.fn.jOrgChart = function(options) {
    var opts = jQuery.extend({}, jQuery.fn.jOrgChart.defaults, options);
    var jQueryappendTo = jQuery(opts.chartElement);

    // build the tree
    jQuerythis = jQuery(this);
    var jQuerycontainer = jQuery("<div class='" + opts.chartClass + "'/>");
    if(jQuerythis.is("ul")) {
      buildNode(jQuerythis.find("li:first"), jQuerycontainer, 0, opts);
    }
    else if(jQuerythis.is("li")) {
      buildNode(jQuerythis, jQuerycontainer, 0, opts);
    }
    jQueryappendTo.append(jQuerycontainer);

    // add drag and drop if enabled
    if(opts.dragAndDrop){
        jQuery('div.node').draggable({
            cursor : 'move',
            distance : 40,
            helper : 'clone',
            opacity : 0.8,
            revert : 'invalid',
            revertDuration : 100,
            snap : 'div.node.expanded',
            snapMode : 'inner',
            stack : 'div.node'
        });
        
        jQuery('div.node').droppable({
            accept : '.node',
            activeClass : 'drag-active',
            hoverClass : 'drop-hover'
        });
        
      // Drag start event handler for nodes
      jQuery('div.node').bind("dragstart", function handleDragStart( event, ui ){
        
        var sourceNode = jQuery(this);
        sourceNode.parentsUntil('.node-container')
                   .find('*')
                   .filter('.node')
                   .droppable('disable');
      });

      // Drag stop event handler for nodes
      jQuery('div.node').bind("dragstop", function handleDragStop( event, ui ){

        /* reload the plugin */
        jQuery(opts.chartElement).children().remove();
        jQuerythis.jOrgChart(opts);
      });
    
      // Drop event handler for nodes
      jQuery('div.node').bind("drop", function handleDropEvent( event, ui ) {

        var targetID = jQuery(this).data("tree-node");
        var targetLi = jQuerythis.find("li").filter(function() { return jQuery(this).data("tree-node") === targetID; } );
        var targetUl = targetLi.children('ul');

        var sourceID = ui.draggable.data("tree-node");	
        var sourceLi = jQuerythis.find("li").filter(function() { return jQuery(this).data("tree-node") === sourceID; } );	
        var sourceUl = sourceLi.parent('ul');

        if (targetUl.length > 0){
   targetUl.append(sourceLi);
        } else {
   targetLi.append("<ul></ul>");
   targetLi.children('ul').append(sourceLi);
        }
        
        //Removes any empty lists
        if (sourceUl.children().length === 0){
          sourceUl.remove();
        }

      }); // handleDropEvent
        
    } // Drag and drop
  };

  // Option defaults
  jQuery.fn.jOrgChart.defaults = {
    chartElement : 'body',
    depth : -1,
    chartClass : "jOrgChart",
    dragAndDrop: false
  };

  var nodeCount = 0;
  // Method that recursively builds the tree
  function buildNode(jQuerynode, jQueryappendTo, level, opts) {
  	var elementId 	= (jQuerynode.attr('id'));
    var jQuerytable = jQuery("<table cellpadding='0' cellspacing='0' border='0' id='magemlm_structure'/>");
    var jQuerytbody = jQuery("<tbody/>");

    // Construct the node container(s)
    var jQuerynodeRow = jQuery("<tr/>").addClass("node-cells");
    var jQuerynodeCell = jQuery("<td/>").addClass("node-cell").attr("colspan", 2);
    var jQuerychildNodes = jQuerynode.children("ul:first").children("li");
    var jQuerynodeDiv;
    
    if(jQuerychildNodes.length > 1) {
      jQuerynodeCell.attr("colspan", jQuerychildNodes.length * 2);
    }
    // Draw the node
    // Get the contents - any markup except li and ul allowed
    var jQuerynodeContent = jQuerynode.clone()
                            .children("ul,li")
                            .remove()
                            .end()
                            .html();

      //Increaments the node count which is used to link the source list and the org chart
   nodeCount++;
   jQuerynode.data("tree-node", nodeCount);
   jQuerynodeDiv = jQuery("<div>").addClass("node")
                                     .data("tree-node", nodeCount)
                                     .attr('id',  "customer_"+elementId)
                                     .append(jQuerynodeContent);

    // Expand and contract nodes
    if (jQuerychildNodes.length > 0) {
      jQuerynodeDiv.click(function() {
          var jQuerythis = jQuery(this);
          var jQuerytr = jQuerythis.closest("tr");

          if(jQuerytr.hasClass('contracted')){
            jQuerythis.css('cursor','n-resize');
            jQuerytr.removeClass('contracted').addClass('expanded');
            jQuerytr.nextAll("tr").show(500);
            
            jQuerytr.find('.node-cell').find('.node').addClass('expanded');

            // Update the <li> appropriately so that if the tree redraws collapsed/non-collapsed nodes
            // maintain their appearance
            jQuerynode.removeClass('collapsed');
          }else{
            jQuerythis.css('cursor','s-resize'); 
            jQuerytr.removeClass('expanded').addClass('contracted');
            jQuerytr.nextAll("tr").hide(500);
            jQuerynode.addClass('collapsed');
          }
        });
    }
    
    jQuerynodeCell.append(jQuerynodeDiv);
    jQuerynodeRow.append(jQuerynodeCell);
    jQuerytbody.append(jQuerynodeRow);

    if(jQuerychildNodes.length > 0) {
      // if it can be expanded then change the cursor
      jQuerynodeDiv.css('cursor','n-resize');
    
      // recurse until leaves found (-1) or to the level specified
      if(opts.depth == -1 || (level+1 < opts.depth)) {
        var jQuerydownLineRow = jQuery("<tr/>");
        var jQuerydownLineCell = jQuery("<td/>").attr("colspan", jQuerychildNodes.length*2);
        jQuerydownLineRow.append(jQuerydownLineCell);
        
        // draw the connecting line from the parent node to the horizontal line
        jQuerydownLine = jQuery("<div></div>").addClass("struc_line struc_down");
        jQuerydownLineCell.append(jQuerydownLine);
        jQuerytbody.append(jQuerydownLineRow);

        // Draw the horizontal lines
        var jQuerylinesRow = jQuery("<tr/>");
        jQuerychildNodes.each(function() {
          var jQueryleft = jQuery("<td>&nbsp;</td>").addClass("struc_line struc_left struc_top");
          var jQueryright = jQuery("<td>&nbsp;</td>").addClass("struc_line struc_right struc_top");
          jQuerylinesRow.append(jQueryleft).append(jQueryright);
        });

        // horizontal line shouldn't extend beyond the first and last child branches
        jQuerylinesRow.find("td:first")
                    .removeClass("struc_top")
                 .end()
                 .find("td:last")
                    .removeClass("struc_top");

        jQuerytbody.append(jQuerylinesRow);
        var jQuerychildNodesRow = jQuery("<tr/>");
        jQuerychildNodes.each(function() {
           var jQuerytd = jQuery("<td class='node-container'/>");
           jQuerytd.attr("colspan", 2);
           // recurse through children lists and items
           buildNode(jQuery(this), jQuerytd, level+1, opts);
           jQuerychildNodesRow.append(jQuerytd);
        });

      }
      jQuerytbody.append(jQuerychildNodesRow);
    }

    // any classes on the LI element get copied to the relevant node in the tree
    // apart from the special 'collapsed' class, which collapses the sub-tree at this point
    if (jQuerynode.attr('class') != undefined) {
        var classList = jQuerynode.attr('class').split(/\s+/);
        jQuery.each(classList, function(index,item) {
            if (item == 'collapsed') {
                console.log(jQuerynode);
                jQuerynodeRow.nextAll('tr').css('visibility', 'hidden');
                    jQuerynodeRow.removeClass('expanded');
                    jQuerynodeRow.addClass('contracted');
                    jQuerynodeDiv.css('cursor','s-resize');
            } else {
                jQuerynodeDiv.addClass(item);
            }
        });
    }

    jQuerytable.append(jQuerytbody);
    jQueryappendTo.append(jQuerytable);
    
    /* Prevent trees collapsing if a link inside a node is clicked */
    jQuerynodeDiv.children('a').click(function(e){
        console.log(e);
        e.stopPropagation();
    });
  };

})(jQuery);