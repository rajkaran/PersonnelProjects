<script type="text/javascript">
	$(document).ready(function(){
		 <?php if(isset($filledForm) == true){ ?>
			$("#contentWrapper").css({"height":"auto"});
			$(".contentSection").css({"height":"auto"});
			
			$("#form").css({"height":"<?php echo $formHeight+5; ?>px"});
		<?php } ?>
	});
</script>

<div class="contentSection">
	<div id="back"><?php echo anchor($backLink,'<span>&lt;&lt;</span> GO BACK');?></div>
    
    <?php if(isset($formList) == true){ ?>
    	<div class="list">
            <table>
                <!--Heading for the table-->
                <tr>
                    <th>Serial<br />No.</th>
                    <th class="name">Name</th>
                    <th>Parent</th>
                    <th>Parent Name</th>
                    <th>Total Submission</th>
                    <th>Creation Date</th>
                </tr>
                
                <!--Displaying data in the table layout-->
                <?php for($i=0; $i<count($formList); $i++){ ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        
                        <td class="name">
                            <?php echo anchor('administrator/track_submission/loadSubmission/'.$formList[$i]['id'], 
												$formList[$i]['articleName']); ?>
                        </td>
                        
                        <td><?php echo $formList[$i]['connectedTo']; ?></td>
                        <td><?php echo $formList[$i]['parent']; ?></td>
                        <td><?php echo $formList[$i]['totalSubmission']; ?></td>
                        <td><?php echo $formList[$i]['creationDate']; ?></td>
                    </tr>
                <?php } ?>
                
                <tr>
                    <td colspan="6"><?php echo $links; ?></td>
                </tr>
                
            </table>
    	</div>
    <?php } ?>
	
    <?php if(isset($submissionList) == true){ ?>
    	<div id="note"><p>Below is the list of the submission of <em><?php echo $formName; ?></em> form. The 
        latest submission of this form is at the top. To view submitted data click on the
         corresponding submission id.</p></div>
         
        <div class="list">
            <table>
                <!--Heading for the table-->
                <tr>
                    <th>Serial<br />No.</th>
                    <th>Submission Id</th>
                    <th>Submission Date</th>
                </tr>
                
                <!--Displaying data in the table layout-->
                <?php for($i=0; $i<count($submissionList); $i++){ ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        
                        <td>
                            <?php echo anchor('administrator/track_submission/filledForm/'.$submissionList[$i]['id'], 
                                                $submissionList[$i]['id']); ?>
                        </td>
                        
                        <td><?php echo $submissionList[$i]['submissionDate']; ?></td>
                    </tr>
                <?php } ?>
                <tr><td colspan="3"><?php echo $links;?></td></tr>
            </table>
        </div>
    <?php } ?>
    
    <?php if(isset($filledForm) == true){ ?>
    	<div id="form"><?php echo $filledForm; ?></div>
    <?php } ?>
    
</div>
