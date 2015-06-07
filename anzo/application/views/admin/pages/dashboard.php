<script>
$(document).ready(function(){
	$("#contentWrapper").css({"height":"582px", "border":"1px ridge #15777a", "overflow":"auto"});
});
</script>

<div class="dashboardWrapper">
	<div class="iconWrapper">
    	<?php echo anchor('administrator/upload_and_index/loadUploadAndIndexView','
			<img src=" '.base_url().'img/profile.png" height="120px" width="133px" alt="my Profile" />
			<p>Upload and Index</p>
		')?>
	</div>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/manage_user/loadChangePassword','
			<img src=" '.base_url().'img/password.png" height="120px" width="89px" alt="change password" />
			<p>Change Password</p>
		')?>
	</div>
    
    <?php if($this->session->userdata('role') == "admin"){ ?>
    <div class="iconWrapper">
        <?php echo anchor('administrator/manage_user/loadUserList','
			<img src=" '.base_url().'img/users.png" height="120px" width="127px" alt="manage users" />
			<p>Manage Users</p>
		')?>
	</div>
    <?php } ?>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/manage_home_page/loadHomePage/0/new','
			<img src=" '.base_url().'img/homepage.png" height="120px" width="114px" alt="manage home page" />
			<p>Manage Home Page</p>
		')?>
	</div>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/listing/loadLevelList/categoryList','
			<img src=" '.base_url().'img/category.png" height="120px" width="123px" alt="manage category" />
			<p>Manage Category</p>
		')?>
	</div>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/listing/loadLevelList/sub-categoryList','
			<img src=" '.base_url().'img/subcategory.png" height="120px" width="102px" alt="manage sub-category" />
			<p>Manage Sub-Category</p>
		')?>
	</div>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/listing/loadLevelList/articleList','
			<img src=" '.base_url().'img/article.png" height="120px" width="112px" alt="manage article" />
			<p>Manage Article</p>
		')?>
	</div>
    
    <div class="iconWrapper">
        <?php echo anchor('administrator/track_submission/loadFormList','
			<img src=" '.base_url().'img/submission.png" height="120px" width="112px" alt="manage article" />
			<p>Track Form Submissions</p>
		')?>
	</div>
    
</div>