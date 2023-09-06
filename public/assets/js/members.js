$(function (){

  /**** download ics file ***/
  $('body').on('click', '.download-event-ics', function() {
    window.location.href = $(this).data('url');
    return true;
  });

  /**
   * Get member tree data.
   */
  addBootstrapAjaxLoader($('#myDiagramDiv'), 300);
  $.get(memberGetTreeData,  function (response){
    init(response);
    var time = 0;
    if (window.matchMedia("(max-width: 767px)").matches) {
      time = 3000;
    }
    setTimeout(() => {
      $('.bootstrap-loader-main').remove();
      $('#content').css('overflow','');
    }, time);
  });

  /**
   * Tree
   */
  function init(nodeDataArray) {
    var $ = go.GraphObject.make;  // for conciseness in defining templates
    var roundedRectangleParams = {
      parameter1: 2,  // set the rounded corner
      spot1: go.Spot.TopLeft, spot2: go.Spot.BottomRight  // make content go all the way to inside edges of rounded corners
    };

    myDiagram =
      $(go.Diagram, "myDiagramDiv",  // must be the ID or reference to div
        {
          "toolManager.hoverDelay": 100,  // 100 milliseconds instead of the default 850
          maxSelectionCount: 1,
          allowCopy: false,
          validCycle: go.Diagram.CycleDestinationTree,
          allowDrop: false,
          layout:  // create a TreeLayout for the family tree
            $(go.TreeLayout,
              { angle: 90, nodeSpacing: 100, layerSpacing: 40, layerStyle: go.TreeLayout.LayerUniform })
        });

    // get tooltip text from the object's data
    function tooltipTextConverter(person) {
      var str = "";
      str += "Name: " + person.name;
      str += "\nEmail: " + person.email;
      return str;
    }

    // define tooltips for nodes
    var tooltiptemplate =
      $("ToolTip",
        { "Border.fill": "whitesmoke", "Border.stroke": "black" },
        $(go.TextBlock,
          {
            font: "bold 8pt Helvetica, bold Arial, sans-serif",
            wrap: go.TextBlock.WrapFit,
            margin: 5
          },
          new go.Binding("text", "", tooltipTextConverter))
      );

    function findHeadShot(profile_image) {
      if (!profile_image) return "/assets/images/profile-icon-large.png"; // There are only 16 images on the server
      return "/storage/" + profile_image;
    }

    myDiagram.defaultCursor = "pointer";

    function textStyle(field) {
      return [
        {
          font: "17px Roboto, sans-serif", stroke: "rgba(0, 0, 0, 1)",
          visible: false,  // only show textblocks when there is corresponding data for them,
          alignment: go.Spot.Center
        },
        new go.Binding("visible", field, function(val) { return val !== undefined; })
      ];
    }

    function textBoldStyle(field) {
      return [
        {
          font: "bold 18px Roboto, bold sans-serif", stroke: "rgba(0, 0, 0, 1)",
          visible: false,  // only show textblocks when there is corresponding data for them,
          alignment: go.Spot.Center
        },
        new go.Binding("visible", field, function(val) { return val !== undefined; })
      ];
    }

    function mayWorkFor(node1, node2) {
      if (!(node1 instanceof go.Node)) return false;  // must be a Node
      if (node1 === node2) return false;  // cannot work for yourself
      if (node2.isInTreeOf(node1)) return false;  // cannot work for someone who works for you
      return true;
    }

    function updateParentRemote(selectionId, targetId){
      jQuery.post(parentUpdateRoute, {selectionId, targetId}, function (response){
        if(response.message){
          alert(response.message);
        }
        window.location.reload();
      });
    }

    myDiagram.toolManager.draggingTool.doActivate = function() {
      go.DraggingTool.prototype.doActivate.call(this);
      this.currentPart.selectionAdorned = false;
      this.currentPart.layerName = "Foreground";
    }
    myDiagram.toolManager.draggingTool.doDeactivate = function() {
      this.currentPart.selectionAdorned = true;
      this.currentPart.layerName = "";
      go.DraggingTool.prototype.doDeactivate.call(this);
    }

    // replace the default Node template in the nodeTemplateMap
    myDiagram.nodeTemplate =
      $(go.Node, "Vertical",
        { selectionAdorned: true},
        {
          mouseDragEnter: function(e, node, prev) {
            var diagram = node.diagram;
            var selnode = diagram.selection.first();
            if (!mayWorkFor(selnode, node)) return;
          },
          mouseDrop: function(e, node) {
            var diagram = node.diagram;
            var selnode = diagram.selection.first();
            if (mayWorkFor(selnode, node)) {
              updateParentRemote(selnode.data.id, node.data.id);
              var link = selnode.findTreeParentLink();
              if (link !== null) {
                link.fromNode = node;
              } else {
                diagram.toolManager.linkingTool.insertLink(node, node.port, selnode, selnode.port);
              }
            }
          }
        },
        $(go.Panel, "Vertical",
          $(go.Panel, "Horizontal",
            $(go.Panel, "Spot",
              $(go.Picture,
                { width: 90, height: 90,
                  imageStretch: go.GraphObject.UniformToFill
                },
                new go.Binding("opacity", "opacity", function (opacity) {
                  return opacity;
                }),
                { sourceCrossOrigin: function(pict) { return "use-credentials"; } },
                new go.Binding("source", "profile_image", findHeadShot)
              ),
              $(go.Shape, {
                  geometryString: "F M0 0 L100 0 L100 100 L0 100 z M5,50 a45,45 0 1,0 90,0 a45,45 0 1,0 -90,0 z",
                  desiredSize: new go.Size(95, 95),
                  strokeWidth: 0,
                  fill: "rgb(242, 242, 242)"
                },
              ),
              $(go.Shape, "Circle",
                { desiredSize: new go.Size(90, 90), fill: "transparent", strokeWidth: 5 },
                new go.Binding("stroke",  "isHighlighted", function(h) { return h ? "rgb(50, 50, 50)" : "rgb(242, 242, 242)"; }).ofObject()
              ),
            )
          ),
          $(go.TextBlock,
            { row: 1, stroke: "#000",  margin: 10},
            new go.Binding("text", "name"),
            new go.Binding("font",  "isHighlighted", function(h) { return h ? "Bold 11pt sans-serif" : "11pt sans-serif"; }).ofObject()
          ),
        ),
      );

    myDiagram.nodeTemplate.selectionAdornmentTemplate =
      $(go.Adornment, "Spot",
        {defaultAlignment: go.Spot.Bottom},
        $(go.Panel, "Auto",
          $(go.Shape, { stroke: "dodgerblue", strokeWidth: 0, fill: null }),
          $(go.Placeholder)
        ),
        $(go.Panel, "Vertical",
          {width: 300,},
          $(go.Panel, "Table",
            {
              margin: new go.Margin(180,0,0,0),
              name: "INFO",  // identify to the PanelExpanderButton
              stretch: go.GraphObject.Horizontal,  // take up whole available width
              defaultAlignment: go.Spot.Top,  // thus no need to specify alignment on each element,
            },
            $(go.Shape, "TriangleUp",
              { row: 0, width: 20, height: 10, fill: "white" , strokeWidth: 5, stroke: "white", strokeJoin: "round", strokeCap: "butt", alignment: go.Spot.Center}),
            $(go.Shape, "RoundedRectangle", roundedRectangleParams,
              { row: 1, name: "SHAPE", strokeWidth: 0, fill: "#fff",width: 300, parameter1: 10, height: 270},
            ),
            $(go.Panel, "Table",
              {row: 1, defaultAlignment: go.Spot.Center},
              $(go.Picture,
                {width: 30, height: 30, source: '/assets/images/shape/hierarchy-structure.png',isActionable: true,
                  alignment: go.Spot.TopLeft, margin: new go.Margin(15, 0, 0, 0), click: function(e, obj) {
                    changeTreeLevel(obj.part.data);
                  }
                }
              ),
              $(go.Picture,
                {width: 20, height: 20, source: '/assets/images/shape/yellow-star.png',isActionable: true,
                  alignment: go.Spot.TopRight, margin: new go.Margin(15, 0, 0, 0), click: function(e, obj) {
                    removeFavourite(obj.part.data);
                  }},
                { sourceCrossOrigin: function(pict) { return "use-credentials"; } },
                new go.Binding('visible', 'is_favourite', function (is_favourite){return is_favourite === 1;}),
              ),
              $(go.Picture,
                {width: 20, height: 20, source: '/assets/images/shape/white-star.png',isActionable: true,
                  alignment: go.Spot.TopRight, margin: new go.Margin(15, 0, 0, 0), click: function(e, obj) {
                      addFavourite(obj.part.data);
                  }},
                { sourceCrossOrigin: function(pict) { return "use-credentials"; } },
                new go.Binding('visible', 'is_favourite', function (is_favourite){return is_favourite === 0;}),
              ),
              $(go.Panel, "Spot",
                {row: 0, columnSpan: 3, margin: new go.Margin(15, 0, 0, 0)},
                $(go.Picture,
                    { width: 100, height: 100, imageStretch: go.GraphObject.UniformToFill},
                    { sourceCrossOrigin: function(pict) { return "use-credentials"; } },
                    new go.Binding("source", "profile_image", findHeadShot),
                    new go.Binding("opacity", "opacity", function (opacity) {
                      return opacity;
                    })
                ),
                $(go.Shape, {
                    geometryString: "F M0 0 L100 0 L100 100 L0 100 z M5,50 a45,45 0 1,0 90,0 a45,45 0 1,0 -90,0 z",
                    desiredSize: new go.Size(105, 105),
                    strokeWidth: 0,
                    fill: "white"
                  },
                ),
              ),
              $(go.TextBlock, textBoldStyle("name"),
                  {row: 1,columnSpan: 3, margin: new go.Margin(17, 0, 0, 0)},
                  new go.Binding("text", "name")
              ),
              $(go.Panel, "Horizontal",
                { row: 2,columnSpan: 3, margin: new go.Margin(15, 0, 0, 0) },
                $(go.TextBlock,
                  { text: "\ue98a", font: "16px feather", margin: new go.Margin(0, 5, 0, 0), stroke: '#56B2FF' }),
                $(go.TextBlock, textStyle("email"),
                  new go.Binding("text", "email")
                )
              ),
              $("CustomButton",
                  {
                    row: 3, columnSpan: 3,
                    width: 200,
                    height: 42,
                    margin: new go.Margin(10, 0, 0, 0),

                    "ButtonBorder.fill": "#56B2FF",
                    "ButtonBorder.stroke": "#56B2FF",
                    "_buttonStrokeOver": "#56B2FF",
                    "_buttonStrokePressed": "#56B2FF",
                    "_buttonFillOver": "white",
                    "_buttonFillPressed": "white",
                    click: function(e, button) {
                      window.location.href = button.url;
                    }
                  },
                  new go.Binding("url", "id", function (id){
                      return profileRoute.replace("#id#", id);
                  }),
                  new go.Binding("visible", "hide_profile"),
                  $(go.TextBlock,
                    {
                      margin:  new go.Margin(0, 0, 0, 0),
                      stroke: "white",
                      font: "bold 14px Roboto, sans-serif",
                      stretch: go.GraphObject.Fill,
                      textAlign: "center",
                      verticalAlignment: go.Spot.Center,
                      mouseEnter: function (e, obj){
                        obj.stroke = "#56B2FF";
                      },
                      mouseLeave: function (e, obj){
                        obj.stroke = "white";
                      }
                    },
                    new go.Binding("text", "name", function (name) { return viewProfileText + ' ' + name.split(' ')[0]})
                  )
                ),
            ),
          )
        )
      );

    // define the Link template
    myDiagram.linkTemplate =
      $(go.Link,  // the whole link panel
        { routing: go.Link.Orthogonal, corner: 5, selectable: false },
        $(go.Shape, { strokeWidth: 2, stroke: '#6D6D6DFF' }));  // the gray link shape

    // create the model for the family tree
    myDiagram.model = $(go.TreeModel, {
      nodeKeyProperty: "id",
      nodeParentKeyProperty: 'parent_id',
      nodeDataArray: nodeDataArray
    });

    document.getElementById('zoomOut').addEventListener('click', function() {
      myDiagram.commandHandler.decreaseZoom(0.5);
    });

    document.getElementById('zoomIn').addEventListener('click', function() {
      myDiagram.commandHandler.increaseZoom(1.5);
    });

    document.getElementById('zoomToFit').addEventListener('click', function() {
      myDiagram.commandHandler.zoomToFit();
    });

    //favourite
    function addFavourite(data){
      let node =myDiagram.findNodeForKey(data.id);
      let array = node.data;
      myDiagram.model.commit(function(m) {
          m.set(array, "is_favourite", 1);
      }, "is_favourite");
      myDiagram.clearSelection();
      myDiagram.select(node);
      jQuery.post(addFavouriteRoute, {member_id: data.id}, function (response){
        if(!response?.success){
          myDiagram.model.commit(function(m) {
            m.set(array, "is_favourite", 0);
          }, "is_favourite");
          myDiagram.clearSelection();
          myDiagram.select(node);
        }
      });
    }

    function removeFavourite(data){
      let node = myDiagram.findNodeForKey(data.id);
      let array = node.data;
      myDiagram.model.commit(function(m) {
        m.set(array, "is_favourite", 0);
      }, "is_favourite");
      myDiagram.clearSelection();
      myDiagram.select(node);
      jQuery.post(removeFavouriteRoute, {member_id: data.id}, function (response){
        if(!response?.success){
          myDiagram.model.commit(function(m) {
            m.set(array, "is_favourite", 1);
          }, "is_favourite");
          myDiagram.clearSelection();
          myDiagram.select(node);
        }
      });
    }
  }

  function changeTreeLevel(data){
    var id = data.id;
    myDiagram.div = null;
    myDiagram = null;
    addBootstrapAjaxLoader($('#myDiagramDiv'), 300);
    jQuery.get(memberGetTreeData+"?id="+id,  function (response){
      init(response);
      var time = 0;
      if (window.matchMedia("(max-width: 767px)").matches) {
        time = 3000;
      }
      setTimeout(() => {
        jQuery('.bootstrap-loader-main').remove();
        jQuery('#content').css('overflow','');
      }, time);
    });
  }

  $("#treeSearchBtn").click(function(){
    let member_id = $('#member_id').val();
    if(member_id && member_id != undefined) {
      let data = {
        'id' : member_id
      };
      changeTreeLevel(data);
      
      setTimeout(function () {
        searchUser();
      }, 1000);

      $('.member_list').empty();

      setTimeout(function () {
        $("#tree_search_users").val('');
      }, 2000);
    }
  });

  /**
   * Search people
   */
  $('body').on('keyup', '#tree_search_users', function (e) {
    clearTimeout(typingTimer);
    $('.member_list').empty();
    currentPeoplePage = 1;
    if(event.keyCode === 13) {
      get_member_list(currentPeoplePage);
    } else {
      typingTimer = setTimeout(get_member_list, doneTypingInterval);
    }
  });

  /**
   * Get member listing
   */
  function get_member_list(pageNumber) {
    var search_people_text = $('#tree_search_users').val();
    $.ajax({
      type: "get",
      url: searchedPeopleRoute,
      data: {'search_text' : search_people_text, 'page': pageNumber},
      dataType: "json",
      success:function(data) {
        if(data.success) {
          $('.member_list').append(data.html);
        } else {
          $('.member_list').append('');
        }
      },
      error: function (data) {
        $('.member_list').append('');
      }
    });
  }

  /**
   * Call function on people scroll
   */
  function getPeopleList() {
    var scrollContainer = $('#member_list_div'); // Select 
    var scrollHeight = scrollContainer.prop('scrollHeight');
    var scrollTop = scrollContainer.scrollTop();
    var containerHeight = scrollContainer.height();
    if (scrollHeight - scrollTop === containerHeight) {
      currentPeoplePage++;
      get_member_list(currentPeoplePage);
    }
  }

  function searchUser() {
    var input = document.getElementById("tree_search_users");
    if (!input) return;
    myDiagram.focus();
    myDiagram.startTransaction("highlight search");
    $('#print-error-msg-search-member').hide();
    if (input.value) {
      var safe = input.value.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
      var regex = new RegExp(safe, "i");
      var results = myDiagram.findNodesByExample({ name: regex }, { email: regex });
      myDiagram.highlightCollection(results);
      if (results.count > 0) myDiagram.centerRect(results.first().actualBounds);
      if(results.count == 0){
        $('#print-error-msg-search-member').show();
      }
    } else {
      myDiagram.clearHighlighteds();
    }

    myDiagram.commitTransaction("highlight search");
  }

  $('.qr-code').click(function (){
    $('#qrCode').modal('show');
  })

  /**
   * Book a call
   */
  if ($("#book_call_form").length) {
    $('#book_call_button').click(() => {
      $('#book-a-call').modal('show');
    });
    $("#book_call_form").submit(function(event) {
      event.preventDefault();
      let form = $(this);
      let url = form.attr('action');
      let formData = new FormData(this);

      $.ajax({
        type: 'POST',
        url: url,
        data: formData,
        cache: false,
        processData: false,
        contentType: false,
        success: function(data) {
          if ($.isEmptyObject(data.errors)) {
            if (data.success) {
              window.location.reload();
            }
          } else {
            printErrorMsg(data.errors);
          }
        },
        error: function(data) {
          printErrorMsg(data.responseJSON.errors);
        }
      });
    });
  }
});
