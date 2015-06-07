<script>
$(document).ready(function(){
	$("#contentWrapper").css({"height":"584px", "display":"table"});
});
</script>
<div class="loginWrapper">
	<div class="logIn">
		<?php echo form_open('administrator/login/validate_credentials') ?>
            <div class="caption">Log In</div>
            
            <div class="userName">
                <label>User Name: 
                    <input type="text" id="userName" name="userName" required="required" /> 
                </label>
            </div>
            
            <div class="password">
                <label>Password: 
                    <input type="password" id="password" name="password" required="required" /> 
                </label>
            </div>
            
            <div class="buttonPanel">
                <?php echo anchor('administrator/manage_user/loadForgetPassword','Forget Password',array('id' => 'forget'));?>
                <input type="submit" id="logIn" name="logIn" value="Log In" />
            </div>
            <div class="error">
				<?php echo $this->session->flashdata('loginError');?>
            </div>
        </form>
	</div>
</div>