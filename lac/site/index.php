<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Home Page</title>
<link href="../css/system.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function showerror(){
//alert("its done");
document.getElementById("successmsg").innerHTML="Error: Credentials are incorrect.";
}
 </script>

</head>

<body>
<div id="sitename">LearnAboutcms.com</div>
<div id="topnav">
	<ul>
    	<?php
		require_once("../libraries/config.php");
		$query = "SELECT `id`,`title` FROM `articles` WHERE `publish_status` = '1'";
		$result = mysql_query($query);
		//echo $r = mysql_num_rows($result);
		while($row  = mysql_fetch_assoc($result)){
		$article_id = $row['id'];
		$title = $row['title'];
		//var_dump($row);
		echo "<li><a href='index.php?id=$article_id'>$title</a></li>";
		} 
		
		?> 
        
  </ul> 
</div>
<div id="container">
	<?php
	require_once("../libraries/config.php");
	if($_GET){
		//echo $r = $_REQUEST['id'];
		$query = "SELECT * FROM `articles` WHERE `id` = '".$_REQUEST['id']."' ";
		$result = mysql_query($query);
		//echo $r = mysql_num_rows($result);
		while($row  = mysql_fetch_assoc($result)){
		$article_name = $row['article_name'];
		$date = $row['date'];
		$content = $row['content'];
		//var_dump($row);
		}
	}
	else{
		$query = "SELECT * FROM `articles` WHERE `id` = '1' ";
		$result = mysql_query($query);
		//echo $r = mysql_num_rows($result);
		while($row  = mysql_fetch_assoc($result)){
		$article_name = $row['article_name'];
		$date = $row['date'];
		$content = $row['content'];
		//var_dump($row);
		}
	}
	
	?>
	<div id="heading">Article Name: <?php echo $article_name; ?><div id="subhead"> Creation Date: <?php echo $date; ?> </div> </div>
	<div id="content">
    		<p class="pagecontent"><?php echo $content; ?></p>	
    	</div>
	    <div id="loginsection">
	    	<div class="login">
		    	<fieldset><legend>Administrator Login</legend>
			        <form action="index.php" method="post">
			        	<div><label>User Name</label><input name="user_name" type="text" size="40px" /></div>
			            	<div><label>Password</label><input name="password" type="password" size="40px" /></div>
			            	<div align="center">
			            		<input class="submit" name="login" type="submit" value="Log In" />
			            		<a href="../admin/forgotpassword.php" class="adminlink"> forgot password </a>
			            	</div>
			         </form>
		         	  
		        </fieldset>
		        <?php
				require_once("../libraries/config.php");
				if($_POST){
					
					echo "raj";
					
					// username and password sent from form
					$user_name = $_POST['user_name'];
					$password = $_POST['password'];
					//generate sql query
					$query = "SELECT * FROM `credentials` WHERE `user_name` = '".$user_name."' and `password` = '".$password."' ";
					$result = mysql_query($query);
					if(mysql_num_rows($result) != 0){
						while($row = mysql_fetch_assoc($result)){
							$user_type =  $row['user_type'];
							$admin_id = $row['id'];
						}
						//echo '<script> location.replace("../admin/main.php?id='.$admin_id.'"); </script>';
						//header("location:../admin/main.php?id=$admin_id");
						if($user_type == "admin"){
						//echo $admin_id;
							echo '<script> location.replace("../admin/main.php?id='.$admin_id.'"); </script>';
							//header("location:main.php?id=$admin_id");
						}
						elseif($user_type == "user"){
							echo '<script> location.replace("../admin/main.php"); </script>';
							//header("location:main.php");
						}
						else{
						echo "incorrect registration";
						//echo "3";
						}
					}
					else{
						echo '<script type="text/javascript"> showerror(); </script>';
					}
				}
			?>
	    </div>
    </div>
</div>
<div id="footer"> 
	<p style="padding-top:2px;">
		<strong style="font-size:14px">Developed By:</strong> Rajkaran Chauhan 
		&copy; RK Designs
	</p>
</div>
</body>
</html>