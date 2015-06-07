/*CreateSummarySlide.js
	This file includes three functionalities the creating Summary slide,
	returning back to a particualr slide and saving survey data to the local server.
	
*/

function showSummary(db){
	
	//taking system time when user clicks on finish
	surveyEndTimeStamp = getCurrentTimeStamp();
	
	//declaring and initializing variables
	var lastInsertedSurveyId = 0;
	var newSurveyId = 0; 
	var findSlide = false;
	var getDiv = document.getElementById("slideInnerContainer");
	var getSlide = getDiv.getElementsByTagName("div");
	
	//check if summary slide already exists if yes then remove it.
	for(k=0;k<getSlide.length;k++){
		if(getSlide[k].id == "summary")
			findSlide = true;
	}
	if(findSlide == true){
		$("div#summary").remove();
	}
	
	/*constructing sunnary slide if if user has an image as background for application  
	 the summary slide will also have the same background image. */
	var slidestring = "<div class = 'slide' id = 'summary' style = ";
	if(themeArray[6] == 0) 
		slidestring += "'background-image:url("+themeArray[10]+");'"; 
	else
		slidestring += "'background-color:"+themeArray[6]+";'";
	slidestring += "></div>";
	$("#slideInnerContainer").append(slidestring);
	
	//show summary slide to user
	moveForward();
	summarySeqArrayIndex++;
	
	//hide the finish and previous button
	$("#finish").css({"display":"none"});
	$("#back").css({"display":"none"});
	
	// populating summary slide
	$("#summary").append("<p> <bdi>Please save your survey data by clicking on &quot;Save&quot;"
						+ " button now.</bdi><br /> If you want to go back on particular slide,"
						+ " choose the slide from drop down and click on &quot;Go back To&quot;"
						+ " button. You can upload the survey data to the server by clicking on"
						+" &quot;Go to Sync Slide&quot button on first slide. </p>");
	$("#summary").append("<button type = 'button' id = 'save'>Save</button>");
	$("#summary").append("<button type = 'button' id = 'goBack'>Go back To</button>");
	
	$("#save").css({"background-color":themeArray[15],"color":"#FFF"});
	$("#goBack").css({"background-color":themeArray[15],"color":"#FFF"});
	
	//creating a drop down listing all the slides of application
	var dropDownTag = "<select id = 'slideDropDown' size = '1' >";
	for(i=summarySeqArray.length-1; i>-1; i--) {
		if(summarySeqArray[i] != ""){
			var slideNumber = i+1;
			dropDownTag += "<option value = "+i+">"+slideNumber+".   "+ summarySeqArray[i]+"</option>";
		}
	}
	dropDownTag += "</select>";
	$("#summary").append(dropDownTag);
	
	/*If user wants to return to a particular slide the he only need to pick a slide name from the
	drop down and touch the Go Back To button. The last slide of application is selected default.*/
	$("#goBack").click(function(){
		$("#goBack").css({"background-color":themeArray[15],"color":"#000"});
		var getDropDown = document.getElementById("slideDropDown");
		var selectedIndex = getDropDown.options[getDropDown.selectedIndex].value;
		if(selectedIndex == summarySeqArray.length-2) {
		goLeft();
		$("#finish").css({"display":"block"});
		$("#back").css({"display":"block"});
		}
		else {
			while(summarySeqArrayIndex != selectedIndex) {
				goLeft();
			}
			$("#back").css({"display":"block"});
		}
		setTimeout(function (){			
			$("#goBack").css({"background-color":themeArray[15],"color":"#FFF"});
		},1500);
	});
	
	//saving survey data to the local database. This functionality is not implemented. 
	$("#save").click(function(){
		$("#save").css({"background-color":themeArray[15],"color":"#000"});
		surveyEndTimeStamp = getCurrentTimeStamp();
		$("#loadingPopUp").css({"opacity":".7", "filter":"alpha(opacity=70)"});
		document.getElementById("firstMessage").innerHTML = 
                                            "After saving your Survey data application will get reloaded";
		document.getElementById("secondMessage").innerHTML = "";
		
		$('#loadingPopUp').show();
		
		//retrieving last inserted id in survey table
		db.readTransaction(
		function (tx) {
			tx.executeSql('SELECT MAX(id) AS id FROM surveyInfo', [], function(tx,results) {
				var surveyIdString = "<span id='surveyId' style= 'display:none;'>" ;
				surveyIdString +=results.rows.item(0)['id'];
				surveyIdString += "</span>";
				//alert(surveyIdString);
				$("#navigator").append(surveyIdString);
			},onError);
		});
		
		//storing user supplied data to the database
		setTimeout(function (){
			lastInsertedSurveyId = document.getElementById("surveyId").innerText;
			if(lastInsertedSurveyId == "null" || lastInsertedSurveyId == "") 
				newSurveyId = 1;
			else
				newSurveyId = parseInt(lastInsertedSurveyId)+1;
				
			makeSurveyEntry(db, newSurveyId, function () {
					savedata(db,newSurveyId);
			});
			
		},100);	
		
		//close the popup and reload application
		setTimeout(function (){
			$('#loadingPopUp').hide();
			$("#save").css({"background-color":themeArray[15],"color":"#FFF"});
			window.location.reload(true);
		},5000);
	});
}

//storing data supplied by the user in general control (input and textarea)
function storeDataInDatabase(surveyId, controlId, value, db) {
	var query = "INSERT INTO surveyData('surveyId','controId','value') "
				+"VALUES ('"+surveyId+"','"+controlId+"','"+value+"')";
	db.transaction(
		function (tx) {
			tx.executeSql(query, [],successMsg ,onError);
		});
}

//storing data related to like control
function storeLikeInDatabase(surveyId, likeControId, objectId, objectType,
								 xCoordinate, yCoordinate, db) {
	var queryf = "INSERT INTO likeData('surveyId','likeControId', 'objectId',"
	+" 'objectType', 'xCoordinate',  'yCoordinate') VALUES ("+surveyId+","
	+likeControId+","+objectId+",'"+objectType+"',"
	+xCoordinate+","+yCoordinate+")";
	db.transaction(
		function (tx) {
			tx.executeSql(queryf, [],successMsg ,onError);
		}); 
}

//entering new record in Survey info table for new survey
function makeSurveyEntry(db,SurveyId, callback) {
	var query = "Insert into surveyInfo('id', 'startTimeStamp', 'endTimeStamp')"
					+" Values ("+SurveyId+",'"+surveyStartTimeStamp+"','"
					+surveyEndTimeStamp+"')";
	db.transaction(
		function (tx) {
			tx.executeSql(query, [],function (tx) {callback();} ,onError);
		});
}

//picking data from the controls
function savedata(dbObject, lastSurveyId) {
	for(i=0; i<summarySeqArray.length-1; i++) {
		if(summarySeqArray[i] != "") {
			 
			var findSlide = false;
			var getDiv = document.getElementById(summarySeqArray[i]);
			var inputTagCollection = getDiv.getElementsByTagName("input");
			var textAreaTagCollection = getDiv.getElementsByTagName("textarea");
			var likeCollection = getDiv.getElementsByTagName("div");
			
			//executes only if slide contain input element
			if(inputTagCollection.length != 0) {
				for(k=0;k<inputTagCollection.length;k++){
					if(inputTagCollection[k].type == "text" || 
									inputTagCollection[k].type == "range")
						storeDataInDatabase(lastSurveyId, 
												inputTagCollection[k].id, 
												inputTagCollection[k].value, 
												dbObject);
												
					else if(inputTagCollection[k].type == "checkbox" 
								|| inputTagCollection[k].type == "radio" ){
						var value = false;
						if(inputTagCollection[k].checked)
							value = true;
						storeDataInDatabase(lastSurveyId, 
											inputTagCollection[k].id, 
											value, dbObject);
					}
					
				}
			}
			
			//executes only if slide contain input textarea
			if(textAreaTagCollection.length != 0) {
				for(k=0;k<textAreaTagCollection.length;k++){
						storeDataInDatabase(lastSurveyId, 
									textAreaTagCollection[k].id, 
									textAreaTagCollection[k].value, 
									dbObject);
				}
			}
			
			//executes only if slide contain like control
			if(likeCollection.length != 0) {
				var someimage = likeCollection[0];
				var myimg = someimage.getElementsByTagName('img');
				var length = myimg.length;
				
				for(j=0;j<length;j++){
					var id = $(myimg[j]).attr("id");
					var liketype = $(myimg[j]).attr("data-controlType");
					var xCoordinates = $(myimg[j]).position().left;
					var yCoordinates = $(myimg[j]).position().top;
					storeLikeInDatabase(lastSurveyId, 
										likeCollection[0].id, id,
										liketype, xCoordinates,
										 yCoordinates, dbObject);
				}
			}
		
		
		}
	}	

}


//get the current system date and time
function getCurrentTimeStamp() {
	var currentdate = new Date(); 
	var timeStamp = "Last Sync: " + checkDate_Time(currentdate.getDate()) + "/"
                + checkDate_Time((currentdate.getMonth()+1))  + "/" 
                + currentdate.getFullYear() + " @ "  
                + checkDate_Time(currentdate.getHours()) + ":"  
                + checkDate_Time(currentdate.getMinutes()) + ":" 
                + checkDate_Time(currentdate.getSeconds());
	return timeStamp;
}

function checkDate_Time(string){
    return (string < 10) ? ("0" + string) : string;   
}

