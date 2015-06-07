<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title; ?></title>
<script type='text/javascript' src='<?php echo base_url() ?>/js/formBuilder/jquery-1.9.1.js' ></script>
<?php if(isset($scriptAndStyle))echo $scriptAndStyle; ?>

<script type="text/javascript">
	var indicator = new search_and_indicator('<?php echo base_url() ?>');
	$(document).ready(function(e) {
		var indicatorArray = <?php echo json_encode($indicatorArray); ?>;
		indicator.setIndicators(indicatorArray);
    });
</script>


</head>

<body>
	<div class="pageWrapper">
    	
        <!-- Back end's header -->
        <div class="header">
            <div class="siteStrip">
            	<div class="siteName">
                	<!--div><img src="<?php echo base_url(); ?>/img/the.png" alt="The" height="80" width="80" /></div>
                	<div><img src="<?php echo base_url(); ?>/img/A.gif" alt="A" height="80" width="74"  /></div>
                    <div><img src="<?php echo base_url(); ?>/img/R.gif" alt="R" height="80" width="74"  /></div>
                    <div><img src="<?php echo base_url(); ?>/img/T.gif" alt="T" height="80" width="60"  /></div>
                    <div><img src="<?php echo base_url(); ?>/img/E.gif" alt="E" height="80" width="68"  /></div>
                    <div><img src="<?php echo base_url(); ?>/img/R.gif" alt="R" height="80" width="74"  /></div>
                    <div><img src="<?php echo base_url(); ?>/img/Y.gif" alt="Y" height="80" width="74"  /></div-->
					<div><img src="<?php echo base_url(); ?>/img/anzo-logo200x75.png" alt="The" height="80" width="160" /></div>
                </div>
                
                <!--div class="pulse">
                    <img src="<?php echo base_url(); ?>/img/ekg3.gif" alt="heart pulse image" height="40" width="485"  />
                 </div-->
                 
                <div class="pull-right">
					<div class="searchBlock">
						<span>Search</span>
						<select class="searchDd" id="searchDd">
							<option value="pdf" selected="selected"> Pdf </option>
							<option value="page"> Page </option>
						</select>
						<input type="text" id="searchTextbox" class="searchTextbox" placeholder="Search" />
						<button type="button" onclick="indicator.searchPdfOrArticle()" id="searchButton" class="search" ></button>
					</div>
					
					<div class="homeLink">
						<span><?php echo anchor('anzo/home/index','Home',array('title' => 'Return to Home Page'))?></span>
					</div>
				</div>
            </div>
				
            
            <div class="navStrip">
            	<div class="navBar">
                	<!--this ul for titles-->
                    <ul>
                    	<?php for($i=0; $i<count($titleArray); $i++){ ?>
                        <li class="title" >
                        	<span>
                            	<?php if($titleArray[$i]['isRanged'] == 1) echo "<span class='isRanged' >&nbsp;</span>" ?>
								<?php echo $titleArray[$i]['title'] ?>
                            </span>
                            <!--this ul for categories-->
                            <ul>
								<?php foreach($categoryArray[$titleArray[$i]['title']] as $key=> $category){ ?>
                                <li class="category">
                                	<?php if($titleArray[$i]['isRanged'] == 1){ 
										echo "<span class='indicator' data-value='".$category['condition']."' >&nbsp;</span>" 
                                    			.anchor("anzo/display_article/loadArticles/".$key, $category['name'], 
													array("class" => "condition")); 
									}else
										echo anchor("anzo/display_article/loadArticles/".$key, $category['name']);?>
                                    
                                    <?php if(count($subCategoryArray[$category['name']]) > 0){ ?>
                                    <span class="arrow">&gt;&gt;</span>
                                    <!--this ul for sub categories-->
                                    <ul>
                                        <?php foreach($subCategoryArray[$category['name']] as $key=> $subCategory){ ?>
                                        <li class="subCategory">
                                            <?php if($titleArray[$i]['isRanged'] == 1){ 
												echo "<span class='indicator' data-value='"
													.$subCategory['condition']."' >&nbsp;</span>" 
													.anchor("anzo/display_article/loadArticles/".$key."/sub-category",
														 $subCategory['name'],array("class" => "condition")); 
											}else
												echo anchor("anzo/display_article/loadArticles/".$key."/sub-category", $subCategory['name']);?>
											
                                        </li>
                                        <?php } ?>
                                    </ul>
                                    <?php } ?> 
                                
                                </li>
                                <?php } ?>
                            </ul>
                            
                        </li>
                        <?php } ?>
                    </ul>
                    
                </div>
            </div>
        
        <!--end of header-->    	
        </div>
        
        
        <!-- Major container of body where all pages came in -->
        <div class="containerWrapper" id="contentWrapper">