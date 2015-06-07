<script type="text/javascript">
    $(document).ready(function(){
        $("#contentWrapper").css({"height":"auto"});
		
		var lists = new listing('<?php echo base_url() ?>');
		var level = "<?php echo $thisLevel ?>";
		
		$("#disable").unbind("click").click(function(e) {
            e.preventDefault();
			lists.enableOrDisableLevels("disable", level);
        });
		
		$("#enable").unbind("click").click(function(e) {
            e.preventDefault();
			lists.enableOrDisableLevels("enable", level);
        });
        
    });
</script>

<!--Show the action bar at the top-->
<div class="actionBar">
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/disable.png" height="50px" width="50px" alt="my Profile" />
            <p>Disable</p>
        ',array('id' => 'disable'));?>
    </div>
    
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/enable.png" height="50px" width="50px" alt="my Profile" />
            <p>Enable</p>
        ',array('id' => 'enable'));?>
    </div>
    
    <?php if($thisLevel == "categoryList"){ ?>
    <div class="actionIcon">
    <?php echo anchor('administrator/create_and_edit/createCategoryAndSubCategory/'.$thisLevel,'
        <img src=" '.base_url().'img/add_new.png" height="50px" width="49px" alt="my Profile" />
        <p>Category</p>
    ');?>
    </div>
    <?php } ?>
    
    <?php if($thisLevel == "category" || $thisLevel == "sub-categoryList"){ ?>
    <?php $id=0; if($thisLevel == "category") $id=$levelInfo['id']; ?>
    <div class="actionIcon">
    <?php echo anchor('administrator/create_and_edit/createCategoryAndSubCategory/'.$thisLevel.'/'.$id.'','
        <img src=" '.base_url().'img/add_new.png" height="50px" width="49px" alt="my Profile" />
        <p>Sub Category</p>
    ');?>
    </div>
    <?php } ?>
    
    <?php if($thisLevel == "category" || $thisLevel == "sub-category" || $thisLevel == "articleList"){ ?>
    <?php $id=0; if($thisLevel == "category" || $thisLevel == "sub-category" ) $id=$levelInfo['id']; ?>
    <div class="actionIcon">
    <?php echo anchor('administrator/create_and_edit/createArticle/'.$previousLevel.'/'.$id.'','
        <img src=" '.base_url().'img/add_new.png" height="50px" width="49px" alt="my Profile" />
        <p>Article</p>
    ');?>
    </div>
    <?php } ?>
     
    <?php if($thisLevel == "category" || $thisLevel == "sub-category"){ ?>
    <?php $id=0; $id=$levelInfo['id']; ?>
    <div class="actionIcon">
    <?php echo anchor('administrator/create_and_edit/editCategoryAndSubCategory/'.$previousLevel.'/'.$id.'','
        <img src=" '.base_url().'img/edit_.png" height="50px" width="49px" alt="my Profile" />
        <p>Edit</p>
    ');?>
    </div>
    <?php } ?>
        
    <div class="actionIcon">
    	<?php echo anchor($backLink,'
			<img src=" '.base_url().'img/back.png" height="50px" width="72px" alt="my Profile" />
			<p>Back</p>
		');?>
    </div>
    
</div>

<div class="contentSection">
	
    <!--Showing categopry and sub category in read mode-->
	<?php if(isset($levelInfo) == true){ ?>
    
    
    
    	<div class="levelInfo">
        
            <div class="info">
            	<label>Name: 
                	<input type="text" class="name" readonly="readonly" value="<?php echo $levelInfo['name'] ?>" /> 
                </label>
            </div>
            
        	<div class="info">
            	<label>Parent Title: 
                	<input type="text" class="title" readonly="readonly" value="<?php echo $levelInfo['title'] ?>" /> 
                </label>
            </div>
            
            <?php if($thisLevel == "sub-category"){ ?>
                <div class="info">
                    <label>Parent Category: 
                    	<input type="text" class="category" readonly="readonly" value="<?php echo $levelInfo['category'] ?>" /> 
                    </label>
                </div>
            <?php } ?>
            
            
            
            <div class="info">
            	<label>Is Enabled? 
                	<span class="isEnabled"> <?php echo toglleImage($levelInfo['status']) ?> </span> 
                </label>
            </div>
            
            <div class="info">
            	<label>Number of Article it has: 
                	<input type="text" class="articleCount" readonly="readonly" value="<?php echo $levelInfo['articleCount'] ?>" /> 
                </label>
            </div>
            
            <?php if($thisLevel == "category"){ ?>
                <div class="info">
                    <label>Number of Sub Categories it has: 
                    	<input type="text" class="subCategoriesCount" readonly="readonly" 
                        	value="<?php echo $levelInfo['subCategoryCount'] ?>" /> 
                    </label>
                </div>
            <?php } ?>
            
            <div class="info">
            	<label>Condition: 
                	<input type="text" class="condition" readonly="readonly" 
                    	value="<?php echo $levelInfo['condition'] ?>" /> 
                </label>
            </div>
            
            <div class="info">
            	<label>Creation Date: 
                	<input type="text" class="creationDate" readonly="readonly" 
                    	value="<?php echo $levelInfo['creationDate'] ?>" /> 
                </label>
            </div>
        	
        </div>
    <?php } ?>
    
    <div id="errorMsg"> </div>
	
    <div class="searchBar">
        <input type="text" id="seachTextBox" name="seachTextBox" />
        <input type="submit" id="searchButton" name="searchButton" value="Search" />
    </div>
    
    <div class="resultList">
        <table class="mytable">
            <!--Heading for the table-->
            <tr>
                <th>Serial<br />No.</th>
                <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                <th class="name">Name</th>
                
                 <?php if($thisLevel == "articleList") { ?>
                	<th>Parent</th>
                <?php } ?>
                
                 <?php if(isset($levelInfo) == false) { ?>
                	<th>Title</th>
                 <?php } ?>
                 
                <?php if($thisLevel == "sub-categoryList"){ ?>
                	<th class="category">Category</th>
                <?php } ?>
                
                <th>Enabled</th>
                
                <?php if($thisLevel == "articleList") { ?>
                	<th>has Pdf</th>
                	<th>It's Form</th>
                <?php } ?>
                
                <th>Creation Date</th>
                
                <?php if($thisLevel == "categoryList" ){ ?>
                	<th>Number<br />of Sub<br />Categories</th>
                <?php } ?>
                
                <?php if($thisLevel != "articleList" && isset($levelInfo) == false){?>
                	<th>Number of<br />Articles</th>
                <?php } ?>
                
                <?php if(isset($levelInfo) == true){?>
                	<th>Type</th>
                <?php } ?>
            </tr>
            
            <!--Displaying data in the table layout-->
            <?php for($i=0; $i<count($list); $i++){ ?>
                <tr class="retrievedRows">
                    <td><?php echo $i;?></td>
                    <td><input type="checkbox" value="<?php echo $list[$i]['id']; ?>" name="rowId" 
                    		data-type="<?php if(isset($list[$i]['type'])==true) echo $list[$i]['type'] ?>" /></td>
                    <td class="name">
                        <?php
                        if(isset($levelInfo) == true){
                            if($list[$i]['type'] == "Article"){
                                echo anchor('administrator/read/articleReadMode/'.$previousLevel.'/'.$list[$i]['id'], $list[$i]['name']);
                            }else{
                                echo anchor('administrator/listing/loadLevelList/'.$thisLevel.'/'.$list[$i]['id'], $list[$i]['name']);
                            }
                        }
                        
                        //set the link for names on direct access to levels from dash board
                        if($thisLevel == "categoryList")
                            echo anchor('administrator/listing/loadLevelList/'.$thisLevel.'/'.$list[$i]['id'], $list[$i]['name']);
                        if($thisLevel == "sub-categoryList")
                            echo anchor('administrator/listing/loadLevelList/'.$thisLevel.'/'.$list[$i]['id'], $list[$i]['name']);
                        if($thisLevel == "articleList")
                            echo anchor('administrator/read/articleReadMode/'.$thisLevel.'/'.$list[$i]['id'], $list[$i]['name']);
                             
                        ?>
                    </td>
                    
                    <!--getting it only in article case-->
                    <?php if($thisLevel == "articleList") { ?>
                        <td class="title"><strong><?php echo $list[$i]['connectedTo']; ?></strong>&nbsp;:&nbsp;
								<?php echo $list[$i]['parent']; ?></td>
                    <?php } ?>
                    
                    <!--it is required in all direct case-->
                     <?php if(isset($levelInfo) == false ) { ?>
                        <td class="title"><?php echo $list[$i]['title']; ?></td>
                     <?php } ?>
                    
                    <!--required only in subcategory both direct and indirect-->
                    <?php if($thisLevel == "sub-categoryList"){ ?>
                        <td class="category"><?php echo $list[$i]['category']; ?></td>
                    <?php } ?>
                    
                    <!--it si required in every case-->
                    <td id="<?php echo $list[$i]['id']; ?>"><?php echo toglleImage($list[$i]['status']); ?></td>
                    
                    <!--required only in direct article case-->
                    <?php if($thisLevel == "articleList") { ?>
                        <td><?php echo toglleImage($list[$i]['havePdfVersion']); ?></td>
                        <td><?php echo toglleImage($list[$i]['isItForm']); ?></td>
                    <?php } ?>
                    
                    <!--it is required in evry case-->
                    <td><?php echo $list[$i]['creationDate']; ?></td>
                    
                    <!--required only in category case direct and indirect-->
                    <?php if($thisLevel == "categoryList"){ ?>
                        <td><?php echo $list[$i]['subCategoryCount']; ?></td>
                    <?php } ?>
                    
                    <!--   required only in categoryand sub category case direct and indirect-->
                    <?php if($thisLevel != "articleList" && isset($levelInfo) == false){ ?>
                    <td><?php echo $list[$i]['articleCount']; ?></td>
                    <?php } ?>
                     
                    <!--only required for indirect article and sub category -->
                    <?php if(isset($levelInfo) == true && $thisLevel != "articleList"){ ?>
                    <td class="title"><?php echo $list[$i]['type']; ?></td>
                    <?php } ?>
                    
                </tr>
			<?php } ?>
            
            
            <tr>
            	<td colspan="9" class="links"><?php echo $links; ?></td>
            </tr>
        </table>
    </div>

</div>

