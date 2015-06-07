/* movement.js
	
	This file handles the movemnt of slides. When slides move forward system checks whether 
	current slide contain subset checkbox or current slide contains like control.
	 */

//declaring and initializing variables
var counter = 0;
var summarySeqArrayIndex = 0;

/*check if control on current slide is required if yes then confirm it is set. change 
the view to next slide. check if current slide containg checkbox with subset. Disable
and enable slide subet related to particular checkbox. */
function goRight(){
	var currentContainSubset = false;
	
	//move forward untill view reach to second last slide.
	if( summarySeqArrayIndex < slideSeqArray.length-2 ) {
		
		//check whether current slide conatin subset checkbox.
		for(i=0;i<slideContainSubSetArray.length-1;i++) {
			if(summarySeqArray[summarySeqArrayIndex] == slideContainSubSetArray[i])
				currentContainSubset = true;
		}
		
		//check if required controls are set.
		var getDiv = document.getElementById(summarySeqArray[summarySeqArrayIndex]);
		var getSpans = getDiv.getElementsByTagName("input");
		for(k=0;k<getSpans.length;k++){
		    if (getSpans[k].type == "text" && getSpans[k].required == true 
                                && getSpans[k].value.trim() == ""){
				alert("Are you mising Sales person's or Client's Name");
				return false;
			}
		}
		
		/*if current slide contain subset then disable all the slide subsets associated with unchecked
		checkbox and enable slide subset associated with checked checkbox.*/ 
		if(currentContainSubset == true) {
			var countCheckBoxesWithSubSet = 0;
			var countSubSetCheckBoxChecked = 0;
			var checkArray = $("div#"+summarySeqArray[summarySeqArrayIndex] +" :input");
			var finalArray = Array();
			
			//make the array of all checkboxes
			for(i=0;i<checkArray.length;i++) {
				if(checkArray[i].type === "checkbox")
					finalArray.push(checkArray[i]);
			}
			
			for(j=0;j<finalArray.length;j++) {
				var findSubset = false;
				var getDiv = document.getElementById("navigator");
				var getSpansCollection = getDiv.getElementsByTagName("span");
				var collectionIndex;
				
				/*loop through the checkboxes array and pick particular checkbox for
				 which a subset exists.*/ 
				for(k=0;k<getSpansCollection.length;k++){
					if(getSpansCollection[k].id == finalArray[j].name){
						collectionIndex = k; 
						findSubset = true;
						k = getSpansCollection.length;
					}
				}
				
				if(findSubset == true) {
					var chkBoxString = getSpansCollection[collectionIndex].innerText;
					countCheckBoxesWithSubSet++;
					chkBoxArray = chkBoxString.split(",");
					//enable the subset
					if(finalArray[j].checked) {
						countSubSetCheckBoxChecked++;
						for(k=0;k<chkBoxArray.length-1;k++) {
							$("div#"+chkBoxArray[k]).css({"display":"block"});
							var seqArrayIndex;
							for(i=0;i<slideSeqArray.length;i++){
								if(chkBoxArray[k] == slideSeqArray[i])
									seqArrayIndex = i;
							}
						summarySeqArray[seqArrayIndex] = chkBoxArray[k];
						}
					}
					//diable the subset
					else {
						for(k=0;k<chkBoxArray.length-1;k++) {
							$("div#"+chkBoxArray[k]).css({"display":"none"});
							var seqArrayIndex;
							for(i=0;i<slideSeqArray.length;i++){
								if(chkBoxArray[k] == slideSeqArray[i])
									seqArrayIndex = i;
							}
						summarySeqArray[seqArrayIndex] = "";
						}
					}
					
				}
				
			}
			
			//stop program flow if none of the checkbox is checked
			if(countCheckBoxesWithSubSet > 0 && countSubSetCheckBoxChecked < 1){
				alert("Please select atleast one of the checkbox ");
				return false;
			}
			
		}
		
		//move forward
		moveForward();
		setTimeout(function (){$("#next").attr("src","img/next1.gif");},1500);
		
		//set the index to nextslide viewed on screen
		if(summarySeqArray[summarySeqArrayIndex+1] == "") {
			while(summarySeqArray[summarySeqArrayIndex+1] == "") {
				summarySeqArrayIndex++;
			}
			summarySeqArrayIndex++;
		}
		else
			summarySeqArrayIndex++;
			
		if(summarySeqArrayIndex == slideSeqArray.length-2) {
			$("#next").css({"display":"none"});
			$("#finish").css({"display":"block"});
			
		}
		$("#back").css({"display":"block"});
		$("#goSyncSlide").css({"display":"none"});
		
	}
	
}

//moving view to the next slide
function moveForward() {
	if(counter == 0){
		$(".slideInnerContainer").css({"-moz-transform":"translate("+-1024+"px)"});
		$(".slideInnerContainer").css({"-webkit-transform":"translate("+-1024+"px)"});
		$(".slideInnerContainer").css({"transform":"translate("+-1024+"px)"});
		counter+=2;
		flag = false;
	}
	else {
		if(flag == true) counter ++;
		$(".slideInnerContainer").css({"-moz-transform":"translate("+-counter*1024+"px)"});
		$(".slideInnerContainer").css({"-webkit-transform":"translate("+-counter*1024+"px)"});
		$(".slideInnerContainer").css({"transform":"translate("+-counter*1024+"px)"});
		counter++;
		flag = false;
	}
	
}

// return to the previous slide and reduce the index to revious slide.
function goLeft(){
	if(counter!=0) {
		if(flag == false) {
			counter-=2;
			$(".slideInnerContainer").css({"-moz-transform":"translate("+-counter*1024+"px)"});
			$(".slideInnerContainer").css({"-webkit-transform":"translate("+-counter*1024+"px)"});
			$(".slideInnerContainer").css({"transform":"translate("+-counter*1024+"px)"});
			flag = true;
		}
		else {
			counter-=1;
			$(".slideInnerContainer").css({"-moz-transform":"translate("+-counter*1024+"px)"});
			$(".slideInnerContainer").css({"-webkit-transform":"translate("+-counter*1024+"px)"});
			$(".slideInnerContainer").css({"transform":"translate("+-counter*1024+"px)"});
		}
		
		setTimeout(function (){$("#back").attr("src","img/back1.gif");},1500);
		
		if(summarySeqArrayIndex == slideSeqArray.length-2) {
			$("#next").css({"display":"block"});
			$("#finish").css({"display":"none"});
		}
		
		if(summarySeqArray[summarySeqArrayIndex-1] == "") {
			while(summarySeqArray[summarySeqArrayIndex-1] == "") {
				summarySeqArrayIndex--;
			}
			summarySeqArrayIndex--;
		}
		else
			summarySeqArrayIndex--;
			
		if(summarySeqArrayIndex == 0) {
			$("#back").css({"display":"none"});
			$("#goSyncSlide").css({"display":"block"});
		}
	}
}




