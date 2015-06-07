/* This is a JavaScript class that consist of fuctions required 
by list view.*/

function indexing(baseUrl) {
	
	this.baseUrl = baseUrl;
	this.dots;
	var self = this;
	
	//This function starts the indexing animation
	indexing.prototype.startAnim = function(element){
		element.html("<p>Indexing <span id='wait'></span></p>");
		this.dots = window.setInterval( function() {
			var wait = $(element).children("p").children("span");
			if ( wait.html().length > 8 ) wait.html("");
			else wait.html(wait.html()+".");
		}, 340);
	}
	
	//this function stops the animation
	indexing.prototype.stopAnim = function(element){
		clearInterval(this.dots);
		element.html("");
	}
	
	//Indexes pdf files or articles by Ajax call to particular methods
	indexing.prototype.indexArticleOrPdf = function(type, element){
		self.startAnim(element);
		var url = this.baseUrl+"administrator/upload_and_index/";
		var targetMethod = "indexArticles";
		if(type == "pdf") targetMethod = "indexPdfFiles";
		$.post(url+targetMethod,{pdf:""},
			function(result,status){
				var jsonObj = JSON.parse(result);
				self.stopAnim(element);
				element.html(jsonObj.msg);
		});
	}
	
	
	

	
	
	
	
}

