/* This is a JavaScript class that consist of fuctions required 
by home page view.*/

function home_page(baseUrl) {
	
    this.baseUrl = baseUrl;
	
	/*Accpets the array of check boxes which will provide event ids in order 
	to enable or disable those events.*/ 
	home_page.prototype.enableOrDisableEvent = function(action){
		var itemArray = Array();
		var eventIdArray = Array();
		var collection = document.getElementsByClassName("eventCheckbox");
		var url = this.baseUrl+"administrator/manage_home_page/";
		
		for(var i=0; i<collection.length; i++){
			if(collection[i].checked)
				itemArray.push(collection[i]);
		}
		
		for(var i=0; i<itemArray.length; i++){
			eventIdArray.push($(itemArray[i]).attr("id"));
		}
		
		if(eventIdArray.length > 0){
			$.post(url+"enableOrDisable",{ idArray:eventIdArray, action:action},
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.response == "success"){
						location.reload(); 
					}else
						$("#errorMsg").html("Action didn't completed. Database error occured.")
			});
		}else $("#errorMsg").html("Please select the item before enabling or disabling");

	}
	
	/*Accepts event id; if id is not null than the operation is update / edit 
	otherwise create new*/
	home_page.prototype.createOrUpdateEvent = function(id){
		var name = $("#nameTextbox").val();
		var validate = new validator();
		var description = CKEDITOR.instances['description'].getData();
		var url = this.baseUrl+"administrator/manage_home_page/";
		
		if(!validate.isEmpty(name) && !validate.isEmpty(description) ){
			$.post(url+"saveEvent",{ eventId:id, name:name, description:description },
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.response == "success"){
						$("#errorMsg").html("<span> Event has been "+jsonObj.action
											+". To see it in list press ctrl+r keys.</span> ");
					}
					else
						$("#errorMsg").html("Event didn't created, database error occured.");
			});	
		}
		else $("#errorMsg").html("Event Name or Event Description is missing.");
	}
	
	
	
	
}

