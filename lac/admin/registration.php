<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register New User</title>
<link href="../css/registration.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="../js/validate.js"></script>
<script type="text/javascript">
function showerror(){
//alert("its done");
document.getElementById("successmsg").innerHTML="Error: You can't use same Email again";
}
</script>
</head>

<body>
<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel </div>
    <div id="container">
        <div class="heading"><p class="heading_text"> Register New Admin </p></div>
         <div id="content_form" align="center">
         <div class="error" id="successmsg" > Please fill the below Fields</div>
           <form action="registration.php" method="post" name="form2" >
           <table width="585" cellpadding="10">
            <tr>
               <th>Name</th>
               <td width="140"><input type="text" name="fname" id="fname" size="16em" value="First Name" onfocus="this.value='';" onblur="emptyfield(this.value);"/></td>
               <td width="140"><input type="text" name="lname" id="lname" size="16em" value="Last Name" onfocus="this.value='';" onblur="emptyfield(this.value);"/></td>
             </tr>
             <tr>
               <th>User Name</th>
               <td colspan="2"><input type="text" name="user_name" id="user_name" size="45em" onblur="emptyfield(this.value);"/></td>
             </tr>
             <tr>
               <th>Email Id</th>
               <td colspan="2"><input type="text" name="email" id="email" size="45em" onblur="emailchk();"/></td>
             </tr>
             <tr>
               <th>New Password</th>
               <td colspan="2"><input type="password" name="password" id="password" size="45em" onblur="emptyfield(this.value);"/></td>
             </tr>
             <tr>
               <th>Type the Password again</th>
               <td colspan="2"><input type="password" name="password_again" id="password_again" size="45em" onblur="compare_password();" /> </td>
             </tr>
             <tr>
               <th>Address</th>
               <td colspan="2"><input type="text" name="address" id="address" size="45em" height="20em" onblur="emptyfield(this.value);" /></td>
             </tr>
             <tr>
               <td colspan="3" align="center" style="width:40px"><input type="submit" name="register" id="register" value="Register Me" /></td>
             </tr>
           </table>
           <div class="error"> </div>
           </form>
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
	$fname = $_POST['fname'];
	$lname = $_POST['lname'];
	$user_name = $_POST['user_name'];
	$email = $_POST['email'];
	$password = $_POST['password'];
	$address = $_POST['address'];
	if($fname != "" || $lname != "" || $user_name != "" || $email != "" || $password != "" || $address != ""){

		//generate sql query
		$query = "SELECT * FROM `credentials` WHERE `email` = '".$email."'";
		$result = mysql_query($query);
		//$r = mysql_num_rows($result);
		
		if(mysql_num_rows($result) === 0){
			echo $inner_query = "INSERT INTO `credentials` (`id`, `fname`, `lname`, `user_name`, `email`, `password`, `address`, `user_type`) VALUES (NULL, '".$fname."', '".$lname."', '".$user_name."', '".$email."', '".$password."', '".$address."', ''); ";
			$inner_result = mysql_query($inner_query);
			$inner_num = mysql_affected_rows();
			
			if($inner_num != 0){
			echo '<script> location.replace("login.php"); </script>';
			//header("location:login.php");
			}
			else{
			echo "check the entries";
			//echo "0";
			}
		}
		else{
			echo '<script type="text/javascript"> showerror(); </script>';
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