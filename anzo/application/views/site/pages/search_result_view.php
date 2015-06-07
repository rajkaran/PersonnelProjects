<script type="text/javascript">
	$(document).ready(function(e) {
		

		
    });
	
</script>

<?php 
	$mtime = microtime();
	$mtime = explode(" ",$mtime);
	$mtime = $mtime[1] + $mtime[0];
	$endtime = $mtime;
	$totalTime = ($endtime - $startTime);
?>

<div class="contentSection">
    
	<div id="searchMessage">
    <p id="resultCount">About <?php echo count($searchResult); ?> results found (<?php echo round($totalTime,2); ?> seconds) </p>
    <p id="searchString">  <?php echo $screenMsg; ?>	</p>
    </div>
    
    <div id="searchResult">
            
    	<?php for($i=0; $i<count($searchResult); $i++){ ?>
        <div class="eachResult" id="eachResult">
        	<?php if(isset($searchResult[$i]["author"]) == true){ ?>
            	<a href='../../../../../../pdfDirectory/<?php echo $searchResult[$i]["fileName"]?>.pdf' target="_blank" >
					<?php echo str_replace("_", " ", $searchResult[$i]["fileName"]); ?>
                </a> <br />
            <?php } else{
				$level = ($searchResult[$i]["connectedTo"] == 1)?"category":"sub-category";
				$id = ($searchResult[$i]["connectedTo"] == 1)?$searchResult[$i]['categoryId']:$searchResult[$i]['subCategoryId'];
				echo anchor('anzo/display_article/loadArticles/'.$id.'/'.$level.'/'.$searchResult[$i]['id'].'', $searchResult[$i]["articleName"]);
			?><br /> 
            <?php }?>
            
            Title: <?php echo $searchResult[$i]["title"]; ?>&nbsp;&nbsp;&nbsp;&nbsp; 
            Creation Date: <?php echo $searchResult[$i]["creationDate"]; ?> <br />
            <?php if(isset($searchResult[$i]["author"]) == true){ ?>
            	By: <?php echo $searchResult[$i]["author"]; ?>
            <?php } else{?>
            	Keywords: <?php echo $searchResult[$i]["keyword"]; ?> 
            <?php }?>
		</div><hr />
	 	<?php }?>
    </div>

</div>