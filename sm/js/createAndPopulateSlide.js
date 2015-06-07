/* createAndPopulateSlide.js
	get the data from theme table into an array.
	create and populate the slides from the database */

//returns an array in callback
function createThemeArray(db,callback) {
	db.readTransaction(
		function (tx) {
			tx.executeSql('SELECT * FROM theme', [], function(tx,results) {
				var themeDataString = "<span id='themeArray' style= 'display:none;'>" ;
				for (var i=0; i<results.rows.length; i++) { 
				   var row=results.rows.item(i);
				   themeDataString += row['value'] +",";
				}
				themeDataString += "</span>";
				$("#navigator").append(themeDataString);
				callback();
			},onError);
		}
	);
}	

/*construct slides one by one and populates it at the same time
prints data about three arrays in hidden spans*/
function createSlides(db,callback) {
	$("#slideOuterContainer").css({"background-color":themeArray[15]});
	db.readTransaction(
	function (tx) {
		tx.executeSql('SELECT * FROM slides ORDER BY seqNum; ', [], function(tx,results) {
			var slideSeqString = "<span id='slideSeq' style= 'display:none;'>" ;
			var containSubSetString = "<span id='containSubSet' style= 'display:none;'>" ;
			
			$("#slideInnerContainer").css({"width":(results.rows.length+1)*1024+"px"});
			$("#navigator").append("<input type='image' id='back' src='img/back1.gif'" 
                                         +"style = 'display:none;' />");
			$("#navigator").append("<input type='image' id='next' src='img/next1.gif' />");
			$("#navigator").append("<button type = 'button' id = 'goSyncSlide'>Go To Sync Slide"
                                  +"</button>");
			$("#goSyncSlide").css({ "width": "200px", "height": "44px",
			                        "border": "0px", "background-color": "#000",
                                    "color":themeArray[15], "font-size":"23px"});
            $("#navigator").append("<input type='image' id='finish' src='img/next1.gif' "
                                         +"style = 'display:none;' />");
			
			//loop through all items of retrieved set
			for (var i=0; i<results.rows.length; i++) { 
			   var row=results.rows.item(i);
			   slideSeqString += row['name'] +",";
			   
			   //create comma separated string of slide ids containg checkbox with subset. 
			   if(row['hasSubSetCheck'] == 1)
			   		containSubSetString += row['name'] +",";
					
				/*if current id is part of subset than add the slide name in 
				another comma separated string.*/	
				if(row['partOfSubSet'] == 1){
					var findSpan = false;
					var getDiv = document.getElementById("navigator");
					var getSpans = getDiv.getElementsByTagName("span");
					for(k=0;k<getSpans.length;k++){
						if(getSpans[k].id == row['checkboxID'])
							findSpan = true;
					}
					if(findSpan == false)
					    $("#navigator").append("<span style= 'display:none;' "
                                                + "id=" + row['checkboxID'] + ">"
                                                +row['name']+","+"</span>");
					else
						$("span#"+row['checkboxID']).append(row['name']+",");
				}
				
				/*construct slide and set background image if indicated otherwise 
				set background colour.*/
				var slidestring = "<div class = 'slide' id = "+row['name']+" style = ";
				if(themeArray[6] == 0) 
					slidestring += "'background-image:url("+themeArray[10]+");'"; 
				else
					slidestring += "'background-color:"+themeArray[6]+";'";
				slidestring += "></div>";
				$("#slideInnerContainer").append(slidestring);
				 
				 //calling a function to populate slides with controls.
				populateSlides(row['id'],row['name'],db);
				
			}
			
			//append both comma separated string into a hidden span
			slideSeqString += "</span>";
			containSubSetString += "</span>";
			$("#navigator").append(containSubSetString);
			$("#navigator").append(slideSeqString);
			callback();
		},onError);
	});
}

//populating controls
function populateSlides(string1,string2,db) {
	var sql = "SELECT * FROM controls WHERE slideID="+string1;
	db.readTransaction(
	function (tx) {
		tx.executeSql(sql, [], function(tx,results) {
			for (var i=0; i<results.rows.length; i++) { 
			   var row=results.rows.item(i);
			 
			 //append particular type of control into the slide.
			  switch(row['type']){
				//if text  
				case "text":
					if(row['isRequired'] == 1){
					    $("div#" + string2).append(" <input type = 'text' "
                                                + "name = " + row['name']
                                                + " id =" +row['id']+" required = 'true' />");
					}else {
					    $("div#" + string2).append(" <input type = 'text' "
                                                + "name = " + row['name']
                                                + " id =" +row['id']+" />");
					}
					$("#" + row['id']).css({ "position": "absolute", "padding-left": "15px",
					                         "border": "0px solid " + themeArray[9],
					                         "top": row['top'] + "px", "left": row['left'] + "px",
					                         "width": themeArray[0] + "px", 
                                             "height": themeArray[1] + "px",
                                             "border-radius": themeArray[7] + "px", 
                                             "color": themeArray[12],
                                             "background-color": themeArray[11],
                                             "font-family": themeArray[3],
                                             "font-size":themeArray[2]+"px" });
					break;
				//if checkbox	
				case "checkbox":
					if(row['isRequired'] == 1){
					    $("div#" + string2).append(" <input type = 'checkbox' "
                                                         + "name = " + row['name'] + " id =" + row['id']
                                                         +" checked = 'checked' />");
					}else {
					    $("div#" + string2).append(" <input type = 'checkbox' "
                                                          + "name = " + row['name']
                                                          + " id =" +row['id']+" />");
					}
					$("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
                                             "left":row['left']+"px" });
					break;
				
				//if radio	
				case "radio":
					if(row['isRequired'] == 1){
					    $("div#" + string2).append(" <input type = 'radio' name = " + row['name']
                                                        + " id =" +row['id']+" checked = 'checked' />");
					}else {
					    $("div#" + string2).append(" <input type = 'radio' name = " + row['name']
                                                        + " id =" +row['id']+" />");
					}
					$("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
                                             "left":row['left']+"px" });
					break;
				
				//if pic	
				case "pic":
				    $("div#" + string2).append(" <img src=" + row['value']
                                                   + " width=" + row['width'] + "px"
                                                   +" height="+row['height']+"px id="+row['id']+" />");
				    $("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
                                             "left":row['left']+"px" });
					break;
				
				//if label
				case "label":
				    $("div#" + string2).append("<label for =" + row['forValue'] + ">"
                                                    +"<span id =" + row['id'] + " >"
                                                    +row['value']+"</span></label>");
				    $("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
				                             "left": row['left'] + "px", "font-family": themeArray[3],
                                             "font-size":themeArray[2]+"px", "color":themeArray[9] });
					break;
					
				//if para	
				case "para":
					$("div#"+string2).append(" <p id =" +row['id']+">"+row['value']+"</p>");
					$("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
					                         "left": row['left'] + "px", "font-family": themeArray[3],
					                         "font-size": row['paraSize'] + "px", "color": row['paraColor'],
                                             "width":row['width'] });
					break;
				
				//if textarea	
				case "textarea":
				    $("div#" + string2).append(" <textarea id =" + row['id']
                                                        + " name = " + row['name']
                                                        + " cols= " + row['width']
                                                        + " rows = " + row['height']
                                                        + " placeholder = '" + row['placeHolder']
                                                        +"' wrap = 'soft'></textarea>");
				    $("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
				                             "left": row['left'] + "px", "color": themeArray[9],
				                             "border-radius": themeArray[13] + "px", "color": themeArray[9],
				                             "background-color": themeArray[6], "font-family": themeArray[3],
				                             "font-size": themeArray[2] + "px", "padding-left": "5px",
                                             "resize":"none", "border":themeArray[14] });
					break;
				
				//if slider	
				case "slider":
				    $("div#" + string2).append(" <input type = 'range' min = '0' max = '100' value = '50'"
                                                    +" name = "+row['name']+ " id =" +row['id']+" />");
				    $("#" + row['id']).css({ "position": "absolute", "top": row['top'] + "px",
                                             "left":row['left']+"px", "width":row['width']+"px"});
					break;
				
				//if like	
				case "like":
					$("div#"+string2).append("<div id = "+row['id']+" data-type = 'like'></div>");
					var img = new Image;
					img.src = row['value'];
					img.onload = function() { 
						var imgNewDimension = resize(img.width,img.height);
						$("#" + row['id']).css({ "width": imgNewDimension[0] + "px",
						                         "background-image": "url(" + row['value'] + ")",
						                         "background-size": imgNewDimension[0] + "px " + imgNewDimension[1] + "px",
						                         "background-position": "center",
                                                 "background-repeat":"no-repeat"});
						
						var secondaryDivId = "trash"+string2;
						$("#" + string2).append("<div id =" + secondaryDivId + " style = 'float:left;"
                                                + " width:150px; height:550px; padding:auto;"
                                                +" text-align:center;'></div>");
						$("#" + secondaryDivId).css({ "width": 1024 - imgNewDimension[0] + "px",
                                                      "height":imgNewDimension[1]+"px"});
						
						$("#"+secondaryDivId).append("<br/><br/><br/><br/><br/><br/><br/><br/><br/>"
                                                        + "<br/><br/><br/><img src='img/LikeTexture.png'"
                                                        + " class = 'draggable' id='texture'"
                                                        + " width = '80px' height = '80px'"
                                                        +" data-controlType = 'texture' /><br/><br/>");
						$("#" + secondaryDivId).append("<img src='img/LikeColour.png' class = 'draggable'"
                                                        + " id='colour'  width = '80px' height = '80px'"
                                                        +" data-controlType = 'colour' /><br/><br/>");
						$("#" + secondaryDivId).append("<img src='img/LikeDesign.png' class = 'draggable'"
                                                          + " id='design'  width = '80px' height = '80px'"
                                                          +" data-controlType = 'design' /><br/><br/>");
						
						var slideXCoordinate = $("#"+string2).parent().offset().left;
						var slideYCoordinate = $("#"+string2).parent().offset().top;
						
						//making like controls as draggable	
						$('.draggable').draggable({
						    containment: [slideXCoordinate, slideYCoordinate,
                                          slideXCoordinate+944, slideYCoordinate+470],
							helper: "clone"
						});
						
						//making div as droppable
						var dynamicId = 0;
						$("#"+row['id']).droppable({
							accept: ".draggable",
							drop: function(event, ui) 
							{
								//alert(ui.draggable.attr("class") );
								var clone = $(ui.helper).clone();
								$(clone).removeAttr("id");
								$(clone).attr('id', dynamicId)
								$(this).append(clone);
								$("#"+row['id']+" .draggable").addClass("item");
								$(".item").removeClass("draggable");
								$(".item").css({"position":"absolute","width":"50px","height":"50px"});
								dynamicId++;
								$(".item").draggable({
									containment: "#"+string2,
									create: function(){
									}
								});
							}
						});
						
						//making div as trash can
						$("#"+secondaryDivId).droppable({
							accept: ".item",
							drop: function(event, ui) 
							{
								var id = ui.draggable.attr("id");
								//alert(id);
								$(ui.draggable).remove();
							}
						});
					
					}
					break;
					
			}
			   
			}
		},onError);
	});
}

//fuction to resize the background image while keeping aspect ratio intact.
function resize(oriWidth,oriHeight){
	var newDimension = Array(2);
	if(oriWidth > 850 && oriHeight > 550 )
	{
		var ratioW = 850 / oriWidth;
		var ratioH = 550 / oriHeight;
		var ratio = ratioW < ratioH ? ratioW : ratioH;
		
		newDimension[0] = Math.round(oriWidth*ratio);
		newDimension[1] = Math.round(oriHeight*ratio);
	}
	else if(oriWidth > 850) {
		var ratioH = 550 / oriHeight;
		var ratio = ratioH;
		
		newDimension[0] = Math.round(oriWidth);
		newDimension[1] = Math.round(oriHeight*ratio);
	}
	else if(oriHeight > 550) {
		var ratioW = 850 / oriWidth;
		var ratio = ratioW;
		
		newDimension[0] = Math.round(oriWidth*ratio);
		newDimension[1] = Math.round(oriHeight);
	}
	else {
		newDimension[0] = Math.round(oriWidth);
		newDimension[1] = Math.round(oriHeight);
	}
	return newDimension;
}



