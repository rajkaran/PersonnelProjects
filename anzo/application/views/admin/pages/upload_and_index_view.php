<script type="text/javascript">
	$(document).ready(function(e) {
		var index = new indexing('<?php echo base_url() ?>');
		
		$("#indexArticle").unbind("click").click(function(event){
			event.preventDefault();
			index.indexArticleOrPdf("article", $(".indexingMsg"));
		});
		
		$("#indexPdf").unbind("click").click(function(event){
			event.preventDefault();
			index.indexArticleOrPdf("pdf", $(".indexingMsg"));
		});
		
    });
	
</script>

<!--Show the action bar at the top-->
<div class="actionBar">
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/index_icon.png" height="50px" width="50px" alt="Index Articles" />
            <p>Articles</p>
        ',array('id' => 'indexArticle'));?>
    </div>
    
    <div class="actionIcon">
        <?php echo anchor('manage_user/loadProfile','
            <img src=" '.base_url().'img/index_icon.png" height="50px" width="50px" alt="Index PDFs" />
            <p>PDFs</p>
        ',array('id' => 'indexPdf'));?>
    </div>
    
    <div class="actionIcon">
    	<?php echo anchor($backLink,'
			<img src=" '.base_url().'img/back.png" height="50px" width="72px" alt="my Profile" />
			<p>Back</p>
		');?>
    </div>
    
</div>

<div class="contentSection">

	<div class="indexingMsg"></div>
    <div class="fileSystem">
    	<?php
    	$this->ckfinder->BasePath = base_url().'js/formBuilder/ckfinder/' ;	
		$this->ckfinder->Height = 600;
		$this->ckfinder->Create() ;
		?> 
    </div>

</div>
