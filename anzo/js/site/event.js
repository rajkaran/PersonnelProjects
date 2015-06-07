// This is a JavaScript class that consist of functions required by every header of the front end.

function home_Page_Event(baseUrl) {
	
    this.baseUrl = baseUrl;
	var self = this;
	
	//This function retrieves the event desription for selected event.
	home_Page_Event.prototype.getDescription = function(id){
		var url = this.baseUrl+"anzo/home/";
		$.post(url+"getEventDescription",{ eventId:id},
			function(data,status){
				var jsonObj = JSON.parse(data);
				if(jsonObj.msg == "success")
					$("#description").html(jsonObj.desc);
				$(".contentSection").height($("#description").height()+20);
				$("#"+id).parent("li").addClass("selected").siblings("li").removeClass("selected");
		});
	}
	
	//This function creates a cycle of events and change them after a interval.
	home_Page_Event.prototype.cycleThroughEvent = function(){
		var eventIndex = 1;
		
		var idArray = Array();
		var i = 0;
		$("#eventList li a").each(function(index, element) {
            idArray[i] = $(element).attr("id");
			i++;
        });
		
		setInterval(function(){
			self.getDescription(idArray[eventIndex]);
			eventIndex++;
			if(eventIndex == idArray.length) eventIndex = 0;
			},20000);
	}
	
	
	
	
	
	
	
	
	
}

