<script type="text/javascript">
	var verifyInput = new password();
	var validate = new validator();
</script>

<div class="contentSection">
	<div id="returnMsg" ><?php if(isset($displayMsg) )echo $displayMsg; ?></div>
	<?php if($action == "change"){ ?>
    	<div class="chpass">
			<?php $data = array('onsubmit' => "return verifyInput.validateInput();"); ?>
            <?php echo form_open('administrator/manage_user/changePassword', $data) ?>
                
                <div> <label>Old Password: 
                    <input type="password" id="old" name="old"/> 
                </label> </div>
                
                <div><label>New Password: 
                    <input type="password" id="new" name="new"/> 
                </label> </div>
                
                <div> <label>Type again: 
                    <input type="password" id="confirm" name="confirm"/> 
                </label> </div>
                
                <div class="buttonPanel">
                    <?php echo anchor($backLink,'Back',array('id' => 'back'));?>
                    <input type="submit" id="change" name="change" value="Change" />
                </div>
                
            </form>
		</div>
    <?php } ?>
    
    <?php if($action == "forget"){ ?>
        <div class="pass">
            <?php $data = array('onsubmit' => "return validate.isEmail('email');"); ?>
            <?php echo form_open('administrator/manage_user/forgetPassword', $data) ?>
                <p>Please type the email id that have been used for account set up 
                example &shy; john@wgh.on.ca, we will send you new password on this 
                email id.</p>
                <div>
                    <label>Email Id: 
                        <input type="text" id="email" name="email"/> 
                    </label>
                </div>
                
                <div class="buttonPanel">
                    <?php echo anchor($backLink,'Back',array('id' => 'back'));?>
                    <input type="submit" id="send" name="send" value="Send" />
                </div>
               
            </form>
        </div>
    <?php } ?>
    
    
</div>
