<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Forgot Password Window</title>
<link href="../css/forgotpassword.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/validate.js"></script>

</head>

<body>
<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel</div>
    <div id="container">
        <div class="heading"><p class="heading_text"> Forgot Password </p></div>
         <div id="content_form">
           <form action="forgotpassword.php" method="post" name="form2">
           <table width="491" cellpadding="10">
             <tr>
               <th width="157">User Name</th>
               <td width="286"><input type="text" name="user_name" id="user_name" size="45em" onblur="emptyfield(this.value);"/></td>
             </tr>
             <tr>
               <th scope="row">New Password</th>
               <td><input type="password" name="password" id="password" size="45em" onblur="emptyfield(this.value);"/></td>
             </tr>
             <tr>
               <th scope="row">Confirm password</th>
               <td><input type="password" name="password_again" id="password_again" size="45em" onblur="compare_password();"/></td>
             </tr>
              <tr>
               <th scope="row">Email Id</th>
               <td><input type="text" name="email" id="email" size="45em" onblur="emailchk();"/></td>
             </tr>
             <tr>
               <td scope="row" colspan="2"><input type="submit" name="generate_new_password" id="generate_new_password" value="Set New Password" /></td>
             </tr>
           </table>
           <div class="error" id="successmsg">   </div>
           </form>
        </div>
         
         <div id="content_text">
         <p>
            If you are Registered user and had forgotted password than set your new password by providing your Username or Email Id.<br/>
            
            <p style="color:#CC0000; font-size:18px">Note:</p>
            <p> => Username is the name that you had set during the Registration process.</p><br/> 
            <p> => Please use the same Email Id that you had provided in Registration Form.</p><br/>
            <p> => You can Provide Any one of them but we suggeast you to provide both, for better accuracy.</p><br/><br/>
            <p style="padding-left:50px;">Thank you! for using Our Services.</p><br/>
            
         </p>
         </div>
 
  </div>
    <div id="footer"> <p style="padding-top:2px;">
		<strong style="font-size:14px">Developed By:</strong> Rajkaran Chauhan 
		<br/>&copy; RK Designs
	</p> </div>
</div>
<?php
require_once("../libraries/config.php");
if($_POST){
	// username and password sent from form
	$user_name = $_POST['user_name'];
	$email = $_POST['email'];
	$n_password = $_POST['password'];
	if($user_name != "" || $email != "" || $n_password != ""){
		//generate sql query
		$query = "UPDATE `credentials` SET  `password` = '".$n_password."'  WHERE `user_name` = '".$user_name."' || `email` = '".$email."' ";
		$result = mysql_query($query);
		
		$num = mysql_affected_rows();
		if($num != 0){
			header("location:login.php");
		}
		else{
		echo "not a registered user";
		//echo "0";
		}
	
	}
	else{
			echo '<script type="text/javascript"> emptysubmit(); </script>';
	}
}
?>
</body>
</html>