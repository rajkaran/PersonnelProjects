<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Profile Details</title>
<link href="../css/myprofile.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
require_once("../libraries/config.php");
$admin_id = "";
$admin_id = $_REQUEST['id'];
//echo $admin_id;
$query = "SELECT * FROM credentials Where id = '".$admin_id."' ";
$result = mysql_query($query);
if(mysql_num_rows($result) != "0"){
	while($row = mysql_fetch_assoc($result)){
		$fname = $row['fname'];
		$lname = $row['lname'];
		$user_name = $row['user_name'];
		$email = $row['email'];
		$address = $row['address'];
		$password = $row['password'];
	}
}

?>
<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel</div>
    <div id="container">
        <div class="heading"><p class="heading_text"> My Profile </p></div>
         <div id="content_form" align="center">         
         <?php if($admin_id === "") echo "<div class='error'>Guest user can't update their profile </div>" ?>
         <?php echo "<form action=\"myprofile.php?id=$admin_id\" method=\"post\" name=\"form1\" >" ?>
             <div id="block_profile">
                 <fieldset><legend>My Profile</legend>
                   <table width="500px" cellpadding="10">
                    <tr>
                       <th>Name</th>
                       <td width="140"><input type="text" name="fname" id="fname" size="16em" value="<?php echo $fname; ?>" /></td>
                       <td width="140"><input type="text" name="lname" id="lname" size="16em" value="<?php echo $lname; ?>" /></td>
                     </tr>
                     <tr>
                       <th>User Name</th>
                       <td colspan="2"><input type="text" name="user_name" id="user_name" size="45 em" value="<?php echo $user_name; ?>"/></td>
                     </tr>
                     <tr>
                       <th>Email Id</th>
                       <td colspan="2"><input type="text" name="email" id="email" size="45em" value="<?php echo $email; ?>"/></td>
                     </tr>
                     <tr>
                       <th>Address</th>
                       <td colspan="2"><input type="text" name="address" id="address" size="45em" height="20em" value="<?php echo $address; ?>"/></td>
                     </tr>
                     
                   </table>
                   </fieldset>
               </div>
               
             <div id="block_password">
                   <fieldset><legend>Change Password</legend>
                   <table width="500px" cellpadding="10">
                     <tr>
                       <th>New Password</th>
                       <td colspan="2"><input type="password" name="password" id="password" size="45em" value="<?php echo $password; ?>"/></td>
                     </tr>
                     <tr>
                       <th>Confirm Password </th>
                       <td colspan="2"><input type="password" name="password_again" id="password_again" size="45em" value="<?php echo $password; ?>"/></td>
                     </tr>
                   </table>
                   </fieldset>
               </div>
               
              <div align="center" id="button_wrapper"><input type="submit" name="update" id="update" value="Update" /></div>
                 
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

	//generate sql query
		$inner_query = "UPDATE `credentials` SET `fname` = '".$fname."',`lname` = '".$lname."',`user_name` = '".$user_name."',`email` = '".$email."',`password` = '".$password."',`address` = '".$address."' WHERE `id` = '".$admin_id."' ";
		$inner_result = mysql_query($inner_query);
		$inner_num = mysql_affected_rows();
		
		if($inner_num != 0){
		header("location:main.php?id=$admin_id");
		}
		else{
		header("location:main.php?id=$admin_id");
		//echo "check the entries";
		//echo "0";
		}
}
?>
</body>
</html>