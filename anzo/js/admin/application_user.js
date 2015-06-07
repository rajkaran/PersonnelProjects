/* This is a JavaScript class that consist of fuctions required 
by list view.*/

function application_user(baseUrl) {
	
	this.baseUrl = baseUrl;
	var self = this;
	
	//This method enables or disables; those users, admin wants to
	application_user.prototype.enableOrDisableUser = function(action){
		var itemArray = Array();
		var UserIdArray = Array();
		var collection = document.getElementsByName("rowId");
		var url = this.baseUrl+"administrator/manage_user/";
		
		for(var i=0; i<collection.length; i++){
			if(collection[i].checked)
				itemArray.push(collection[i]);
		}
		
		for(var i=0; i<itemArray.length; i++){
			UserIdArray.push($(itemArray[i]).attr("id"));
		}
		
		if(UserIdArray.length > 0){
			$.post(url+"enableOrDisable",{ idArray:UserIdArray, action:action},
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.response == "success") location.reload(); 
					else
						$("#errorMsg").html("Action didn't completed. Database error occured.")
			});
		}else $("#errorMsg").html("Please select the item before enabling or disabling");
	}
	
	/*This method validates the user info and returns an array of info if validation 
	passes otherwise returns list of errors*/
	application_user.prototype.validateInput = function(){
		var userInfo = new Object();
		var isError = false;
		var errorString = "<ul>";
		var validate = new validator();
		
		if( !validate.isEmpty( $("#userName").val() ) )
			userInfo.userName=$("#userName").val().trim();
		else{
			isError = true;
			errorString += "<li>User Name is required field.</li>";
		}
		
		if( !validate.isEmpty( $("#password").val() ))
			userInfo.password=$("#password").val().trim();
		else{
			isError = true;
			errorString += "<li>Password is required field.</li>";
		}
		
		userInfo.confirmPassword=$("#confirmPassword").val().trim();
		if( !validate.isEquivalent( userInfo.password, userInfo.confirmPassword) ){
			isError = true;
			errorString += "<li>Password and Confirm Password fields has to be same.</li>";
		}
		
		if( !validate.isEmpty( $("#empId").val() ))
			userInfo.empId=$("#empId").val().trim();
		else{
			isError = true;
			errorString += "<li>Employee Id is required field.</li>";
		}
		
		if(!validate.isEmpty( $("#emailId").val() ) )
			userInfo.emailId=$("#emailId").val().trim();
		else{
			isError = true;
			errorString += "<li>Email Id is required field.</li>";
		}
		
		if( !validate.isEmail("emailId") ){
			isError = true;
			errorString += "<li>Email Id is not valid.</li>";
		}
		
		if( !validate.isEmpty( $("#titles").val() ))
			userInfo.titles=$("#titles").val();
		else{
			isError = true;
			errorString += "<li>Please select at least one title.</li>";
		}
		
		errorString += "</ul>";
		
		if(isError == true)	return (false, errorString);
		else return (true, userInfo);
	}
	
	
	/*This method passes the User info submitted by admin in order to 
	create new user or Update User info*/
	application_user.prototype.createOrUpdateUser = function(id){
		var url = this.baseUrl+"administrator/manage_user/";
		var userInput = self.validateInput();
		
		if(self.validateInput()[0] == true){
			console.log(id);
			$.post(url+"saveUser",{ userId:id, userInfo:self.validateInput()[1] },
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.response == "success")
						window.location.replace(this.baseUrl+"administrator/manage_user/loadUserList");
					else $("#returnMsg").html(jsonObj.returnMsg);
			});
		}else $("#returnMsg").html(self.validateInput());
	}
	
	
	
}

