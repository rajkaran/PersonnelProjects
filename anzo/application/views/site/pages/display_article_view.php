<script type="text/javascript">
	$(document).ready(function(e) {
		var slider = new slide_and_movement('<?php echo base_url() ?>');
		
		/*set y axis of next and back button on page load and window resize events*/
		slider.setNextAndBack();
		$(window).resize(function(){
			slider.setNextAndBack();
		}); 
		
		//var articleArray = <?php echo json_encode($articleList); ?>;
//		if(articleArray.length > 0){
//			var lastIndex = articleArray.length-1;
//			if(lastIndex == 0) $(".movement").hide();
//			else $("#backward").hide();
//			slider.createSlides(articleArray);
//		}
//		else{
//			$(".movement").hide();
//			$("#slideWrapper").html("<div class='article' > There is no Article found. </div>");
//		}
		
		var articleArray = <?php echo json_encode($articleList); ?>;
		if(articleArray.length > 0){
			var lastIndex = articleArray.length-1;
			if(lastIndex == 0) $(".movement").hide();
			else $("#backward").hide();
			slider.createFirstSlide(articleArray);
		}
		else{
			$(".movement").hide();
			$("#slideWrapper").html("<div class='article' > There is no Article found. </div>");
		}
		
		/*show the next article/slide*/
		$("#next").unbind("click").click(function(event){
			event.preventDefault();
			//slider.moveForward(articleArray);
			slider.next();
		});
		
		/*show the previous article/slide*/
		$("#back").unbind("click").click(function(event){
			event.preventDefault();
			//slider.moveBackward(articleArray);
			slider.previous(articleArray);
		});
		
		
    });
	
</script>

<div class="contentSection">
	<div id="submitError">
    	<?php echo $this->session->flashdata('displayMessage');  ?>
    </div>

	<div id="breadCrumb">
    	<?php echo $breadCrumb; ?>
    </div>
    
    <div class="movement" id="backward" >
    	<a href="" id="back">
        	<img src="<?php echo base_url(); ?>/img/backward.png" 
            	alt="Back" title="Go to Previous Article" height="130px" 
                width="70px" /></a>
    </div>
    
    <div class="movement" id="forward" >
    	<a href="" id="next">
        	<img src="<?php echo base_url(); ?>/img/forward.png" 
            	alt="Next" title="Go to Next Article" height="130px" 
                width="70px" /></a>
    </div>
    
    <div id="slideWrapper">
    	<div id="slider"></div>
    </div>

</div>


