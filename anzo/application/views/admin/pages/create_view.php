<script type="text/javascript">
	$(document).ready(function(){
		$("#contentWrapper").css({"height":"auto"});
		$("#builder").css("height", "auto");
		
		var create = new create_and_edit('<?php echo base_url() ?>');
		var id = "<?php if(isset($info['id']) == true) echo $info['id'] ?>";
		var thisLevel = "<?php echo $thisLevel ?>";
		var previousLevel = "<?php echo $previousLevel ?>";
		
		//Populate drop downs when page loads
		create.initializeDropDowns(thisLevel, previousLevel);
		
		/*saving article, category, sub category data to the database 
		'on the click event on save button(link)'*/
		$("#save").unbind("click").click(function(event){
			event.preventDefault();
			$("#screenMessage").text("");//clear the error div
			
			if(thisLevel != "article")
				create.saveCategoryOrSubCategory(id, "create");
			else create.saveArticle();
		});
		
		//populate child drop downs on the selection change event of title drop down
		$('body').delegate('#title', 'change', function() {
			if( thisLevel == "sub-category" && previousLevel == "sub-categoryList" ){
				create.populateParentDropDown("category", "");
			}
			
			if( thisLevel == "article" && previousLevel == false ){
				create.populateParentDropDown("category", "", function(ret){
					create.populateParentDropDown("sub-category", "");
				});
			}
			
			if( (thisLevel == "category" && previousLevel == "categoryList") || 
				(thisLevel == "sub-category" && previousLevel == "sub-categoryList") ){
					create.checkTitleIsRanged("condition");
			}
		});
		
		//populate child drop downs on the selection change event of category drop down
		$('body').delegate('#category', 'change', function() {
			if( thisLevel == "article" && previousLevel == false ){
				create.populateParentDropDown("sub-category", "");
			}
		});
		
		$(".condition").has("#isRanged").show();
			
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
			<img src=" '.base_url().'img/back.png" height="50px" width="72px" alt="my Profile" />
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
                    <input type="text" id="name" required='required' 
                    value="" />
                </label>
            </div>
            
            <div class="parentTitle" >
                <label>Parent Title: 
                    <input type="text" id="title" required="required"
                    value="<?php if(isset($info['title']) ==true)echo $info['title'] ?>" readonly="readonly" />
                </label>
            </div>
            
            <?php if($thisLevel == "sub-category"){ ?>
            <div class="parentCategory" >
                <label>Parent Category: 
                    <input type="text" id="category" required="required" 
                    value="<?php if(isset($info['name']) ==true)echo $info['name'] ?>" readonly="readonly"  />
                </label>
            </div>
            <?php } ?>
            
            <div class="isEnabled" >
                <label>Enable It: 
                    <select id="isEnabled" >
                        <option value="1" >Yes</option>
                        <option value="0" >No</option>
                     </select>
                 </label>
           </div>
           
           <div class="condition" >
                <label>Condition: <?php if(isset($info['conditionDropDown']) ==true)echo $info['conditionDropDown'] ?></label>
           </div>
           
        </div>
    <?php }?>
    
    <?php if($thisLevel == "article"){ ?>
    
        <div class="relatedInfo">
            <div  class="parent">
                <label>
                    Title:
                    <input type="text" id="title" value="<?php if(isset($info)==true)echo $info['title']; ?>" readonly="readonly" />   
                </label>
                <br />
                
                <label>
                    category:
                     <input type="text" id="category" 
                     	value="<?php 
								if(isset($info)==true){
									echo ($previousLevel == "category" || $previousLevel == "sub-categoryList")?$info['category']:$info['name'];
									//echo $info['name'];
								} 
							  ?>" readonly="readonly" />   
                </label>
                 
                <br />
                <?php if($previousLevel == "category" || $previousLevel == false || $previousLevel == "sub-categoryList"){ ?>
                <label>
                    Sub - Category:
                    <input type="text" id="sub-category" value="<?php if(isset($info)==true)echo $info['name']; ?>" readonly="readonly" />    
                </label>
                <?php } ?>
            </div>
            
            <div class="articleInfo">
                <label >
                    Article Name:
                    <input type="text" name="articleName" id="articleName" />    
                </label>
                <br />
                <label>
                    Article Title:
                    <input type="text" name="articleTitle" id="articleTitle" />    
                </label>
                <br />
                <label>
                    Article Keywords:
                    <input type="text" name="articleKeyword" id="articleKeyword" />    
                </label>
            </div>
        </div>
        
        <div id="builder" class="builder"> </div>
    <?php } ?>

</div>
