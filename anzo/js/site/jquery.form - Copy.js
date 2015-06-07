// This is a JavaScript class that consist of functions required by every header of the front end.

function search_and_indicator(baseUrl) {
	
    this.baseUrl = baseUrl;
	var self = this;
	
	/*This function redirects the searct type and search string to 
	controller for processing*/
	search_and_indicator.prototype.searchPdfOrArticle = function(){
		var searchType = $("#searchDd").val().toLowerCase();
		var searchString = $("#searchTextbox").val().toLowerCase();
		
		if($.trim(searchString) != ""){
			window.location.href = this.baseUrl
						+"artery/home/loadSearchResultView/"
						+searchType+"/"+searchString;
		}
	}
	
	/*This function returns the colour name from the given associative aaray 
	for a given indicator id*/
	search_and_indicator.prototype.getIndicatorColour = function(indicatorArray, index){
		for(i=0; i<indicatorArray.length; i++){
			if(indicatorArray[i]['id'] == index)
				return indicatorArray[i]['colour'];
		}
	}
	
	/*This function sets the indicator for title, category and sub category. 
	It figure outs the severe condition in child list and show the condition
	on the parent.*/
	search_and_indicator.prototype.setIndicators = function(indicatorArray){
		
		//set the indicators for title
		var isRangedTitleArray = $(".navBar ul li.title").has("span.isRanged");
		$(isRangedTitleArray).each(function(index, element) {
			var title = 1;
            categoryArray = $(element).find("li.category");
			
			//set the indicators for category
			$(categoryArray).each(function(index, element) {
                var categoryIndicator = $(element).children("span.indicator").attr("data-value");
				if(categoryIndicator > title) title = categoryIndicator;
				
				//set the indicators for sub category
				subCategoryArray = $(element).find("li.subCategory");
				$(subCategoryArray).each(function(index, element) {
					var subCategoryIndicator = $(element).children("span.indicator").attr("data-value");
                    $(element).children("span.indicator").css({"background-color":self.getIndicatorColour(indicatorArray,
								 subCategoryIndicator)});
					if(subCategoryIndicator > categoryIndicator) categoryIndicator = subCategoryIndicator;
                });
				
				//set the back ground colour of span in current category
				$(element).children("span.indicator").css({"background-color":self.getIndicatorColour(indicatorArray,
								 categoryIndicator)});
				if(categoryIndicator > title) title = categoryIndicator;
				
            });
			
			//set the back ground colour of span in current title
			$(element).find("span.isRanged").css({"background-color":self.getIndicatorColour(indicatorArray, title)});
        });
	}
	
	
	
	
	
	
	
	
}

