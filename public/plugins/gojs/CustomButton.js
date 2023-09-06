'use strict';

go.GraphObject.defineBuilder('CustomButton', function (args) {
  // default colors for 'Button' shape
  var buttonFillNormal = '#F5F5F5';
  var buttonStrokeNormal = '#BDBDBD';
  var buttonFillOver = '#E0E0E0';
  var buttonStrokeOver = '#9E9E9E';
  var buttonFillPressed = '#BDBDBD'; // set to null for no button pressed effects
  var buttonStrokePressed = '#9E9E9E';
  var buttonFillDisabled = '#E5E5E5';

  // padding inside the ButtonBorder to match sizing from previous versions
  var paddingHorizontal = 0;
  var paddingVertical = 0;
  var parameter1 =10;
  var parameter2 =2;

  var button = /** @type {Panel} */ (
    go.GraphObject.make(go.Panel, 'Auto',
      {
        isActionable: true,  // needed so that the ActionTool intercepts mouse events
        enabledChanged: function (btn, enabled) {
          var shape = btn.findObject('ButtonBorder');
          if (shape !== null) {
            shape.fill = enabled ? btn['_buttonFillNormal'] : btn['_buttonFillDisabled'];
          }
        },
        cursor: 'pointer',
        // save these values for the mouseEnter and mouseLeave event handlers
        '_buttonFillNormal': buttonFillNormal,
        '_buttonStrokeNormal': buttonStrokeNormal,
        '_buttonFillOver': buttonFillOver,
        '_buttonStrokeOver': buttonStrokeOver,
        '_buttonFillPressed': buttonFillPressed,
        '_buttonStrokePressed': buttonStrokePressed,
        '_buttonFillDisabled': buttonFillDisabled
      },
      go.GraphObject.make(go.Shape,  // the border
        {
          name: 'ButtonBorder',
          figure: 'RoundedRectangle',
          spot1: new go.Spot(0, 0, paddingHorizontal, paddingVertical),
          spot2: new go.Spot(1, 1, -paddingHorizontal, -paddingVertical),
          parameter1: parameter1,
          parameter2: parameter2,
          fill: buttonFillNormal,
          stroke: buttonStrokeNormal
        }
      )
    )
  );

  // There's no GraphObject inside the button shape -- it must be added as part of the button definition.
  // This way the object could be a TextBlock or a Shape or a Picture or arbitrarily complex Panel.

  // mouse-over behavior
  button.mouseEnter = function (e, btn, prev) {
    if (!btn.isEnabledObject()) return;
    var shape = btn.findObject('ButtonBorder');  // the border Shape
    if (shape instanceof go.Shape) {
      var brush = btn['_buttonFillOver'];
      btn['_buttonFillNormal'] = shape.fill;
      shape.fill = brush;
      brush = btn['_buttonStrokeOver'];
      btn['_buttonStrokeNormal'] = shape.stroke;
      shape.stroke = brush;
    }
  };

  button.mouseLeave = function (e, btn, prev) {
    if (!btn.isEnabledObject()) return;
    var shape = btn.findObject('ButtonBorder');  // the border Shape
    if (shape instanceof go.Shape) {
      shape.fill = btn['_buttonFillNormal'];
      shape.stroke = btn['_buttonStrokeNormal'];
    }
  };

  // mousedown/mouseup behavior
  button.actionDown = function (e, btn) {
    if (!btn.isEnabledObject()) return;
    if (btn['_buttonFillPressed'] === null) return;
    if (e.button !== 0) return;
    var shape = btn.findObject('ButtonBorder');  // the border Shape
    if (shape instanceof go.Shape) {
      var diagram = e.diagram;
      var oldskip = diagram.skipsUndoManager;
      diagram.skipsUndoManager = true;
      var brush = btn['_buttonFillPressed'];
      btn['_buttonFillOver'] = shape.fill;
      shape.fill = brush;
      brush = btn['_buttonStrokePressed'];
      btn['_buttonStrokeOver'] = shape.stroke;
      shape.stroke = brush;
      diagram.skipsUndoManager = oldskip;
    }
  };

  button.actionUp = function (e, btn) {
    if (!btn.isEnabledObject()) return;
    if (btn['_buttonFillPressed'] === null) return;
    if (e.button !== 0) return;
    var shape = btn.findObject('ButtonBorder');  // the border Shape
    if (shape instanceof go.Shape) {
      var diagram = e.diagram;
      var oldskip = diagram.skipsUndoManager;
      diagram.skipsUndoManager = true;
      if (overButton(e, btn)) {
        shape.fill = btn['_buttonFillOver'];
        shape.stroke = btn['_buttonStrokeOver'];
      } else {
        shape.fill = btn['_buttonFillNormal'];
        shape.stroke = btn['_buttonStrokeNormal'];
      }
      diagram.skipsUndoManager = oldskip;
    }
  };

  button.actionCancel = function (e, btn) {
    if (!btn.isEnabledObject()) return;
    if (btn['_buttonFillPressed'] === null) return;
    var shape = btn.findObject('ButtonBorder');  // the border Shape
    if (shape instanceof go.Shape) {
      var diagram = e.diagram;
      var oldskip = diagram.skipsUndoManager;
      diagram.skipsUndoManager = true;
      if (overButton(e, btn)) {
        shape.fill = btn['_buttonFillOver'];
        shape.stroke = btn['_buttonStrokeOver'];
      } else {
        shape.fill = btn['_buttonFillNormal'];
        shape.stroke = btn['_buttonStrokeNormal'];
      }
      diagram.skipsUndoManager = oldskip;
    }
  };

  button.actionMove = function (e, btn) {
    if (!btn.isEnabledObject()) return;
    if (btn['_buttonFillPressed'] === null) return;
    var diagram = e.diagram;
    if (diagram.firstInput.button !== 0) return;
    diagram.currentTool.standardMouseOver();
    if (overButton(e, btn)) {
      var shape = btn.findObject('ButtonBorder');
      if (shape instanceof go.Shape) {
        var oldskip = diagram.skipsUndoManager;
        diagram.skipsUndoManager = true;
        let brush = btn['_buttonFillPressed'];
        if (shape.fill !== brush) shape.fill = brush;
        brush = btn['_buttonStrokePressed'];
        if (shape.stroke !== brush) shape.stroke = brush;
        diagram.skipsUndoManager = oldskip;
      }
    }
  };

  function overButton(e, btn) {
    var over = e.diagram.findObjectAt(
      e.documentPoint,
      function (x) {
        while (x.panel !== null) {
          if (x.isActionable) return x;
          x = x.panel;
        }
        return x;
      },
      function (x) { return x === btn; }
    );
    return over !== null;
  }

  return button;
});