<script type="text/javascript">
	var id = "<?php if(isset($articleId) == true) echo $articleId ?>";
	var articleReadMode = new read('<?php echo base_url() ?>', id);
	
	$(document).ready(function(){
		$("#contentWrapper").css({"height":"auto"});
		$(".contentSection").css({"height":"auto"});
		
		/*calculate and apply article height*/
		articleReadMode.getAndSetArticleHeight();
		
		//retrieve article info and place it in popup
		articleReadMode.getAndSetArticleSettings();
		
		/*This function opens the popup and let the user make changes to the article settings*/
		$("#setting").unbind("click").click(function(event){
			event.preventDefault();
			$("#settingError").html("");
			$( "#settingDialogBox" ).dialog({
				width: 520,
				modal: true,
				title:"Settings",
				buttons: [{
					text: "Update",
					click: function() {
						var settingInfo = articleReadMode.validateSetting();
						if(settingInfo != false){
							articleReadMode.updateSetting(settingInfo, function(saved){
								if(saved == true)location.reload();
							});
						}
					}
				}]
			});
			
		});
		
		
	});
</script>

<!--Show the action bar at the top-->
<div class="actionBar">
    <div class="actionIcon">
        <?php echo anchor('administrator/create_and_edit/editArticle/'.$previousLevel.'/'.$articleId.'/','
            <img src=" '.base_url().'img/edit_.png" height="50px" width="50px" alt="my Profile" />
            <p>Edit</p>
        ');?>
    </div>

    <div class="actionIcon pdfIcon">
        <?php echo anchor($pdfLink,'
            <img src=" '.base_url().'img/pdf.png" height="50px" width="51px" alt="my Profile" />
            <p>Pdf</p>
        ',array('id' => 'pdf'));?>
    </div>
    
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/setting.png" height="50px" width="46px" alt="my Profile" />
            <p>Settings</p>
        ',array('id' => 'setting'));?>
    </div>
    
    <div class="actionIcon">
    	<?php echo anchor($backLink,'
		<img src=" '.base_url().'img/back.png" height="50px" width="72px" alt="my Profile" />
		<p>Back</p>
	');?>
    </div>
    
</div>


<div class="contentSection">

	<!--Div in which article data will be placed-->
	<div id="readArticle" class="readArticle"><?php echo $articleData;  ?></div>
    
    <!--setting Pop up-->
    <div id="settingDialogBox" class="settingDialogBox">
    	<!--Show errors occured during updating settings-->
    	<div id="settingError" class="settingError"></div>
        <fieldset class="connect">
            <legend>Connected To:</legend>
            <div><label class="radioLabel"> <input type="radio" name="connectTo" id="toCategory" 
                    value="category" onchange="articleReadMode.toggleParent(this)" /> Category </label>
            </div>
            
            <div><label class="radioLabel"> <input type="radio" name="connectTo" id="toSubCategory" 
                    value="subCategory" onchange="articleReadMode.toggleParent(this)" /> Sub - Category</label>
            </div>
            
            <div class="parent"><label id="categoryLabel"> Category Name: <input type="text" 
                    name="categoryName" id="category" value="" disabled="disabled"/> </label>
            </div>
            
            <div class="parent"><label id="subCategoryLabel"> Sub - Category Name: <input 
                    type="text" name="subCategoryName" id="subCategory" value="" disabled="disabled"/> </label>
            </div>
            
            <div id="chooseParent"> <input type="button" value="Choose" onclick="articleReadMode.createParentDropDown()" /> </div>
        </fieldset>
        
        <fieldset class="info">
            <legend>Article Info:</legend>
            <div><label> Name: <input type="text" name="articleName" id="articleName" /> </label></div>
            
            <div><label> Title: <input type="text" name="articleTitle" id="articleTitle" /> </label></div>
            
            <div><label>Keywords:<input type="text" name="articleKeyword" id="articleKeyword" /></label></div>
        </fieldset>
        
        <fieldset class="sendNow">
            <legend>Create and send Pdfs:</legend>
            <div id="pdfExist" class="pdf"></div>
            
            <div><label>Email It Now:<input type="email" name="emailId" id="emailId" placeholder="john@wgh.ca"/>
                <input type="button" onclick="articleReadMode.sendEmail()" id="send" value="Send" /></label>
            </div>
        </fieldset>
        
        <fieldset class="setup">
            <legend>Form and Email Settings:</legend>
            <div><label> <input type="checkbox" name="isThisForm" id="isThisForm" value="true" 
                    onchange="articleReadMode.validateRelationBetweenSubmitAndEmail(this)" /> This is a Form and requires Submit 
                    button. </label>
            </div>
            
            <div><label> <input type="checkbox" name="setToEmail" id="setToEmail" value="true" 
                    onchange="articleReadMode.validateRelationBetweenSubmitAndEmail(this)" /> Wish to receive it&acute;s submitted
                     copy. </label>
            </div>
            
            <div class="email"><label for="sendTo">Send To:</label><textarea id="sendTo"></textarea></div>
            <div class="email"><label for="copyTo">Copy To:</label><textarea id="copyTo"></textarea></div>
        </fieldset>
        
    </div>
	
    <!--Div to show submit button-->
	<div id="submitButton"></div>
    
</div>
