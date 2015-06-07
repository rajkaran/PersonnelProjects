<script type="text/javascript">
    $(document).ready(function(){
        $("#contentWrapper").css({"height":"auto"});
		
		var homeEvent = new home_page('<?php echo base_url() ?>');
		var id = "<?php if(isset($eventData['id']) == true) echo $eventData['id'] ?>";
		var height = $("#description").height();
		var width = $("#description").width();
		
		$("#disable").unbind("click").click(function(e) {
            e.preventDefault();
			homeEvent.enableOrDisableEvent("disable");
        });
		
		$("#enable").unbind("click").click(function(e) {
            e.preventDefault();
			homeEvent.enableOrDisableEvent("enable");
        });
		
		//Create an instance of ckeditor
		var editor = CKEDITOR.replace( 'description',
		{
			basePath : '<?php echo base_url()?>js/formBuilder/ckeditor/',
			toolbar :
			[
				{ name: 'document', items : [ 'Source' ] },
				{ name: 'clipboard', items : [ 'Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo' ] },
				{ name: 'editing', items : [ 'Find','Replace','-','SelectAll','-','SpellChecker', 'Scayt' ] },
				'/',
				{ name: 'basicstyles', items : [ 'Bold','Italic','Underline','Strike','Subscript','Superscript','-','RemoveFormat' ] },
				{ name: 'paragraph', items : [ 'NumberedList','BulletedList','-','Outdent','Indent','-','Blockquote',
				'-','JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock' ] },
				{ name: 'links', items : [ 'Link','Unlink'] },
				{ name: 'insert', items : [ 'Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','Iframe' ] },
				'/',
				{ name: 'styles', items : [ 'Styles','Format','Font','FontSize' ] },
				{ name: 'colors', items : [ 'TextColor','BGColor' ] },
				{ name: 'tools', items : ['ShowBlocks'] }
			],
			toolbarCanCollapse:false,
			toolbarStartupExpanded:true,
			resize_enabled:false,
			height:height,
			width:width
		});
		
		CKFinder.setupCKEditor( editor, '<?php echo base_url()?>js/formBuilder/ckfinder/' );
        
		/*creating an event when id is not set otherwise editing old event*/
		$("#save").unbind("click").click(function(event){
			event.preventDefault();
			$("#errorMsg").html("");//clear the error div
			homeEvent.createOrUpdateEvent(id);
		});

    });
</script>

<!--Show the action bar at the top-->
<div class="actionBar">
    <div class="actionIcon">
        <?php echo anchor('manage_home_page','
            <img src=" '.base_url().'img/disable.png" height="50px" width="50px" alt="my Profile" />
            <p>Disable</p>
        ',array('id' => 'disable'));?>
    </div>
    
    <div class="actionIcon">
        <?php echo anchor('manage_home_page','
            <img src=" '.base_url().'img/enable.png" height="50px" width="50px" alt="my Profile" />
            <p>Enable</p>
        ',array('id' => 'enable'));?>
    </div>
    
    <div class="actionIcon">
    <?php echo anchor('administrator/manage_home_page/loadHomePage/0/new','
        <img src=" '.base_url().'img/add_new.png" height="50px" width="49px" alt="my Profile" />
        <p>New Event</p>
    ');?>
    </div>
    
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
	
    <div id="errorMsg"> </div>
    
    <div class="eventWrapper">
    	<div id="event">
        	<div class="eventName">
        		<label id="nameLabel"> Event Name: 
                	<input type="text" id="nameTextbox" class="nameTextbox" 
                    	value="<?php if(isset($eventData) == true) echo $eventData['name']  ?>" />
                </label>
            </div>
            
            <div class="eventDescription">
        		<label id="descriptionLabel"> Event Description:
                	<div><textarea id="description" >
						<?php if(isset($eventData) == true) echo $eventData['description']  ?>
                    </textarea></div>
                </label>
           </div>
        </div>
        
        <div id="eventList">
        <table border="1">
        	<caption>List of Events</caption>
            
        	<tr>
            	<th>&nbsp;</th>
                <th class="name">Name</th>
                <th>Enabled</th>
            </tr>
            <?php $page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0; ?>
            <?php for($i=0; $i<count($list); $i++){ ?>
            <tr class="retrievedRows">
            	<td><input type="checkbox" id="<?php echo $list[$i]['id'] ?>" class="eventCheckbox" /></td>
                <td class="name"><?php echo anchor('administrator/manage_home_page/loadHomePage/'.$list[$i]['id'].'/open/'.$page.'', sliceString($list[$i]['name'], 23)); ?></td>
                <td><?php echo toglleImage($list[$i]['status']);  ?></td>
            </tr>
			<?php } ?>
            
            <tr>
            	<td colspan="3"><?php echo $links; ?></td>
            </tr>
            
        </table>
        </div>
    </div>

</div>

