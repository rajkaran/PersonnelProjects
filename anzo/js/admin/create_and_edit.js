// This is a JavaScript class that consist of fuctions required by create and edit view.

function create_and_edit(baseUrl) {
	
    this.baseUrl = baseUrl;
	var self = this;
	
	/*This function gets the list of titles and category and populate the 
	corresponding drop down with that list*/
	create_and_edit.prototype.populateParentDropDown = function(change, currentValue, callback){
		var textBox = "title";
		var title = "";
		var category = "";
		var targetMethod = "createTitleDropDown";
		var url = this.baseUrl+"administrator/create_and_edit/";
		var categoryDdCreated = false;
		
		if(change == "category"){
			textBox = "category";
			title = $("#title").val();
			targetMethod = "createCategoryDropDown";
		}
		
		if(change == "sub-category"){
			textBox = "sub-category";
			category = $("#category").val();
			targetMethod = "createSubCategoryDropDown";
		}
		
		$.post(url+targetMethod,{currentValue:currentValue, title:title, category:category},
			function(elementString,status){
				var jsonObj = JSON.parse(elementString);
				$("#"+textBox).replaceWith(jsonObj.dataString);
				
				if(callback) callback();
		});
	};
	
	/*This function initializes the required drop downs when page gets loaded.*/
	create_and_edit.prototype.initializeDropDowns = function(thisLevel, previousLevel){
		
		if( thisLevel == "category" && previousLevel == "categoryList" )
			self.populateParentDropDown("title", "");
		
		if( thisLevel == "sub-category" && previousLevel == "sub-categoryList" ){
			self.populateParentDropDown("title", "", function(ret){
				self.populateParentDropDown("category", "");
			});
		}
		
		if( thisLevel == "article" && previousLevel == false ){
			self.populateParentDropDown("title", "", function(ret1){
				self.populateParentDropDown("category", "", function(ret){
					self.populateParentDropDown("sub-category", "");
				});
			});
		}
	};
	
	/*creating an array of article info, validate it and throw error;
	this array will go into controller to save array data into database*/
	create_and_edit.prototype.articleInfo = function(){
		var result = new Object();
		var errorList = "";
		var validate = new validator();
		
		result['title'] =  $("#title").val();
		result['category'] =  $("#category").val();
		
		if($("#sub-category").val() != undefined)
			result['sub-category'] =  $("#sub-category").val();
		else result['sub-category'] =  "";
		
		if( validate.isEmpty( $.trim($("#articleName").val()) ) ){
			errorList = "Article Name is a required field.";
			isErrorOccured = true;
		} else result['articleName'] =  $("#articleName").val();
		
		result['articleKeyword'] = $("#articleKeyword").val(); 
		result['articleTitle'] = $("#articleTitle").val();
		
		return ( validate.isEmpty(errorList) )? [true, result]: [false, errorList];
	}
	
	create_and_edit.prototype.saveArticle = function(){
		var url = this.baseUrl+"administrator/";
		
		//create associative array of user provided data
		if( self.articleInfo()[0] == true ){
			var data = getCanvasData("screenMessage");//call form builder function
			
			//sending article data and info to the controller through ajax
			if(data && data.length > 0){
				$.post(url+"create_and_edit/saveArticle",{ data:data, articleInfo:self.articleInfo()[1]},
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.msg == "success"){
						var id = jsonObj.articleId;
						window.location.replace(url+"read/articleReadMode/articleList/"+id);
					}
					else if(jsonObj.msg == "exist"){
						$("#screenMessage").html("Article with the same name already exist.");
					}
					else {
						$("#screenMessage").html("Some error occured while creating"
								+" article Try again or inform this bug to developers.");
					}
					
				});
			}
			else if(data) $("#screenMessage").html("There is no Article data to save.");
		}else $("#screenMessage").html(self.articleInfo()[1]);
	};
	
	/*Updating Category and Sub category data in the database through Ajax*/
	create_and_edit.prototype.saveCategoryOrSubCategory = function(id, action){
		var dataArray = new Object();		
		var url = this.baseUrl+"administrator/";
		var errorList = "";
		var validate = new validator();
		var targetMethod = "createCategoryOrSubCategory";
		
		dataArray['name'] = $("#name").val();
		dataArray['title'] = $("#title").val();
		dataArray['category'] = "";
		dataArray['isEnabled'] = $("#isEnabled").val();
		dataArray['isRanged'] = $("#isRanged").val();
		
		if( $("#category").val() )
			dataArray['category'] = $("#category").val();
		
		if( validate.isEmpty( $.trim( $("#name").val() ) ) )
			errorList = "The Name field is required.";
			
		if(action == "update")
			targetMethod = "editCategoryOrSubCategory";
		
		if( validate.isEmpty(errorList) ){
			$.post(url+"create_and_edit/"+targetMethod,{data:dataArray, id:id},
				function(response,status){
					var jsonObj = JSON.parse(response);					
					if(jsonObj.msg == "success" && action == "create")
						window.location.replace(url+"listing/loadLevelList/"+jsonObj.level+"/"+jsonObj.id);
					else if(jsonObj.msg == "success" && action == "update")
						$("#screenMessage").html("<span style='color:#15777a;'>This "+jsonObj.actingOn+" has been updated</span>");
					else if(jsonObj.msg == "exist")
						$("#screenMessage").html(jsonObj.actingOn+" with the same name already exist.");
					else{
						$("#screenMessage").html("Some error occured while creating "
									+jsonObj.actingOn+" Try again or inform this bug to developers.");
					}
			});
		}else $("#screenMessage").html(errorList);
	}
	
	/*This function checks whether isRanged flag for current title is set*/
	create_and_edit.prototype.checkTitleIsRanged = function (divClass){
		var selectedTitle = $("#title").val();
		var url = this.baseUrl+"administrator/create_and_edit/";
		
		$.post(url+"createConditionDropDownForTitle",{title:selectedTitle},
			function(elementString,status){
				var jsonObj = JSON.parse(elementString);
					if(jsonObj.isRangedFlag == 1) {
						if(divClass == "condition")
							$("."+divClass+" label").append(jsonObj.dropDownString);
						else 
							$("."+divClass).html("<label>Condition:"+jsonObj.dropDownString+"</label>");
						$("."+divClass).show();
					}
					else if(jsonObj.isRangedFlag == 0) $("."+divClass).hide();
					else $("#screenMessage").html(jsonObj.isRangedFlag);
		});
	}
	
	/*This function edits the article content. Function call form bulider function
	to get article content and then forward that content to server through Ajax*/
	create_and_edit.prototype.editArticleContent = function (id, backLink){
		var data = getCanvasData("screenMessage");//call form builder function
		var url = this.baseUrl+"administrator/";
		
		//sending article data and info to the controller through ajax
		if(data && data.length > 0){
			$.post(url+"create_and_edit/updateArticle",{ data:data,articleId:id},
				function(data,status){
					var jsonObj = JSON.parse(data);
					if(jsonObj.msg == "success"){
						var id = jsonObj.articleId;
						window.location.replace(self.baseUrl+backLink);
					}
					else {
						$("#screenMessage").html("Some error occured while creating"
								+" article Try again or inform this bug to developers.");
					}
			});
		}
		else if(data) $("#screenMessage").html("There is no Article data to save.");
	}
	
	/*calculating the height of article in ordeer to resize the canvas.*/
	create_and_edit.prototype.getArticleHeight = function(id){
		var url = this.baseUrl+"administrator/create_and_edit/";
		$.post(url+"getArticleHeight",{ articleId:id},
			function(bottom,status){
				var jsonObj = JSON.parse(bottom);
				var bottomEnd = parseInt(jsonObj.articleLength)+10;
				if(bottomEnd < 470)
					$("#canvasPanel").css("height", "470px");
				else $("#canvasPanel").css("height", bottomEnd+"px");
		});
	}
	
	/*Retrieve article data from the database through Ajax call*/
	create_and_edit.prototype.getArticleData = function(targetDiv, id){
		var url = this.baseUrl+"administrator/read/";
		$.post(url+"createRawArticle/"+id,{ data:"null"},
			function(elements,status){
				var jsonObj = JSON.parse(elements);
				$("#"+targetDiv).html(jsonObj.dataString);
		});
	}
	
	
	
	
	
	
	
}

