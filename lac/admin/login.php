<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Administrator Log In</title>
<script type="text/javascript" src="../js/validate.js"></script>
<link href="../css/login.css" rel="stylesheet" type="text/css" />
<link rel="icon"
type="image/ico"
href="../images/logo.ico">
<script src="../libraries/jquery-1.7.2.min.js"></script>
<script type="text/javascript">
function showerror(){
//alert("its done");
document.getElementById("successmsg").innerHTML="Error: Password or User Name may be incorect";
}
function emptysubmit(){
//alert("its done");
document.getElementById("successmsg").innerHTML="Error: Password or User Name fields are empty";
}
</script>
</head>
<body OnLoad="document.form1.user_name.focus();">
<form action="login.php" method="post" name="form1">
<p class="sitename">LearnAboutcms.com</p>
<div class="backend">Administrator Panel</div>
<div align="center" id="wrapper">
	<div class="loginblock">
    <p class="blocktitle"> Admin Log In </p>
    <div align="center"> <label>User Name</label> <input type="text" name="user_name" id="user_name" size="40%" onblur="emptyfield(this.value);" /> </div> 
    <br />
    <div align="center"> <label>Password</label> <input type="password" name="password" id="password" size="40%" onblur="emptyfield(this.value);" /> </div> 
    <br /><br />
    <div align="center"> <input type="submit" name="login" id="login" value="Log In" class="button" /> </div> 
    <br />
    <div align="center"><p id="redirect"><a href="forgotpassword.php"> Forgot Password &nbsp; |</a><a href="registration.php">&nbsp; Sign up </a></p></div>
    <div class="error" id="successmsg">   </div>
	</div>
    
</div>
</form>
<?php
require_once("../libraries/config.php");
if($_POST){
	
		// username and password sent from form
		$user_name = $_POST['user_name'];
		$password = $_POST['password'];
		if($user_name != ""){
		//generate sql query
		$query = "SELECT * FROM `credentials` WHERE `user_name` = '".$user_name."' and `password` = '".$password."' ";
		$result = mysql_query($query);
		$user_type = "";
		if(mysql_num_rows($result) != 0){
			while($row = mysql_fetch_assoc($result)){
				$user_type =  $row['user_type'];
				$admin_id = $row['id'];
			}
			//echo '<script> location.replace("main.php?id='.$admin_id.'"); </script>';
			//header("location:main.php?id=$admin_id");
			if($user_type == "admin"){
			//echo $admin_id;
				echo '<script> location.replace("main.php?id='.$admin_id.'"); </script>';
				//header("location:main.php?id=$admin_id");
			}
			elseif($user_type == "user"){
				echo '<script> location.replace("main.php"); </script>';
				//header("location:main.php");
			}
			else{
			echo "incorrect registration";
			//echo "3";
			}
		}else{
			echo '<script type="text/javascript"> showerror(); </script>';
		}
	}
	else{
			echo '<script type="text/javascript"> emptysubmit(); </script>';
	}
}
?>
</body>
</html>