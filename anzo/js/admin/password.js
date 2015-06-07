/* This is a JavaScript class that consist of fuctions required 
by list view.*/

function password() {
	
	//validates user input data before submitting to server
	password.prototype.validateInput = function(){
		var validate = new validator();
		
		if( validate.isEmpty( $("#old").val() ) ){
			$("#returnMsg").text("Old Password is a required field.");
			$("#old").focus();
			return false;
		}
		
		if( validate.isEmpty( $("#new").val() ) ){
			$("#returnMsg").text("New Password is a required field.");
			$("#new").focus();
			return false;
		}
		
		if( !validate.isEquivalent( $("#new").val(), $("#confirm").val() ) ){
			$("#returnMsg").text("New Password and Type again fields has to be same.");
			$("#confirm").focus();
			return false;
		}
	}
	
	//Deeletes the image or Pdf from the server
	password.prototype.deletePdfOrImage = function(){
		var result = false;
		var records = document.getElementsByName("rowId[]");
		for(var i=0; i<records.length; i++){
			if(records[i].checked){
				result = true;
				break;
			}
		}
		
		if(result == false){
		document.getElementById("returnMsg").innerHTML = "<span>Please select a file to delete.</span>";
		return false;
		}else return true;
	}

	
	
	
	
}

