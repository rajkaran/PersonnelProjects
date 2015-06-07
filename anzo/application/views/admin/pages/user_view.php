<script type="text/javascript">
	$(document).ready(function(){
		
		var appUser = new application_user('<?php echo base_url() ?>');
		var id = "<?php if(isset($userData) == true) echo $userData['id'] ?>";
		
		$("#disable").unbind("click").click(function(e) {
            e.preventDefault();
			appUser.enableOrDisableUser("disable");
        });
		
		$("#enable").unbind("click").click(function(e) {
            e.preventDefault();
			appUser.enableOrDisableUser("enable");
        });
		
		//Create a new user if id is not set otherwise update an old one
		$("#save").unbind("click").click(function(event){
			event.preventDefault();
			$("#returnMsg").html("");//clear the error div
			appUser.createOrUpdateUser(id);
		});
		
	});
</script>

<!--Show the action bar at the top-->
<div class="actionBar">
	<?php if(isset($userList) == true){ ?>
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
    <?php } ?>
    
    <div class="actionIcon">
    <?php echo anchor('administrator/manage_user/loadUserList/0/new','
        <img src=" '.base_url().'img/add_new.png" height="50px" width="49px" alt="my Profile" />
        <p>New User</p>
    ');?>
    </div>
    
     
    <div class="actionIcon">
        <?php echo anchor('administrator/manage_user/loadUserList','
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
	<div id="returnMsg" ><?php echo $this->session->flashdata('displayMessage');  ?></div>
	<?php if(isset($userList) == true){ ?>
    	<div class="userList">
            <table class="mytable">
                <!--Heading for the table-->
                <tr>
                    <th>Serial<br />No.</th>
                    <th>&nbsp;&nbsp;&nbsp;&nbsp;</th>
                    <th class="name">User Name</th>
                    <th>Employee Id</th>
                    <th class="email">Email Id</th>
                    <th>Enabled</th>
                    <th class="title">Titles Allowed</th>
                </tr>
                
                <!--Displaying data in the table layout-->
                <?php for($i=0; $i<count($userList); $i++){ ?>
                    <tr>
                        <td><?php echo $i;?></td>
                        <td><input type="checkbox" id="<?php echo $userList[$i]['id']; ?>" name="rowId" /></td>
                        
                        <td class="name">
                            <?php echo anchor('administrator/manage_user/loadUserList/'.$userList[$i]['id'].'/edit', $userList[$i]['userName']); ?>
                        </td>
                        
                        <td><?php echo $userList[$i]['empId']; ?></td>
                        <td class="email"><?php echo $userList[$i]['emailId']; ?></td>
                        <td><?php echo toglleImage($userList[$i]['status']); ?></td>
                        <td class="title"><?php echo $userList[$i]['title']; ?></td>
                    </tr>
                <?php } ?>
                
                <tr>
                    <td colspan="7"><?php echo $links; ?></td>
                    
                </tr>
                
            </table>
    	</div>
    <?php } ?>
    
    <?php if(isset($userData) == true){ ?>
    	<div class="userData">
        	
            <div><label>User Name: <input type="text" value="<?php echo $userData['userName']; ?>" id="userName" /></label></div>
            <div><label>Password: 
                <input type="password" value="<?php echo base64_decode($userData['password']); ?>" id="password" />
            </label></div>
            <div><label>Confirm Password: 
                <input type="password" value="<?php echo base64_decode($userData['password']); ?>" id="confirmPassword" /></div>
            </label>
        
            <div><label>Employee Id: <input type="text" value="<?php echo $userData['empId']; ?>" id="empId" /></label></div>
            <div><label>Email Id: <input type="text" value="<?php echo $userData['emailId']; ?>" id="emailId" /></label></div>
            <div><label>Is Enabled?: <?php echo toglleImage($userData['status']); ?></label></div>
            
            <div id="title"><label for="titles">Titles: </label><select size="4" multiple="multiple" id="titles" name="title[]">
                <?php $titleAllowed = explode(",", $userData['title']); ?>
                <?php for($i=0; $i<count($titleArray); $i++){ ?>
                    <option value="<?php echo $titleArray[$i]['id']; ?>" 
                        <?php
                        if( in_array($titleArray[$i]['title'], $titleAllowed) == true)
                            echo "selected='selected'";
                        ?> >
                        <?php echo $titleArray[$i]['title']; ?>
                    </option>
                <?php } ?>
            </select></div>
            
    	</div>
    <?php } ?>
    
    <?php if(isset($userData) == false && isset($userList) == false){ ?>
    	<div class="userData">
            <div><label>User Name: <input type="text" value="" id="userName" /></label></div>
            <div><label>Password: <input type="password" value="" id="password" /></label>
            <span>Note: Must be 8 charaters long.</span></div>
            
            <div><label>Confirm Password: <input type="password" value="" id="confirmPassword" /></label> </div>
            <div><label>Employee Id: <input type="text" value="" id="empId" /></label></div>
            <div><label>Email Id: <input type="text" value="" id="emailId" /></label></div>
        
            <div id="title"><label for="titles">Titles: </label><select size="4" multiple="multiple" id="titles" name="title[]">
                <?php for($i=0; $i<count($titleArray); $i++){ ?>
                    <option value="<?php echo $titleArray[$i]['id']; ?>" >
                        <?php echo $titleArray[$i]['title']; ?>
                    </option>
                <?php } ?>
            </select></div>
    	</div>
    <?php } ?>
    
</div>
