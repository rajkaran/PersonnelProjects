/* This is a JavaScript class that consist of fuctions required 
by list view.*/

function read(baseUrl, articleId) {
	
	this.baseUrl = baseUrl;
	this.id = articleId;
	var self = this;
	
	//Toggle the parents depending on the selected radio button
	read.prototype.toggleParent = function(selectedRadio){
		if($(selectedRadio).val() == "category"){
			$("#categoryLabel").css("display","block");
			$("#subCategoryLabel").css("display","none");
			selectString = "Category Name:" + "<input type='text' "
					+"name='categoryName' id='category' disabled='disabled'/>";
			$("#categoryLabel").html(selectString);
		}
		else{
			$("#categoryLabel").css("display","none");
			$("#subCategoryLabel").css("display","block");
			selectString = "Sub Category Name:" + "<input type='text' "
					+"name='subCategoryName' id='subCategory' disabled='disabled'/>";
			$("#subCategoryLabel").html(selectString);
		}
	}
	
	/*calculating the height of article in ordeer to resize the canvas.*/
	read.prototype.getAndSetArticleHeight = function(){
		var url = this.baseUrl+"administrator/create_and_edit/";
		$.post(url+"getArticleHeight",{ articleId:id},
			function(bottom,status){
				var jsonObj = JSON.parse(bottom);
				var bottomEnd = parseInt(jsonObj.articleLength)+10;
				if(bottomEnd < 470)
					$("#readArticle").css("height", "470px");
				else $("#readArticle").css("height", bottomEnd+"px");
		});
	}
	
	/*retrieve article info throgh Ajax call and place the in pop up fields*/
	read.prototype.getAndSetArticleSettings = function(){
		var url = this.baseUrl+"administrator/read/";
		var connectedToObj = "";
		
		$.post(url+"getArticleSetting",{ articleId:this.id},
			function(elements,status){
				
				var jsonObj = JSON.parse(elements);
				connectedToObj = jsonObj.connectedTo == 1?"#toCategory":"#toSubCategory";
				
				$(connectedToObj).attr("checked","checked");
				self.toggleParent(connectedToObj);
				
				if(jsonObj.connectedTo == 1)
					$("#category").val(jsonObj.category);
				else $("#subCategory").val(jsonObj.subCategory);
				
				$("#articleName").val(jsonObj.articleName);
				$("#articleTitle").val(jsonObj.articleTitle);
				$("#articleKeyword").val(jsonObj.articleKeyword);
				
				if(jsonObj.havePdfVersion == 0){
					$("#pdfExist").html("<p>You don\'t have pdf version of this article "
							+"<button onclick='articleReadMode.createPdf()' class='createPdf'>click here"
							+"</button> to create one.</p><span></span>");
					$(".pdfIcon").css({"display":"none"});
				}else{
					$("#pdfExist").html("<p>You have pdf version for this article "
							+"<button onclick='articleReadMode.removePdf()' class='removePdf'>click here"
							+"</button> to remove it.</p><span></span>");
					$(".pdfIcon").css({"display":"block"});
				}
					
				if(jsonObj.isItForm == 1){
					$("#isThisForm").attr("checked","checked");
					$("#submitButton").html("<button type='button'>Submit</button>");
				}
				else{
					$("#isThisForm").removeAttr("checked");
					$("#submitButton").html("");
				}
				
				if(jsonObj.setToEmail == 1)
					$("#setToEmail").attr("checked","checked");
				else $("#setToEmail").removeAttr("checked");
				
				if(jsonObj.copyTo != "")
					$("#copyTo").val(jsonObj.copyTo);
				else $("#copyTo").val("");
				
				if(jsonObj.sendTo != "")
					$("#sendTo").val(jsonObj.sendTo);
				else $("#sendTo").val("");
				
		});
	}
	
	/*Create a pdf for this article not completed yet*/
	read.prototype.createPdf = function(){
		var url = this.baseUrl+"administrator/read/";
		
		$.post(url+"makePdf",{ articleId:this.id, data:$("#readArticle").html()},
			function(message,status){
				var jsonObj = JSON.parse(message);
				if(jsonObj.msg == "success"){
					$("#pdf").attr("href",jsonObj.path);
					$(".pdfIcon").css({"display":"block"});
					$("#pdfExist").html("<p>You have pdf version for this article "
						+"<button onclick='articleReadMode.removePdf()' class='removePdf'>click here"
						+"</button> to remove it.</p><span></span>");
				}else $("#settingError").html(jsonObj.error);
		});
		
	}
	
	/*Remove exsiting pdf for this article not completed yet*/
	read.prototype.removePdf = function(){
		var url = this.baseUrl+"administrator/read/";
		
		$.post(url+"disposePdf",{ articleId:this.id},
			function(message,status){
				var jsonObj = JSON.parse(message);
				if(jsonObj.msg == "success"){
					$(".pdfIcon").css({"display":"none"});
					$("#pdfExist").html("<p>You don\'t have pdf version of this article "
					+"<button onclick='articleReadMode.createPdf()' class='createPdf'>click here</button>"
					+" to create one.</p><span></span>");
				}else $("#settingError").html(jsonObj.error);
		});
	}
	
	/*This function restrict user from setting emails for non-form article and 
	not allowing him to remove button from the form.*/
	read.prototype.validateRelationBetweenSubmitAndEmail = function(checkBox){
		var id = $(checkBox).attr("id");
		
		if(id == "isThisForm" && document.getElementById(id).checked == false  
			&& document.getElementById("setToEmail").checked == true)
		$("#settingError").html("-Only Forms can be emailed, So needs to make this is Article a form.");
		
		else if(id == "setToEmail" && document.getElementById("isThisForm").checked == false  
			&& document.getElementById(id).checked == true)
		$("#settingError").html("-You can't get email of non-form article");
		
		else $("#settingError").html("");
	}
	
	/*This function will send pdf version of current article to the given email id
	not completed yet*/
	read.prototype.sendEmail = function(){
		$("#settingError").text("");
		var inputString = $.trim($("#emailId").val());
		var url = this.baseUrl+"administrator/read/";
		var validate = new validator();
		
		if(validate.isEmail("emailId")){
			$.post(url+"sendEmail",{email:inputString, articleId:this.id, data:$("#readArticle").html()},
				function(result,status){
					var jsonObj = JSON.parse(result);
					if(jsonObj.result == "success")
						$("#settingError").html(jsonObj.msg);
					else $("#settingError").html(jsonObj.msg);
			});
		}
		else $("#settingError").html("Email id is not valid");
	}
	
	//create the parent drop down
	read.prototype.createParentDropDown = function(){
		var elementId = "subCategory";
		var parentId = "subCategoryLabel";
		var name = "subCategoryName";
		var text = "Sub Category Name:";
		var url = this.baseUrl+"administrator/read/";
		
		var connectedTo = "";	
		$('input[name="connectTo"]').each(function(index, element) {
			if ($(this).is(':checked') ) 
				connectedTo = $(element).val();
		});
			
		if(connectedTo == "category"){
			elementId = "category";
			parentId = "categoryLabel";
			name = "categoryName";
			text = "Category Name:";
		}
			
		//replce text field with the list of category and subcategory
		$.post(url+"getParentsList",{ data:connectedTo},
			function(elements,status){
				var jsonObj = JSON.parse(elements);
				var selectString = "<select id = '"+elementId+"' name = '"+name+"'>";
				for(var eachObj in jsonObj){
					if (jsonObj.hasOwnProperty(eachObj)){
					selectString += "<option value='"+jsonObj[eachObj].name
								 +"'>"+jsonObj[eachObj].name+"</option>";
					}
				}
				selectString += "</select>";
				$("#"+elementId).replaceWith(selectString);
		});

	}
	
	/*This method gathers the setting information and validate them; if validation 
	passes then return an array of settings info other wise return false*/
	read.prototype.validateSetting = function(){
		settings = new Object;
		var errorMessage = "";
		var errorOccured = false;
		var collection = document.getElementsByName("connectTo");
		
		$("#settingError").html("");
		
		for(var i=0; i<collection.length; i++){
			if(collection[i].checked)
				settings.connectedTo = collection[i].value;
		}
		
		if(settings.connectedTo == "category")
			settings.parentName = $("#category").val().trim();
		else settings.parentName = $("#subCategory").val().trim();
		
		if(settings.parentName == ""){
			errorMessage += "-Parent name is missing.<br />";
			errorOccured = true;	
		}
		
		if($("#articleName").val().trim() != "")
			settings.articleName = $("#articleName").val().trim();
		else{
			errorMessage += "-Article name is missing.<br />";
			errorOccured = true;
		}
		
		settings.articleTitle = $("#articleTitle").val().trim();
		settings.articleKeyword = $("#articleKeyword").val().trim();
		
		if(document.getElementById("isThisForm").checked){
			settings.isItForm = true;
			$("#button").html("<button type='button'>Submit</button>");
		}
		else {
			settings.isItForm = false;
			$("#button").html("");
		}
		
		//checking if user has set Email if true only than collect the send to email ids.
		if(document.getElementById("setToEmail").checked){
			if(settings.isItForm == false){
				errorMessage += "-Only Forms can be emailed, So needs to make this is Article a form.<br />";
				errorOccured = true;
			}
			
			settings.setToEmail = true;
			settings.sendTo = $("#sendTo").val().trim();
			settings.copyTo = $("#copyTo").val().trim();
			
			if(settings.sendTo == ""){
				errorMessage += "-You have indicated that form need to be emailed, but you hadn't mentioned the receipients.<br />";
				errorOccured = true;
			}
		}else settings.setToEmail = false;
		
		if(errorOccured == true){
			$("#settingError").html(errorMessage);
			return false;
		}
		
		return settings;
		
	}
	
	/*Send updated settings to the server throuigh Ajax*/
	read.prototype.updateSetting = function(settingInfo, callback){
		var url = this.baseUrl+"administrator/read/";
		$.post(url+"updateSetting",{ articleId:this.id, setting:settingInfo},
			function(message,status){
				var jsonObj = JSON.parse(message);
				if(jsonObj.response == "success"){
					$("#settingError").html(jsonObj.msg);
					callback(true);
				}
				else{
					$("#settingError").html(jsonObj.msg);
					callback(false);
				}
		});
	}
	

	
	
	
	
}

