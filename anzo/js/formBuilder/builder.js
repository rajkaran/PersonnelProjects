	
	
	
	//this function places the toolbox at the top of particular element
	function placeToolbox(canvasId, id, toolboxClass, toolBoxElementData){
		var elementHeight =  $("#"+id).height();
		var elementYAxis =  $("#"+id).position().top;
		var elementXAxis =  $("#"+id).position().left;
		$("#"+canvasId).append(toolBoxElementData);
		$("."+toolboxClass).css({"display":"block", "position":"absolute", 
						"top":elementYAxis-22+"px", "left":elementXAxis+"px"});
	}
	
	//this function retrieves the attribute values of particular element
	function getElementAttr(id, callback){
		var attrValuesArray = Array();
		var fontSize;
		var regex = new RegExp('px', 'g');
		
		attrValuesArray[0] = $("#"+id).position().left;
		attrValuesArray[1] = $("#"+id).position().top;
		
		var width = $("#"+id).css("width");
		attrValuesArray[2] = width.replace(regex, '');
		var height = $("#"+id).css("height");
		attrValuesArray[3] = height.replace(regex, '');
		
		attrValuesArray[4] = $("#"+id).attr("name");
		attrValuesArray[5] = $("#"+id).css("font-family");
		
		fontSize = $("#"+id).css("font-size");
		attrValuesArray[6] = fontSize.replace(regex, '').split(",");
		
		attrValuesArray[7] = $("#"+id).css("font-weight");
		attrValuesArray[8] = $("#"+id).attr("type");
		attrValuesArray[9] = $("#"+id).attr("required");
		attrValuesArray[10] = $("#"+id).attr("checked");
		attrValuesArray[11] = $("#"+id).attr("multiple");
		attrValuesArray[12] = $("#"+id).attr("autofocus");
		attrValuesArray[13] = $("#"+id).attr("size");
		attrValuesArray[14] = "";
		attrValuesArray[15] = $("#"+id).attr("columns");
		attrValuesArray[16] = $("#"+id).attr("rows");
		attrValuesArray[17] = $("#"+id).attr("value");
		attrValuesArray[18] = $("#"+id).attr("data-custom");
		attrValuesArray[19] = $("#"+id).attr("for");
		attrValuesArray[20] = $("#"+id).css("color");
		attrValuesArray[21] = $("#"+id).html();
		
		callback(attrValuesArray);
	}
	
	//This function retrieve distinct names of all the radio buttons and checkboxes
	function getNames(type) {
		var finalArray = Array();
		
		if(finalArray.length == 0)
			finalArray.push(name);
			
		else{
			$("#canvasPanel").$("input[type='"+type+"']").each(function(index, element) {
				var name = $(this).attr("name");
				for(var i=0; i<finalArray.length; i++){
					if(finalArray[i] == name)
						i=finalArray.length;
					else if(finalArray[i] != name && i == finalArray.length-1)
						finalArray.push(name);
				}
			});
		}
	}
	
	//This function creates options with one of them selected
	function createOptionString(type){
		var resultString = "";
		if(type == "text"){
			resultString = "<option value = 'text' selected>Text</option>"
						+"<option value = 'email'>Email</option>"
						+"<option value = 'date'>Date</option>"
						+"<option value = 'number'>Number</option>"
						+"<option value = 'password'>Password</option>";
		}
		else if(type == "email"){
			resultString = "<option value = 'text'>Text</option>"
						+"<option value = 'email' selected>Email</option>"
						+"<option value = 'date'>Date</option>"
						+"<option value = 'number'>Number</option>"
						+"<option value = 'password'>Password</option>";
		}
		else if(type == "password"){
			resultString = "<option value = 'text'>Text</option>"
						+"<option value = 'email'>Email</option>"
						+"<option value = 'date'>Date</option>"
						+"<option value = 'number'>Number</option>"
						+"<option value = 'password' selected>Password</option>";
		}
		else if(type == "number"){
			resultString = "<option value = 'text'>Text</option>"
						+"<option value = 'email'>Email</option>"
						+"<option value = 'date'>Date</option>"
						+"<option value = 'number' selected>Number</option>"
						+"<option value = 'password'>Password</option>";
		}
		else if(type == "date"){
			resultString = "<option value = 'text'>Text</option>"
						+"<option value = 'email'>Email</option>"
						+"<option value = 'date' selected>Date</option>"
						+"<option value = 'number'>Number</option>"
						+"<option value = 'password'>Password</option>";
		}
		return resultString;
	}
	
	/*This function creates thestring of elements that displays the information 
	related to particular element that user wants to edit. 
	*/
	function populateEditPopup(id, callback) {
		var tagName = $("#"+id).get(0).tagName.toLowerCase();
		var popupData = "";
		var popupTitle = "";
		switch(tagName){
			case "select":
				getElementAttr(id, function(attrValuesArray){
					var displayText = Array();
					var values = Array();
					var firstOption = true;
					popupTitle = "Edit Drop Down";
					$("#"+id).children("option").each(function(index, element) {
						if(firstOption == true){
							displayText.push($(this).text());
							values.push($(this).val());
							firstOption = false;
						}
						else{
							displayText.push("\n"+$(this).text());
							values.push("\n"+$(this).val());
						}
						
						if($(this).attr("selected") == "selected")
							attrValuesArray[14] = $(this).text();
					});
					popupData = "<div class = 'textAreaContainer'>"
										+"<label for = 'displayText'>Write options" 
										+"</label>"
										+"<label for = 'values'>Write values"
										+"</label>"
										
										+"<textarea cols = 10 rows = 5 "
										+"id = 'displayText' >"+displayText+"</textarea>"
										+"&nbsp;&nbsp;"
										+"<textarea cols = 10 rows = 5 "
										+" id = 'values' >"+values+"</textarea>"
									+"</div>"
									
									+"<div class = 'textBoxContainer'>"
										+"<label>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
										+id+"' id = 'editElementId' readonly/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Name:&nbsp;&nbsp;<input type = 'text' value = '"
										+attrValuesArray[4]+"' id = 'name'/></label>"
										+"<br/>"
										+"<label>Size:&nbsp;&nbsp;&nbsp;<input type = "
										+"'text' value = '"+attrValuesArray[13]+"' "
										+"id = 'size'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Default:<input type = 'text' value = '"
										+attrValuesArray[14]+"' id = 'default'/></label>"
										+"<br/>"
										+"<label>x Axis:<input type = 'text' value = '"
										+attrValuesArray[0]+"' id = 'left'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>y Axis:&nbsp;&nbsp;<input type = 'text' value = '"
										+attrValuesArray[1]+"' id = 'top'/></label>"
										+"<br/>"
										+"<label>Width:<input type = 'text' value = '"
										+attrValuesArray[2]+"' id = 'width'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Height:&nbsp;<input type = 'text' value = '"
										+attrValuesArray[3]+"' id = 'height'/></label>"
									+"</div>"
									
									+"<div class = 'checkBoxContainer'>";
					
					if(!attrValuesArray[9] == ""){
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'required' checked/>Required</label>";
					}
					else {
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'required' />Required</label>";
					}
					popupData += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
					if(!attrValuesArray[11] == ""){
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'multiple' checked/>Multiple</label>";
					}
					else {
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'multiple' />Multiple</label>";
					}
					popupData += "</div>";
					
					popupData += "<div class = 'errorContainer'>Please correct the following errors:<ul></ul></div>";	
				});
				break;
				
			case "input":
				getElementAttr(id, function(attrValuesArray){
					if(attrValuesArray[8] == "text" || attrValuesArray[8] == "password" 
						|| attrValuesArray[8] == "email" || attrValuesArray[8] == "number"
						|| attrValuesArray[8] == "date"){
						popupTitle = "Edit Text Field";	
						popupData = "<div class = 'textBoxContainer'>"
										+"<label>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
										+id+"' id = 'editElementId' readonly/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Name:&nbsp<input type = 'text' value = '"
										+attrValuesArray[4]+"' id = 'name'/></label>"
										+"<br/>"
										+"<label>Width:<input type = 'text' value = '"
										+attrValuesArray[2]+"' id = 'width'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Height:<input type = 'text' value = '"
										+attrValuesArray[3]+"' id = 'height'/></label>"
										+"<br/>"
										+"<label>x Axis:<input type = 'text' value = '"
										+attrValuesArray[0]+"' id = 'left'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>y Axis:&nbsp;<input type = 'text' value = '"
										+attrValuesArray[1]+"' id = 'top'/></label>"
										+"<br/>"
										+"<label>Font Size:<input type = 'text' value = '"
										+attrValuesArray[6]+"' id = 'fontSize'/></label>"
									+"</div>"
									
									+"<div class = 'checkBoxContainer'>";
									
						if(!attrValuesArray[9] == ""){
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'required' checked/>Required</label>";
						}
						else {
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'required' />Required</label>";
						}
						popupData += "</div>";	
						
						popupData += "<div class = 'dropDownContainer'>"
										+"<label>Type:<select id = 'type'>";
						popupData += createOptionString(attrValuesArray[8]);
						popupData += "</select></label>"
									+"</div>";
					}
					else{
						
						popupData += "<div class = 'textBoxContainer'>"
										+"<label>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
										+id+"' id = 'editElementId' readonly/></label>"
										+"<br/>"
										+"<label>Name:<input type = 'text' value = '"
										+attrValuesArray[4]+"' id = 'name'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>Value:<input type = 'text' value = '"
										+attrValuesArray[17]+"' id = 'value'/></label>"
										+"<br/>"
										+"<label>x Axis:<input type = 'text' value = '"
										+attrValuesArray[0]+"' id = 'left'/></label>"
										+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
										+"<label>y Axis:<input type = 'text' value = '"
										+attrValuesArray[1]+"' id = 'top'/></label>"
									+"</div>"
									
									+"<div class = 'checkBoxContainer'>";
						if(!attrValuesArray[9] == ""){
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'required' checked/>Required</label>";
						}
						else {
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'required' />Required</label>";
						}
						popupData += "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
						if(!attrValuesArray[10] == ""){
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'checked' checked/>Checked</label>";
						}
						else {
							popupData += "<label><input type = 'checkbox' value = 'true' "
							+"id = 'checked' />Checked</label>";
						}
						popupData += "</div>";
						
						if(attrValuesArray[8] == "radio"){
							popupTitle = "Edit Radio Button";
							popupData += "<p>To make set of radio buttons work together"
									  +" you need to give them same name.</p>";
						}
						else
							popupTitle = "Edit Check Box";
					}
					popupData += "<div class = 'errorContainer'>Please correct the following errors:<ul></ul></div>";				
				});
				break;
				
			case "textarea":
				getElementAttr(id, function(attrValuesArray){
					if(attrValuesArray[18] == "true" )
						popupTitle = "Edit Inline Text";
					else popupTitle = "Edit Text Area";
					
					popupData = "<div class = 'textBoxContainer'>"
									+"<label>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+id+"' id = 'editElementId' readonly/></label>"
									+"&nbsp;&nbsp;"
									+"<label>Name:&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+attrValuesArray[4]+"' id = 'name'/></label>"
									+"<br/>"
									+"<label>Rows:&nbsp;<input type = 'text' value = '"
									+attrValuesArray[16]+"' id = 'row'/></label>"
									+"&nbsp;&nbsp;"
									+"<label>Columns:<input type = 'text' value = '"
									+attrValuesArray[15]+"' id = 'column'/></label>"
									+"<br/>"
									+"<label>x Axis:<input type = 'text' value = '"
									+attrValuesArray[0]+"' id = 'left'/></label>"
									+"&nbsp;&nbsp;"
									+"<label>y Axis:&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+attrValuesArray[1]+"' id = 'top'/></label>"
									+"<br/>"
									+"<label>Font Size:<input type = 'text' value = '"
									+attrValuesArray[6]+"' id = 'fontSize'/></label>"
								+"</div>"
								
								+"<div class = 'checkBoxContainer'>";
								
					if(!attrValuesArray[9] == ""){
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'required' checked/>Required</label>";
					}
					else {
						popupData += "<label><input type = 'checkbox' value = 'true' "
						+"id = 'required' />Required</label>";
					}
					popupData += "</div>";
					popupData += "<div class = 'errorContainer'>Please correct the following errors:<ul></ul></div>";	
				});
				break;
				
			case "div":
				getElementAttr(id, function(attrValuesArray){
						popupTitle = "Edit Inline Text";
					
					popupData = "<div class = 'textBoxContainer'>"
									+"<label>Width:&nbsp;<input type = 'text' value = '"
									+attrValuesArray[2]+"' id = 'width'/></label>"
									+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
									+"<label>Height:<input type = 'text' value = '"
									+attrValuesArray[3]+"' id = 'height'/></label>"
									+"<br/>"
									+"<label>x Axis:&nbsp;<input type = 'text' value = '"
									+attrValuesArray[0]+"' id = 'left'/></label>"
									+"&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;"
									+"<label>y Axis:&nbsp;<input type = 'text' value = '"
									+attrValuesArray[1]+"' id = 'top'/></label>"
								+"</div>"

					popupData += "<div class = 'errorContainer'>Please correct the following errors:<ul></ul></div>";	
				});
				break;
				
			case "label":
				getElementAttr(id, function(attrValuesArray){
					popupTitle = "Edit Label";
					popupData = "<div class = 'textBoxContainer'>"
									+"<label>Id:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+id+"' id = 'editElementId' readonly/></label>"
									+"&nbsp;&nbsp;"
									+"<label>For-id:&nbsp;<input type = 'text' value = '"
									+attrValuesArray[19]+"' id = 'forId'/></label>"
									+"<br/>"
									+"<label>Font Size:<input type = 'text' value = '"
									+attrValuesArray[6]+"' id = 'fontSize'/></label>"
									+"&nbsp;&nbsp;"
									+"<label>Colour:<input type = 'text' value = '"
									+attrValuesArray[20]+"' id = 'colour'/></label>"
									+"<br/>"
									+"<label>Width:&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+attrValuesArray[2]+"' id = 'width'/></label>"
									+"&nbsp;&nbsp;&nbsp;"
									+"<label>x Axis:<input type = 'text' value = '"
									+attrValuesArray[0]+"' id = 'left'/></label>"
									+"<br/>"
									+"<label>y Axis:&nbsp;&nbsp;&nbsp;&nbsp;<input type = 'text' value = '"
									+attrValuesArray[1]+"' id = 'top'/></label>"
									+"&nbsp;&nbsp;&nbsp;"
									+"<label>Text:&nbsp;&nbsp;<input type = 'text' value = '"
									+attrValuesArray[21]+"' id = 'labelText'/></label>"
								+"</div>"
								+"<p>To associate this label to some control you need "
								+"to fill the Id of that control in For-id field.</p>";
								
					popupData += "<div class = 'errorContainer'>Please correct the following errors:<ul></ul></div>";			
				});
				break;
		}
		document.getElementById("editPopup").innerHTML = popupData;
		callback(popupTitle);
	}
	
	//this function returns true if string(needle) found in given array(hayStack) otherwise false
	function stringExist(hayStack, needle){
		var result = false;
		for (i = 0; i < hayStack.length && !result; i++) {
		  if (hayStack[i] === needle) {
			result = true;
		  }
		}
		return result;
	}
	
	/*this function finds the element with given name with the exception of its own,
	 if found returns true othewise false. This function accepts onw more flag which 
	 allow it to skip reading aal the checkbose when looking for name of a checkbox 
	 and same for radio button. */
	function nameExist(findName, id, canvasPanel, identity){
		var result = false;
		var count = 1;
		$("#"+canvasPanel+" > input[type='text']").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Text Field";
					}
		});
		if(identity != "rad"){
			$("#"+canvasPanel+" > input[type='radio']").each(function(index, element) {
						if($(this).attr("name") == findName){
							if(id != $(this).attr("id") )
								result = "Radio Button";
						}
			});
		}
		if(identity != "check"){
			$("#"+canvasPanel+" > input[type='checkbox']").each(function(index, element) {
						if($(this).attr("name") == findName){
							if(id != $(this).attr("id") )
								result = "Check Box";
						}
			});
		}
		$("#"+canvasPanel+" > input[type='email']").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Email Text Field";
					}
		});
		$("#"+canvasPanel+" > input[type='number']").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Number Text Field";
					}
		});
		$("#"+canvasPanel+" > input[type='password']").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Password Text Field";
					}
		});
		$("#"+canvasPanel+" > input[type='date']").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Date Text Field";
					}
		});
		$("#"+canvasPanel+" > select").each(function(index, element) {
					if($(this).attr("name") == findName ){
						if(id != $(this).attr("id") )
							result = "Drop Down";
					}
					
		});
		$("#"+canvasPanel+" > textarea").each(function(index, element) {
					if($(this).attr("name") == findName){
						if(id != $(this).attr("id") )
							result = "Text Area";
					}
		});
		return result;
	}
	
	//this function look for the control with the same given value
	function isValueRepeated(findValue, canvasPanel, controlType, id){
		var result = false;
			
			if(controlType == "radio"){
				$("#"+canvasPanel+" > input[type='radio']").each(function(index, element) {
							if($(this).attr("value") == findValue){
								if(id != $(this).attr("id") )
									result = true;
							}
				});
			}
			if(controlType == "checkbox"){
				$("#"+canvasPanel+" > input[type='checkbox']").each(function(index, element) {
							if($(this).attr("value") == findValue){
								if(id != $(this).attr("id") )
									result = true;
							}
				});
			}
		return result;
	}
	
	//this function look for the control with the same given id
	function controlIdExist(findId, canvasPanel){
		var result = false;
		$("#"+canvasPanel+" > input").each(function(index, element) {
					if($(this).attr("id") == findId)
					result = true;
		});
		$("#"+canvasPanel+" > select").each(function(index, element) {
					if($(this).attr("id") == findId)
					result = true;
		});
		$("#"+canvasPanel+" > textarea").each(function(index, element) {
					if($(this).attr("id") == findId)
					result = true;
		});
		return result;
	}

	
	/*this function get the values of elements from a specific type of popup.
	it returns the values of name, size, top, left, displaytext, values,
	fontSize, colour. this also returns whether the crequired, multiple, checked
	are checked*/
	function popupElementValue(containerId, callback) {
		var valuesArray = Array();
		valuesArray[0] = $("#"+containerId).find("#name").val();
		valuesArray[1] = $("#"+containerId).find("#size").val();
		valuesArray[2] = $("#"+containerId).find("#top").val();
		valuesArray[3] = $("#"+containerId).find("#left").val();
		valuesArray[4] = $("#"+containerId).find("#displayText").val();
		valuesArray[5] = $("#"+containerId).find("#values").val();
		valuesArray[6] = $("#"+containerId).find("#fontSize").val();
		valuesArray[7] = $("#"+containerId).find("#colour").val();
		valuesArray[8] = $("#"+containerId).find("#forId").val();
		valuesArray[9] = $("#"+containerId).find("#default").val();
		valuesArray[10] = $("#"+containerId).find("#required").is(":checked");
		valuesArray[11] = $("#"+containerId).find("#multiple").is(":checked");
		valuesArray[12] = $("#"+containerId).find("#checked").is(":checked");
		valuesArray[13] = $("#"+containerId).find("#height").val();
		valuesArray[14] = $("#"+containerId).find("#width").val();
		valuesArray[15] = $("#"+containerId).find("#type").val();
		valuesArray[16] = $("#"+containerId).find("#value").val();
		valuesArray[17] = $("#"+containerId).find("#row").val();
		valuesArray[18] = $("#"+containerId).find("#column").val();
		valuesArray[19] = $("#"+containerId).find("#labelText").val();
		
		callback(valuesArray);
	}
	
	/*This function validates the values entered by user in popup
	function returns string of all the errors occured if validation fails
	or returns true if if entered data is valid*/
	function isValid(inputArray, tagName, elementId){
		//console.log("validating data " + tagName);
		var result = true;
		var errorString = ""; 
		
		if($.trim(inputArray[2]) == "" || $.trim(inputArray[3]) == ""){
			errorString += "<li>Coordinates are missing</li>";
			result = false;
		}
		
		
		if(isNaN($.trim(inputArray[2])) || isNaN($.trim(inputArray[3]))){
			errorString += "<li>Coordinates has to be numeric</li>";
			result = false;
		}
		
		if(tagName == "select"){
			var regex = new RegExp('\n', 'g');
			var displayTextArray = inputArray[4].replace(regex, '').split(",");
			var valuesArray = inputArray[5].replace(regex, '').split(",");
			
			if($.trim(inputArray[13]) == "" || $.trim(inputArray[14]) == ""){
				errorString += "<li>Height or Width are missing</li>";
				result = false;
			}
			
			if($.trim(inputArray[1]) == "" || $.trim(inputArray[0]) == ""){
				errorString += "<li>Size or Name are missing</li>";
				result = false;
			}
			if(parseInt($.trim(inputArray[1])) < 1 ){
				errorString += "<li>Size can't be less than 1</li>";
				result = false;
			}
			if(inputArray[11] == true && $.trim(inputArray[1]) < 2){
				errorString += "<li>For multiple seletion size has to be greater than 1.</li>";
				result = false;
			}
			if($.trim(inputArray[4]) == "" || $.trim(inputArray[5]) == ""){
				errorString += "<li>Drop Down options or values are missing</li>";
				result = false;
			}
			if(displayTextArray.length != valuesArray.length){
				errorString += "<li>Number of Drop Down options or values are not equal</li>";
				result = false;
			}
			if($.trim(inputArray[9]) != "" && !stringExist(displayTextArray, $.trim(inputArray[9]))){
				errorString += "<li>default option must exists in the options</li>";
				result = false;
			}
			
			var controlExist = nameExist($.trim(inputArray[0]), elementId, "canvasPanel", "unknown");
			
			if(controlExist != false ){
				errorString += "<li>You have "+controlExist+" of the same name</li>";
				result = false;
			}
		}
		else if(tagName == "input"){
			var type = $("#"+elementId).attr("type");
			if(type == "text" || type == "password" || type == "email" || type == "number" || type == "date"){
				if($.trim(inputArray[6]) == "" || $.trim(inputArray[0]) == ""){
					errorString += "<li>Font Size or Name are missing</li>";
					result = false;
				}
				if($.trim(inputArray[13]) == "" || $.trim(inputArray[14]) == ""){
					errorString += "<li>Height or Width are missing</li>";
					result = false;
				}
				if(isNaN($.trim(inputArray[6])) || isNaN($.trim(inputArray[13]))
									|| isNaN($.trim(inputArray[14])) ){
					errorString += "<li>Height, Width or Font Size has to be numeric</li>";
					result = false;
				}
				var controlExist = nameExist($.trim(inputArray[0]),elementId, "canvasPanel", "unknown");
				if(controlExist != false ){
					errorString += "<li>You have "+controlExist+" of the same name</li>";
					result = false;
				}
			}
			else{
				if($.trim(inputArray[16]) == "" || $.trim(inputArray[0]) == ""){
					errorString += "<li>Value or Name are missing</li>";
					result = false;
				}
				if(type == "radio" ){
					var controlExist = nameExist($.trim(inputArray[0]), elementId, "canvasPanel", "rad");
					if(controlExist != false ){
						errorString += "<li>You have "+controlExist+" of the same name</li>";
						result = false;
					}
				}
				if(type == "checkbox"){
					var controlExist = nameExist($.trim(inputArray[0]), elementId, "canvasPanel", "check");
					if(controlExist != false ){
						errorString += "<li>You have "+controlExist+" of the same name</li>";
						result = false;
					}
				}
				if(type == "radio" && isValueRepeated($.trim(inputArray[16]), "canvasPanel", "radio", elementId) == true){
					errorString += "<li>There is a radio button with the same value</li>";
					result = false;	
				}
				if(type == "checkbox" && isValueRepeated($.trim(inputArray[16]), "canvasPanel", "checkbox", elementId) == true){
					errorString += "<li>There is a checkbox with the same value</li>";
					result = false;	
				}
				
			}
		}
		else if(tagName == "textarea"){
			if($.trim(inputArray[6]) == "" || $.trim(inputArray[0]) == ""){
				errorString += "<li>Font Size or Name are missing</li>";
				result = false;
			}
			if($.trim(inputArray[17]) == "" || $.trim(inputArray[18]) == ""){
				errorString += "<li>Rows or Columns are missing</li>";
				result = false;
			}
			if(isNaN($.trim(inputArray[6])) || isNaN($.trim(inputArray[17]))
								|| isNaN($.trim(inputArray[18])) ){
				errorString += "<li>Rows, Columns or Font Size has to be numeric</li>";
				result = false;
			}
			var controlExist = nameExist($.trim(inputArray[0]), elementId, "canvasPanel", "unknown");
			if(controlExist != false ){
				errorString += "<li>You have "+controlExist+" of the same name</li>";
				result = false;
			}
		}
		else if(tagName == "div"){
			if($.trim(inputArray[13]) == "" || $.trim(inputArray[14]) == ""){
				errorString += "<li>Width or Height are missing</li>";
				result = false;
			}
			if(isNaN($.trim(inputArray[14]))|| isNaN($.trim(inputArray[13])) ){
				errorString += "<li>Height and Width has to be numeric</li>";
				result = false;
			}
		}
		else if(tagName == "label"){
			if($.trim(inputArray[6]) == "" || $.trim(inputArray[8]) == ""
								|| $.trim(inputArray[14]) == "" 
								|| $.trim(inputArray[19]) == ""){
				errorString += "<li>Font Size, Text, For-Id or Width are missing</li>";
				result = false;
			}
			if(isNaN($.trim(inputArray[6])) || isNaN($.trim(inputArray[14])) ){
				errorString += "<li>Width or Font Size has to be numeric</li>";
				result = false;
			}
			var controlExist = controlIdExist($.trim(inputArray[8]), "canvasPanel");
			if(controlExist == false ){
				errorString += "<li>There is no control with the given Id</li>";
				result = false;
			}
		}
			
		if(result == false)
			return errorString;
		else return result;
	}
	
	function editAttributes(attrValueArray, id, tag){
		
		if(tag == "select"){
			$("#"+id).attr("name",attrValueArray[0]);
			
			$("#"+id).css({"top":attrValueArray[2]+"px", "left":attrValueArray[3]+"px",
						"width":attrValueArray[14]+"px", "height":attrValueArray[13]+"px"});
						
			$("#"+id).attr("size",attrValueArray[1]);
			
			if(attrValueArray[11])
				$("#"+id).attr("multiple",attrValueArray[11]);
			else $("#"+id).removeAttr("multiple",attrValueArray[11]);
			
			if(attrValueArray[10])
				$("#"+id).attr("required",attrValueArray[10]);
			else $("#"+id).removeAttr("required",attrValueArray[10]);
			
			var regex = new RegExp('\n', 'g');
			var displayTextArray = attrValueArray[4].replace(regex, '').split(",");
			var valuesArray = attrValueArray[5].replace(regex, '').split(",");
			var optionsString = "";
			for(var i=0; i<valuesArray.length; i++){
				optionsString += "<option value = '"+valuesArray[i]+"'>"
								+displayTextArray[i]+"</option>";
			}
			
			$("#"+id).html(optionsString);
			if(attrValueArray[9] != "")
			$("#"+id+" option").each(function(index, element) {
                if($(element).html() == attrValueArray[9])
					$(element).attr("selected", "selected");
            });
			
		}
		else if(tag == "input"){
			var type = $("#"+elementId).attr("type");
			if(type == "text" || type == "password" 
				|| type == "email" || type == "number"
				|| type == "date"){
					
				$("#"+id).attr("name",attrValueArray[0]);
				$("#"+id).attr("type",attrValueArray[15]);
				
				$("#"+id).css({"top":attrValueArray[2]+"px", "left":attrValueArray[3]+"px",
						 "width":attrValueArray[14]+"px", "height":attrValueArray[13]+"px",
						 "font-size":attrValueArray[6]+"px"});
				if(attrValueArray[10])
					$("#"+id).attr("required",attrValueArray[10]);
				else $("#"+id).removeAttr("required",attrValueArray[10]);
			}
			else{
				$("#"+id).attr("name",attrValueArray[0]);
				$("#"+id).attr("value",attrValueArray[16]);
				$("#"+id).css({"top":attrValueArray[2]+"px", "left":attrValueArray[3]+"px"});
				if(attrValueArray[10])
					$("#"+id).attr("required",attrValueArray[10]);
				else $("#"+id).removeAttr("required",attrValueArray[10]);
				if(attrValueArray[12])
					$("#"+id).attr("checked",attrValueArray[12]);
				else $("#"+id).removeAttr("checked",attrValueArray[12]);
			}
		}
		else if(tag == "textarea"){
			$("#"+id).attr("name",attrValueArray[0]);
			$("#"+id).attr("rows",attrValueArray[17]);
			$("#"+id).attr("columns",attrValueArray[18]);
			$("#"+id).css({"top":attrValueArray[2]+"px", "left":attrValueArray[3]+"px",
					 "font-size":attrValueArray[6]+"px"});
			if(attrValueArray[10])
				$("#"+id).attr("required",attrValueArray[10]);
			else $("#"+id).removeAttr("required",attrValueArray[10]);
		}
		else if(tag == "div"){
			$("#"+id).css({"top":attrValueArray[2]+"px", 
						"left":attrValueArray[3]+"px",
						"width":attrValueArray[14]+"px", 
						"height":attrValueArray[13]+"px"});
		}
		else if(tag == "label"){
			$("#"+id).attr("for",attrValueArray[8]);
			$("#"+id).html(attrValueArray[19]);
			$("#"+id).css({"top":attrValueArray[2]+"px", "left":attrValueArray[3]+"px",
					 "width":attrValueArray[14]+"px", "color":attrValueArray[7],
					 "font-size":attrValueArray[6]+"px"});
		}
	}
	
	/*This function populates the fields in pop up according to the element selected
	for editing, validates the user inputs and updates the attribute values */
	function updateElements(id, popupId){
		var closePopup = false;
		
		popupElementValue(popupId, function(valuesArray){
			//console.log(valuesArray);
			var tag = $("#"+id).get(0).tagName.toLowerCase();
			var result = isValid(valuesArray, tag, id);
			if(result == true){
				//console.log("editing");
				editAttributes(valuesArray, id, tag);
				closePopup = true;
				}
			else{
				//console.log("show error");
				var errorDivObject = $("#"+popupId+" .errorContainer ul");
				errorDivObject.html(result);
				$("#"+popupId+" .errorContainer").css({"display":"block"});
			}
		});
		
		if(closePopup == true)
			return true;
	}
	
	/*This function opens the popup and let the user make changes in the 
	elements attribute values*/
	function showpopup(id,popupTitle){
		$( "#editPopup" ).dialog({
			width: 350,
			modal: true,
			title:popupTitle,
			buttons: [{
				text: "Update",
				click: function() {
					$("#editPopup .errorContainer").css({"display":"none"});
					if(updateElements(id, "editPopup") == true)
						$( this ).dialog( "close" );
				}
			}]
		});
	}
	
	/*this function generates unique id for a particular type of element
	and returns id through callback.
	requires TargetId means id of the div/canvas in which resultant element has 
	to be placed and the category of element*/
	function generateId(targetId, elementType, callback) {
		arrayOfElements(targetId, elementType, function(elementArray){
			var id ="";
			var maxId = 1;
			var key = elementArray[elementArray.length-1];
			var tempArray = Array();
			
			if(elementArray.length === 1){
				id = $.trim(key)+maxId;
			}
			else{
				for(var i=0; i<elementArray.length-1; i++) {
					var mediate = elementArray[i].replace(key,"");
					tempArray[i] = 
						parseInt($.trim(mediate));
				}
				maxId = Math.max.apply(Math, tempArray);
				var newId = maxId+1;
				id = $.trim(key)+newId;
			}
			callback(id);
		});
	}
	
	/*This function generates the array of ids of particular type of 
	element. */
	function arrayOfElements(targetId, elementType, callback) {
		var id = "";
		var elementArray = Array();
		var keyword = "";
		
		switch(elementType) {
			case "Tb":
				$("#"+targetId+" > input[type='text']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				
				$("#"+targetId+" > input[type='date']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				
				$("#"+targetId+" > input[type='email']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				
				$("#"+targetId+" > input[type='number']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				
				$("#"+targetId+" > input[type='password']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				
				elementArray.push("txt");
				break;
			
			case "Cb":
				$("#"+targetId+" > input[type='checkbox']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("chk");
				break;
								
			case "Rb":
				$("#"+targetId+" > input[type='radio']").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("rad");
				break;
								
			case "Ta":
				$("#"+targetId+" > textarea").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("txta");
				break;
			
			case "Dd":
				$("#"+targetId+" > select").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("dd");
				break;
					
			case "Lbl":
				$("#"+targetId+" > label").each(function(index, element) {
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("lbl");
				break;
							 
			case "It":
				$("#"+targetId+" > div[data-inline = 'true']").each(function(index, element) {
					console.log();
					elementArray.push($(this).attr("id"));
				});
				elementArray.push("itxt");
				break;
		}
		callback(elementArray);
	}
	
	/*generates element of specific type with default settings.
	elementType is the type of element needs to be generated. */
	function generateElement(id, elementType, callback) {
		var elementString = "";
	
		switch(elementType) {
			case "Tb":
				elementString = "<input type='text' name = "+id
								+" id = "+id+" value = '' "
								+" class = 'item' style = '"
								+" width:200px; height:30px;"
								+" font-family:Arial; font-size:16px;"
								+" font-weight:normal;"
								+" border-radius:0px;"
								+" border:1px solid black' />";
				break;
			
			case "Cb":
				
				/*need to add some more stuff with ids to make them 
				unique for every checkbox*/
				elementString = "<input type='checkbox' name = "+id
								+" id = "+id+" value = '' class = 'item' />";
				break;
								
			case "Rb":
				
				/*need to add some more stuff with ids to make them 
				unique for every checkbox*/
				elementString = "<input type='radio' name = "+id
								+" id = "+id+" value = '' class = 'item' />";
				break;
								
			case "Ta":
				elementString = "<textarea name = "+id+" id = "+id 
								+" class = 'item' rows='6' "
								+" cols = '40' style = '"
								+" font-family:Arial; font-size:16px;"
								+" font-weight:normal; "
								+" border-radius:0px;"
								+" border:1px solid black;' wrap='soft' /></textarea>";
				break;
			
			case "Dd":
				elementString = "<select name = "+id+"[] id = "+id 
								+" class = 'item' size='1'"
								+" style = 'font-family:Arial;"
								+" font-size:16px; font-weight:normal;"
								+" border-radius:0px; width:auto; height:auto;"
								+" border:1px solid black' >"
									+"<option value='option1'>OPTION1</option>"
									+"<option value='option2'>OPTION2</option>"
									+"<option value='option3' selected = 'selected'>OPTION3</option>"
								+"</select>";
				break;
					
			case "Lbl":
				elementString = "<label id = "+id+" class = 'item' for=''"
								+" style = 'font-family:Arial;"
								+" font-weight:normal; font-size:26px;"
								+" width:auto; height:auto; text-align:left;' >Label"
								+"</label>";
				break;
							 
			case "It":
				elementString = "<div id = "+id+" " 
								+" class = 'item' data-inline = 'true' "
								+" style = 'border:1px solid black; "
								+"Height:150px; Width:300px; overflow:auto' ></div>";
				break;
				
		}
		callback(elementString);
	}
	
	/*This function moves upward to find the parent element which is the 
	actual control that needs to get selected*/
	function getControl( ev) {
		var element = ev.target || ev.srcElement;
		var name;
		
		// Find out the div that holds this element.
		while ( element && ( name = element.nodeName.toLowerCase() ) &&
			( name != 'div' || element.className.indexOf( 'item' ) == -1 ) && name != 'body' ){
				element = element.parentNode;
			}
		
		//return parent element/ control  
		if ( name == 'div' && element.className.indexOf( 'item' ) != -1 )
			return element;
		
		/*This string will show up when either ev don't have any object or 
		code failed to find parent element This conditions supposed do not happen*/	
		else{ 
			//console.log("control did not found due to some logical error");
			return false
		}
	}
	
	/*This function is to kill/ stop events from popagating 
	"Not working  as supposed to"*/
	function murderEvent(evt) {
	 evt.cancel=true;
	 evt.returnValue=false;
	 evt.cancelBubble=true;
	 if (evt.stopPropagation) evt.stopPropagation();
	 if (evt.preventDefault) evt.preventDefault();
	 return false;
	}
	
	/*ck editor object variable*/
	var editor;
	
	/*This function destroys previous editor object and create new.
	At the same time it sets the instance style. It accepts element 
	on which event occurs and a flag that notifies function that 
	user wants to remove all objects.*/
	function addEditor( ev,addInstance) {
		var div;
		if(addInstance == true){
			div = getControl( ev );
		}
		
		if ( editor ){
			var currentInstance;
			for ( var i in CKEDITOR.instances ){
				currentInstance = i;
				break;
			}
			
			if(currentInstance){
				editorWidth = $("span#cke_"+editor.name).css("width");
				editorHeight = $("span#cke_"+editor.name).height()-31;
				$("#"+editor.name).css({"width":editorWidth,"height":editorHeight+"px","z-index":"0"});
				CKEDITOR.instances[currentInstance].destroy();
			}
		}
			
		if(addInstance == true){
			var height = $(div).css("height");
			var width = $(div).css("width");
			var top = $(div).css("top");
			var left = $(div).css("left");
			editor = CKEDITOR.replace( div, {
				height:height, 
				width:width,
				on:{
					instanceReady :function(even){
					 $("span#cke_"+this.name).css({"position":"absolute","top":top,"left":left,"z-index":"0"});
						}
					},
			});
		}
		
	}

	/*This function creates a string of options text and values*/
	function getTextValue(ddOptions){
		var result = "";
		var optionCollection = ddOptions.getElementsByTagName("option");
		
		$(optionCollection).each(function(index, element) {
            result += $(element).html()+",";
			if($(element).attr("selected"))
				result += "*";
			
			result += $(element).attr("value")+"#";
        });
		return result;
	}
	
	/*This function creates an array of data associated with a particular element*/
	function elementsData(elementObject){
		var dataArray = Array();
		dataArray[0] = $(elementObject)[0].nodeName.toLowerCase();
		dataArray[1] = $(elementObject).attr("id").toLowerCase();
		
		dataArray[2] = "";
		if($(elementObject).attr("name"))
			dataArray[2] = $(elementObject).attr("name");
		
		if($(elementObject).attr("rows"))
			dataArray[3] = $(elementObject).attr("rows");
		
		if($(elementObject).attr("columns"))
			dataArray[4] = $(elementObject).attr("columns");
		
		if($(elementObject).css("height"))
			dataArray[5] = $(elementObject).css("height");
			
		if($(elementObject).css("width"))
			dataArray[6] = $(elementObject).css("width");
			
		if($(elementObject).html()){
			dataArray[7] = $(elementObject).html();
			if(dataArray[0] == "select")
				dataArray[7] = getTextValue(elementObject);
		}
		
		if($(elementObject).attr("value"))
			dataArray[8] = $(elementObject).attr("value");
			
		if($(elementObject).attr("type"))
			dataArray[9] = $(elementObject).attr("type");
			
		if($(elementObject).attr("required"))
			dataArray[10] = $(elementObject).attr("required");
		
		if($(elementObject).attr("checked"))
			dataArray[11] = $(elementObject).attr("checked");
			
		if($(elementObject).attr("multiple"))
			dataArray[12] = $(elementObject).attr("multiple");
			
		if($(elementObject).attr("size"))
			dataArray[13] = $(elementObject).attr("size");
			
		if($(elementObject).attr("multiple"))
			dataArray[14] = $(elementObject).attr("multiple");
			
		if($(elementObject).css("font-size"))
			dataArray[15] = $(elementObject).css("font-size");
			
		if($(elementObject).attr("for"))
			dataArray[16] = $(elementObject).attr("for");
			
		if($(elementObject).css("color"))
			dataArray[17] = $(elementObject).css("color");
			
		dataArray[18] = $(elementObject).css("top");
		dataArray[19] = $(elementObject).css("left");
		
		return dataArray;
	}
	
	
	/*This function verifies whether user has provided the for value 
	for labels and radio button and checkbox values */
	function IsDataMissing(errorDiv){
		var missingData = false;
		var errorList = "";
		$("#canvasPanel > input[type='radio'], #canvasPanel > input[type='checkbox'], #canvasPanel > label ")
							.each(function(index, element) {
            if($(element).attr("value") == "" || $(element).attr("for") == ""){
				if(errorList === "")
					errorList += $(element).attr("id");
				else errorList += ", "+$(element).attr("id");
				missingData = true;
			};
        });
		
		if(missingData == true)
			$("#"+errorDiv).html("you are missing some data for given ids: <br />"+errorList) 
		return missingData;
	}
	
	function getCanvasData(inCompleteDataError){
		if(IsDataMissing(inCompleteDataError) == false){
			if(editor)
				editor.destroy();
			var pageElements = Array();
			var index = 0;
			$("#canvasPanel .item").each(function(index, element) {
				pageElements[index] = elementsData(element);
				index++;
			});
			return pageElements;
		}
	}
	
	$(document).ready(function(){
		
		//This is the id of div in which user wants to place form builder			
		var builderWrapper = "builder";
		
		
		
		
		/*add neccessary files to the head of target document
		not working properly*/
		var head = document.getElementsByTagName('head')[0];
		var filesToAdd = '<link href="dnd_change_form/css/dnd.css" rel="stylesheet" type="text/css" />'+
					'<link href="dnd_change_form/css/jquery-ui.css" rel="stylesheet" type="text/css" />'+
					'<script type="text/javascript" src="dnd_change_form/jquery-1.9.1.js" ></script>'+
					'<script type="text/javascript" src="dnd_change_form/jquery-ui.js" ></script>'+
					'<script type="text/javascript" src="dnd_change_form/ckeditor/ckeditor.js" ></script>'+
					'<script type="text/javascript" src="dnd_change_form/ckeditor/config.js" ></script>';
		//$(head).append(filesToAdd);
		
		var builderBodyString = 
				"<div id='controlPanel' class='controlPanel'>"
					+"<div id='inlineToolbar' class='inlineToolbar'> </div>"
					+"<div class='controlWrapper'>"
						+"<div id='textField' class='controlSpec' title='Add Text Field'>Tb</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='checkBox' class='controlSpec' title='Add Checkbox Button'>Cb</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='radioButton' class='controlSpec' title='Add Radio button'>Rb</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='textArea' class='controlSpec' title='Add Text Area'>Ta</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='dropDown' class='controlSpec' title='Add Drop Down'>Dd</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='label' class='controlSpec' title='Add Label'>Lbl</div>"
					+"</div>"
					+"<div class='controlWrapper'>"
						+"<div id='inlineText' class='controlSpec' title='Add Inline Text and Images'>It</div>"
					+"</div>"
				+"</div>"
				+"<div id='canvasPanel' class='canvasPanel'></div>";
		
		$("#"+builderWrapper).append(builderBodyString);
		
		//sets the canvas height
		var builderWrapperH = $("#inlineToolbar").parent().parent().height();
		var canvasH = builderWrapperH- $("#inlineToolbar").parent().height()-3;
		$("#canvasPanel").css({"height":canvasH+"px"});
		
		//adding div for pop up
		var popUpDiv = "<div id='editPopup' class='editPopup'></div>";
		$('body').append(popUpDiv);
		
		var toolBoxString = '<div class="toolbox" id="toolbox" >'
							+'<input type="button" id = "move" value="" class="button"/>'
							+'<input type="button" id = "edit" value="" class="button"/>'
							+'<input type="button" id = "remove" value="" class="button"/></div>';
		$("#canvasPanel").append(toolBoxString);
		$(".toolbox").css({"display":"none"});
		
		var canvasObj = document.getElementById("canvasPanel");
		var previousElementId;
		var isElementDraggable = false;
		
		//Making control icons draggable.
		$(function() {
			$( ".controlSpec" ).draggable({
				containment: "#"+builderWrapper,
				helper: "clone",
				cursor: "move", 
				cursorAt: { top: 25, left: 25 },
				drag:function(event, ui){
					event.stopPropagation();
					//50 is icon height
					var canvasBottomBoundary = $("#canvasPanel").offset().top 
											+ $("#canvasPanel").height()-54 ;
					var draggablePosition = ui.offset.top;
					//console.log("canvasbottom: "+canvasBottomBoundary+",  itembottom: "+draggablePosition);
					if(draggablePosition > canvasBottomBoundary){
						$("#"+builderWrapper+",#canvasPanel").animate({height:$("#"+builderWrapper).height()+100}, 300);
						$("#canvasPanel").animate({height:$("#canvasPanel").height()+100}, 300);
					}
				}
			});
		});
				
		
		var lastEditorEvent = "";
		$(function() {
			$("#canvasPanel").droppable({
				accept: ".controlSpec",
				drop: function(event, ui) 
				{
					var droppedElementXCoord = ui.offset.left - $(this).offset().left;
					var droppedElementYCoord = ui.offset.top - $(this).offset().top;
					var draggedText = $(ui.draggable).text();
					var myitenm = 1;
					
					//placing particular type of control/element on the canvas
					generateId("canvasPanel", draggedText, function(id){
						generateElement(id, draggedText, function(string){
							$("#canvasPanel").append(string);
							$("#"+id).css({"position":"absolute", 
										"top":droppedElementYCoord,"left":droppedElementXCoord});
						});
					});
					
					$(".item").unbind("click").click(function(event1) {
						event1.stopPropagation();
						murderEvent(event1);
                        if(!$(this).hasClass('chosenElement')){
							if(isElementDraggable == true){
								$( ".chosenElement" ).draggable( "destroy" );
								isElementDraggable = false;
							}
							
							//add ckeditor instance to the div
							if($(this).is('div')){
							  $(this).unbind("dblclick").dblclick(function(event2){
								  lastEditorEvent = event2;
								  event2.stopPropagation();
								  murderEvent(event2);
								  addEditor( event2, true );
							  });
							}
							else{//remove editor instance
								 addEditor( lastEditorEvent, false );
							}
							
							$("#"+previousElementId).removeClass('chosenElement');
							$(".chosenToolBox").remove();
							
							$(this).addClass("chosenElement");
							elementId = $(".chosenElement").attr("id");
							placeToolbox("canvasPanel", elementId, "toolbox", toolBoxString );
							$(".toolbox").addClass("chosenToolBox");
							$(".chosenToolBox").removeClass("toolbox");
							previousElementId = elementId;
							
							//when move button on the toolbox is clicked
							$("#canvasPanel .chosenToolBox #move").on('click',function(e){
								e.stopPropagation();
								isElementDraggable = true;
								$(".chosenToolBox").css({"display":"none"});
								$(".chosenElement").draggable({
									cancel: null,
									containment: "#canvasPanel",
									drag:function(event, ui){
										event.stopPropagation();
										//var liter = $(this).attr("id");
										//console.log($("#"+liter).width());
										var elementHeight = $(this).height()+4;
										var canvasBottomBoundary = $("#canvasPanel").offset().top 
																+ $("#canvasPanel").height() 
																- elementHeight ;
										var draggablePosition = ui.offset.top ;
										//console.log($(this).height());
										//console.log("canvasbottom: "+canvasBottomBoundary+",  itembottom: "+draggablePosition);
										if(draggablePosition > canvasBottomBoundary){
											$('#'+builderWrapper+",#canvasPanel").animate({height:$("#"+builderWrapper).height()+100}, 300)
										}
									}
								});
							});
							
							//when remove button on the toolbox is clicked
							$("#canvasPanel .chosenToolBox #remove").on('click',function(event5){
								event5.stopPropagation();
								murderEvent(event5);
								$(".chosenToolBox").remove();
								$(".chosenElement").remove();
							});
							
							//when edit button on the toolbox is clicked
							$("#canvasPanel .chosenToolBox #edit").on('click',function(event6){
								event6.stopPropagation();
								murderEvent(event6);
								populateEditPopup(elementId, function(popupTitle){
									$(".chosenToolBox").css({"display":"none"});
									showpopup(elementId, popupTitle); 
								});
							});
						}
						
						$("#canvasPanel").click(function(event1) {
							$("#canvasPanel").bind("dblclick", function(event7){
								murderEvent(event7);
								if(isElementDraggable == true){
									$( ".chosenElement" ).draggable( "destroy" );
									isElementDraggable = false;
								}
								
								$(".chosenToolBox").css({"display":"none"});
								$("#"+previousElementId).removeClass('chosenElement');
								previousElementId = "";
							});
						});
						
                    });//item click event
					
				}//drop event
			});//droppable event
		 });//function
		 
		 
	});//ready event
