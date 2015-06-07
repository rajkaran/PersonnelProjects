<script type="text/javascript">
	$(document).ready(function(){
		$("#contentWrapper").css({"height":"auto"});
		$("#builder").css("height", "auto");
					
		var thisLevel = "<?php echo $thisLevel; ?>";
		var backLink = "<?php echo $backLink; ?>";
		var id = "<?php if(isset($info) == true) echo $info['id'] ?>";
		var articleId = "<?php if(isset($articleId) == true) echo $articleId ?>";
		var edit = new create_and_edit('<?php echo base_url() ?>');
		
		if(thisLevel == "article"){
			//calculate and apply article height
			edit.getArticleHeight(articleId);
			
			//getting article data and place it in edit mode on page
			edit.getArticleData("canvasPanel", articleId);
		}
		
		/*saving article, category, sub category data to the database 
		'on the click event on save button(link)'*/
		$("#save").unbind("click").click(function(event){
			event.preventDefault();
			$("#screenMessage").text("");//clear the error div
			if(thisLevel != "article"){
				edit.saveCategoryOrSubCategory(id, "update");
			}
			else edit.editArticleContent(articleId, backLink);
		});
		
		/*Populating drop downs when clicking on Title button or Category button*/
		$("#titleButton").unbind("click").click(function(e) {
			edit.populateParentDropDown("title", $("#title").val());
		});
		$("#categoryButton").unbind("click").click(function(e) {
			edit.populateParentDropDown("category", $("#category").val());
		});
		
		//repopulate category drop down for the selected title
		$('body').delegate('#title', 'change', function() {
			edit.checkTitleIsRanged("conditionEdit");
			if(thisLevel == "sub-category") 
				edit.populateParentDropDown("category", $("#category").val());
		});
		
	});
</script>

<div class="actionBar">
     
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/save.png" height="50px" width="51px" alt="my Profile" />
            <p>Save</p>
        ',array('id' => 'save'));?>
    </div>
    
    <div class="actionIcon">
    	<?php echo anchor($backLink,'
			<img src=" '.base_url().'/img/back.png" height="50px" width="72px" alt="my Profile" />
			<p>Back</p>
		');?>
    </div>
    
</div>


<div class="contentSection">

    <div id="screenMessage" class="builderError"></div>
    
    <?php if($thisLevel != "article"){ ?>
        <div id="parent" class="parent">
            <div class="name" >
                <label>Name: 
                    <input type="text" id="name" required="required" 
                    value="<?php echo $info['name'] ?>" />
                </label>
            </div>
            
            <div class="parentTitle" >
                <label>Parent Title: 
                    <input type="text" id="title" required="required"
                    value="<?php echo $info['title'] ?>" readonly="readonly" />
                </label>
                <input type="button" value="Change Title" id="titleButton" />
            </div>
            
            <?php if($thisLevel == "sub-category"){ ?>
            <div class="parentCategory" >
                <label>Parent Category: 
                    <input type="text" id="category" required="required" 
                    value="<?php echo $info['category'] ?>" readonly="readonly" />
                </label>
                <input type="button" value="Change Category" id="categoryButton" />
            </div>
            <?php } ?>
            
            <div class="isEnabled" >
                <label>Enable It: 
                    <select id="isEnabled" >
                        <option value="1" <?php if($info['status'] == '1')echo 'selected="selected"'; ?> >Yes</option>
                        <option value="0" <?php if($info['status'] == '0')echo 'selected="selected"'; ?> >No</option>
                     </select>
                 </label>
           </div>
           
           <div class="conditionEdit" >
           		<?php if(isset($info['conditionDropDown']) ){ ?>
                <label>Condition: <?php echo $info['conditionDropDown'] ?> </label>
                <?php } ?>
           </div>
           
        </div>
    <?php }?>
    
    <div id="builder" class="builder"> </div>

</div>
