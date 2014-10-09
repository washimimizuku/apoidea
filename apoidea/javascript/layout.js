window.onload = function() {
   list = document.getElementById("depot_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
   
   list = document.getElementById("header_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
   
   var list = document.getElementById("left_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
   
   list = document.getElementById("center_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
   
   list = document.getElementById("right_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
   
   list = document.getElementById("footer_box");
   DragDrop.makeListContainer( list, 'g1' );
   list.onDragOver = function() { this.style["background"] = "#FFFFBB"; };
   list.onDragOut = function() { this.style["background"] = "#FFFFDD"; };
};

function getSort()
{
   order = document.getElementById("order");
   order.value = DragDrop.serData('g1', null);
}

/*function showValue()
{
   order = document.getElementById("order");
   alert(order.value);
}*/

