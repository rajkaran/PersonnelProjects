// This is a JavaScript class that consist of functions required by every header of the front end.

function slide_and_movement(baseUrl) {
	
    this.baseUrl = baseUrl;
	var self = this;
	var slideCounter = 0;
	var isbacked = false;
	var articleIdArray = Array();
	var currentIndex = -1;
	
	//This function sets the position of movement buttons on the y-Axis
	slide_and_movement.prototype.setNextAndBack = function(){
		var yAxis = $(".contentSection").offset().left+15;
		$("#backward").css("left",yAxis);
		$("#forward").css("left",yAxis+925);
	}
	
	/*This is a private function that creates the article by getting Article content through Ajax. 
	If article is a form then wrap article content in a form element with submit button. 
	At the same time sets the vertical length of article.*/
	slide_and_movement.prototype.getArticle = function(articleId, callback){
		var url = this.baseUrl+"anzo/";
		$.post(url+"display_article/createRawArticle/"+articleId,{ article:""},
			function(data,status){
				var jsonObj = JSON.parse(data);
				if(jsonObj.dataString != ""){
					
					if(jsonObj.isItForm == 1) 
						jsonObj.articleHeight = parseInt(jsonObj.articleHeight)+50;
					else jsonObj.articleHeight = parseInt(jsonObj.articleHeight)+10; 
						
					var slide = "<div class='article' id='"+articleId+"' data-name='"+jsonObj.name
							+"' style='height:"+jsonObj.articleHeight+"px; width:790px;'>";
								
					if(jsonObj.isItForm == 1){ 
						slide += "<form action='"+url+"form_submission/validateFormData' method='post'>"
							+" <input type='hidden' name='articleId' value='"+articleId+"' />";
					}
					
					slide += jsonObj.dataString;
					
					if(jsonObj.isItForm == 1){
					slide += "<div id='submit'><input type='submit' name='submit' value='Submit' /></div></form>";
					}
					
					slide += "</div>";
					callback(slide);
				}
		});
	}
	
	//This function creates the first slide 
	slide_and_movement.prototype.createFirstSlide = function(articleIdList){
		$("#slider").css("width",2*790+"px");
		this.articleIdArray = articleIdList;
		this.currentIndex = 0;
		
		//call to private function
		self.getArticle(this.articleIdArray[this.currentIndex], function(divSlide){
			$("#slider").append(divSlide);
			$("#breadCrumb span").text( $( "#"+self.articleIdArray[self.currentIndex] ).attr("data-name") );
		});
		
	}
	
	/*This function hides and shows the forward button depending on the position of 
	current article in slider. Function loads the next article and then makes the 
	slider moves one article forward.*/
	slide_and_movement.prototype.next = function() {
		if(this.currentIndex < this.articleIdArray.length && this.articleIdArray.length > 1){
			
			//going forward so the next article is called
			var currentArticleId = this.articleIdArray[this.currentIndex];
			this.currentIndex++;
			var nextArticleId = this.articleIdArray[this.currentIndex];
			
			/*if previous article was the first article than show back button
			if new article is the last article than hide next button*/
			if(this.currentIndex-1 == 0) $("#backward").show();
			else if ( this.currentIndex == this.articleIdArray.length-2) 
				$("#forward").hide();
			else $("#forward").show();
			
			// create animation of button click
			$("#forward img").attr("src", this.baseUrl+"img/forwardClick.png");
			setTimeout(function(){
					$("#forward img").attr("src", self.baseUrl+"img/forward.png");
			},300);
			
			/*call to private function to get next article content and show it through 
			sliding animation. To move forward we covers previous div by new one.*/ 
			self.getArticle(this.articleIdArray[this.currentIndex], function(divSlide){
				$("#slider").append(divSlide);
				
				$("#slider #"+currentArticleId).removeClass("coveringDiv");
				$("#slider #"+currentArticleId).addClass("divToHide");
				$("#slider #"+nextArticleId).addClass("coveringDiv");
				
				$("#slider #"+currentArticleId).animate({width:"0px"}, 
					500, 
					function(){
						$("#slider").children("#"+currentArticleId).remove();
						$("#slider").css({"height":"auto"});
				});
				$("#breadCrumb span").text( $( "#"+nextArticleId ).attr("data-name") );
			});
		}
	}
	
	/*This function hides and shows the backward button depending on the position 
	current article in slider. Function loads the previous article and then makes 
	the slider moves one article backward.*/
	slide_and_movement.prototype.previous = function(articleArray) {
		if(this.currentIndex > 0) {
			
			//going backward so the previous article is called
			var currentArticleId = this.articleIdArray[this.currentIndex];
			this.currentIndex--;
			var previousArticleId = this.articleIdArray[this.currentIndex];
			
			/*if current article was the last article than show next button
			if new article is the first article than hide back button*/
			if(this.currentIndex+1 == this.articleIdArray.length-1) $("#forward").show();
			else if ( this.currentIndex == 0) 
				$("#backward").hide();
			else $("#forward").show();
			
			// create animation of button click
			$("#backward img").attr("src", this.baseUrl+"img/backwardClick.png");
			setTimeout(function(){
					$("#backward img").attr("src", self.baseUrl+"img/backward.png");
			},300);
			
			/*call to private function to get previous article content and show it through 
			sliding animation. To show previous one we push current one out of the view.*/ 
			self.getArticle(this.articleIdArray[this.currentIndex], function(divSlide){
				$("#slider").prepend(divSlide);
				$("#slider #"+previousArticleId).css("width","0px");
				
				$("#slider #"+previousArticleId).animate({width:"790px"},
					500, 
					function(){
						$("#slider").children("#"+currentArticleId).remove();
						$("#slider").css("height","auto");
				});
				$("#breadCrumb span").text( $( "#"+previousArticleId ).attr("data-name") );
			});
		}
	}
	
	
	
	/*older code*/
	slide_and_movement.prototype.getArticleContent = function(id){
		var url = this.baseUrl+"anzo/";
		$.post(url+"display_article/createRawArticle",{ articleId:id},
			function(data,status){
				var jsonObj = JSON.parse(data);
				if(jsonObj.dataString != ""){
					
					if(jsonObj.isItForm == 1) 
						jsonObj.articleHeight = parseInt(jsonObj.articleHeight)+50;
					else jsonObj.articleHeight = parseInt(jsonObj.articleHeight)+10; 
						
					var slide = "<div class='article' id='"+id+"' data-name='"+jsonObj.name
							+"' style='height:"+jsonObj.articleHeight+"px;'>";
								
					if(jsonObj.isItForm == 1){ 
						slide += "<form action='"+url+"form_submission/validateFormData' method='post'>"
							+" <input type='hidden' name='articleId' value='"+id+"' />";
					}
					
					slide += jsonObj.dataString;
					
					if(jsonObj.isItForm == 1){
					slide += "<div id='submit'><input type='submit' name='submit' value='Submit' /></div></form>";
					}
					
					slide += "</div>";
					
					$("#slider").append(slide);
					$("#breadCrumb span").text( $("#slider").children(":first").attr("data-name") );
				}
		});
	}
	
	
	slide_and_movement.prototype.createSlides = function(articleArray){
		$("#slider").css("width",articleArray.length*790+"px");
		for($i=0; $i<articleArray.length; $i++){
			self.getArticleContent(articleArray[$i]);
		}
	}
	
	/*This function retrieves the article name according to the movement and 
	then shows it on the breadcrumb*/ 
	slide_and_movement.prototype.currentArticle = function(movement){
		var sliderWidth = parseInt( $("#slider").css("margin-left").replace("px","") );
		var currentArticle = 0;
		
		if(sliderWidth == 0) currentArticle = 1;
		else {
			if(movement == "forward") currentArticle = ( (sliderWidth*-1)/790)+1;
			else currentArticle = ( (sliderWidth*-1)/790)-1;
		}
		var currentArticleObject =  $("#slider>div")[currentArticle] ;
		var currentArticleName =  $(currentArticleObject).attr("data-name")
		$("#breadCrumb span").text(currentArticleName);
	}
	
	 
	slide_and_movement.prototype.moveForward = function(articleArray) {
		if(slideCounter<articleArray.length && articleArray.length>1){
			
			$("#forward img").attr("src", this.baseUrl+"img/forwardClick.png");
			
			if(slideCounter == 0){
				slideCounter ++;
				$("#slider").animate({marginLeft:-slideCounter*790+"px"}, 500);
				slideCounter ++;
				isbacked = false;
			}
			else {
				if(isbacked == true) slideCounter ++;
				$("#slider").animate({marginLeft:-slideCounter*790+"px"}, 500);
				slideCounter++;
				isbacked = false;
			}
			
			self.currentArticle("forward");
			
			setTimeout(function(){
					$("#forward img").attr("src", self.baseUrl+"img/forward.png");
				},300);
			
			if(slideCounter == articleArray.length) $("#forward").hide();
			else $("#backward").show();
		}
	}
	
	
	slide_and_movement.prototype.moveBackward = function(articleArray) {
		if(slideCounter > 0) {
			$("#backward img").attr("src", this.baseUrl+"img/backwardClick.png");
			
			if(isbacked == false) {
				slideCounter-=2;
				$("#slider").animate({marginLeft:-slideCounter*790+"px"},500);
				isbacked = true;
			}
			else {
				slideCounter-=1;
				$("#slider").animate({marginLeft:-slideCounter*790+"px"}, 500);
			}
			
			self.currentArticle("backward");
			
			setTimeout(function(){
					$("#backward img").attr("src", self.baseUrl+"img/backward.png");
				},300);
			
			if(slideCounter == 0) $("#backward").hide();
			else $("#forward").show();
			
		}
		
	}
	
	
	
	
	
	
	
}

