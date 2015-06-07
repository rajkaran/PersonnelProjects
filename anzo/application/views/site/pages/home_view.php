<script type="text/javascript">
	$(document).ready(function(e) {
		var cycleEvents = new home_Page_Event('<?php echo base_url() ?>');
		
		//The initial selection of top event
		<?php if(isset($events) == true){ ?>
		cycleEvents.getDescription("<?php echo $events[0]['id']; ?>");
		<?php } ?>
		
		//Show the retrieved description of the selected event
		$(".event").unbind("click").click(function(event){
			event.preventDefault();
			cycleEvents.getDescription($(this).attr("id"));
		});
		
		cycleEvents.cycleThroughEvent();
    });
</script>

<div class="contentSection">
	<div id="description">
    </div>
    
    <div id="eventList">
    	<p>AT A GLANCE</p>
    	<ul>
        	<?php for($i=0; $i<count($events); $i++){ ?>
                <li><a href="#" class="event" id="<?php echo $events[$i]['id']; ?>" title="<?php echo $events[$i]['name']; ?>" >
					<?php echo sliceString($events[$i]['name'], 23); ?>
                </a></li>
            <?php } ?>
        </ul>
    </div>

</div>