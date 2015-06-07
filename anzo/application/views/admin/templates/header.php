<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $viewDescription; ?></title>
<script type='text/javascript' src='<?php echo base_url() ?>js/formBuilder/jquery-1.9.1.js' ></script>
<?php if(isset($scriptAndStyle))echo $scriptAndStyle; ?>
</head>

<body>
	<div class="pageWrapper">
    	
        <!-- Back end's header -->
        <div class="header">
        	<div class="siteStrip">
            	<div class="siteName">
                    <span><?php echo anchor('administrator/login/profileLogIn','ANZO',array('title' => 'Return to DashBoard'))?></span>
                </div>
            </div>
            
            <div class="taskStrip">
            	<div class="taskBar">
                	<div class="align">
                        <span class="level"><?php echo $viewDescription; ?></span>
                        <?php if($isLoggedIn === true){?>
                        <span class="logOut"><?php	echo anchor("administrator/login/loggingOut","Log Out"); ?></span>
                        <span class="user"><?php echo "Welcome!! ".$userName; ?></span>
                        <?php } ?>
                    </div>
                </div>
            </div>	
        </div>
        
        <!-- Major container of body where all pages came in -->
        <div class="containerWrapper" id="contentWrapper">
