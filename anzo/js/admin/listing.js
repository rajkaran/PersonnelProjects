/* This is a JavaScript class that consist of fuctions required 
by list view.*/

function listing(baseUrl) {
	
    this.baseUrl = baseUrl;
	
	/*Accpets the array of check boxes which will provide record ids in order 
	to enable or disable those category, sub category and article.*/ 
	listing.prototype.enableOrDisableLevels = function (action, level){
		var itemArray = Array();
		var firstIdArray = Array();
		var secondIdArray = Array();
		var collection = document.getElementsByName("rowId");
		var url = this.baseUrl+"administrator/listing/";
		
		for(var i=0; i<collection.length; i++){
			if(collection[i].checked)
				itemArray.push(collection[i]);
		}
		
		if(level == "category"){
			for(var i=0; i<itemArray.length; i++){
				if($(itemArray[i]).attr("data-type") == "Article")
					firstIdArray.push(itemArray[i].value);
				else secondIdArray.push(itemArray[i].value);
			}
		}
		else{
			for(var i=0; i<itemArray.length; i++){
				firstIdArray.push(itemArray[i].value);
			}
		}
		
		if(collection.length > 0){
			$.post(url+"enableAndDisable",{	level:level, firstIdArray:firstIdArray, 
				secondIdArray:secondIdArray, action:action }, function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.msg == "success") location.reload(); 
			});
		}else $("#errorMsg").text("Please select the item before enabling or disabling");

	}

	
	
	
	
}

