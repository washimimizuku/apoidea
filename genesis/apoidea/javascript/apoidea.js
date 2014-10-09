var CheckValue = false;

function setAllCheckBoxes(formName, fieldName) {
   if(CheckValue) {
      CheckValue = false;
   } else {
      CheckValue = true;
   }
   if(!document.forms[formName]) {
      return;
   }
   var objCheckBoxes = document.forms[formName].elements[fieldName];
   if(!objCheckBoxes) {
      return;
   }
   var countCheckBoxes = objCheckBoxes.length;
   if(!countCheckBoxes) {
      objCheckBoxes.checked = CheckValue;
   } else {
      // set the check value for all check boxes
      for(var i = 0; i < countCheckBoxes; i++) {
         objCheckBoxes[i].checked = CheckValue;
      }
   }
}

function addToList(formName, text, list) {
   var textObject = document.forms[formName].elements[text];
   var listObject = document.forms[formName].elements[list];

   if (textObject.value != "") {
      if (listObject.selectedIndex > -1){
         listObject.options[listObject.selectedIndex].text = textObject.value;
      } else {
         listObject.options[listObject.options.length] = new Option(textObject.value, textObject.value);
      }
      listObject.selectedIndex = -1;
      textObject.value = "";
      textObject.focus();
   }
}
	
function removeFromList(formName, list) {
   var listObject = document.forms[formName].elements[list];
   for(var i = 0; i < listObject.length; i++) {
      if(listObject.options[i].selected) {
         listObject.options[i--] = null;
      }
   }
}

function removeFromListAndRecordErase(formName, list, text) {
   var listObject = document.forms[formName].elements[list];
   var textObject = document.forms[formName].elements[text];
   var totalErased = textObject.value;
   
   for(var i = 0; i < listObject.length; i++) {
      if(listObject.options[i].selected) {
         totalErased = totalErased+listObject.options[i].value+";";
         listObject.options[i--] = null;
      }
   }
   //alert (totalErased);
   //totalErased = totalErased.slice(0,-1);
   //alert (totalErased);
   textObject.value = totalErased;
}

function moveUp(formName, list) {
   var listObject = document.forms[formName].elements[list];
   var index = listObject.selectedIndex;
   
   if (index > 0) {
      var text1 = listObject.options[index-1].text;
      var text2 = listObject.options[index].text;
      var value1 = listObject.options[index-1].value;
      var value2 = listObject.options[index].value;
      listObject.options[index].text = text1;
      listObject.options[index-1].text = text2;
      listObject.options[index].value = value1;
      listObject.options[index-1].value = value2;
      listObject.selectedIndex = index-1;
   }
}

function moveDown(formName, list) {
   var listObject = document.forms[formName].elements[list];
   var index = listObject.selectedIndex;
   var size = listObject.length;
   
   if ((index < size - 1) && (index != -1)) {
      var text1 = listObject.options[index].text;
      var text2 = listObject.options[index+1].text;
      var value1 = listObject.options[index].value;
      var value2 = listObject.options[index+1].value;
      listObject.options[index+1].text = text1;
      listObject.options[index].text = text2;
      listObject.options[index+1].value = value1;
      listObject.options[index].value = value2;
      listObject.selectedIndex = index+1;
   }
}
	
function submitListForm(formName, list, resultValue, resultText) {
   var listObject = document.forms[formName].elements[list];
   var totalValue='';
   var totalText='';
   for(var i = 0; i < listObject.length; i++) {
      totalValue = totalValue + listObject.options[i].value + ';';
      totalText = totalText + listObject.options[i].text + ';';
   }
   totalValue = totalValue.slice(0,-1);
   totalText = totalText.slice(0,-1);
   document.forms[formName].elements[resultValue].value = totalValue;
   document.forms[formName].elements[resultText].value = totalText;
   document.forms[formName].submit();
}


