<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register New User</title>
<link href="../css/createarticle.css" rel="stylesheet" type="text/css" />
<script type="text/javascript">
function doDecryption(){
//alert("its done");
document.getElementById("successmsg").innerHTML="success: A new Article has been Creatd";
}
function showerror(){
//alert("its done");
document.getElementById("successmsg").innerHTML="Error: You can't use same Title again";
}
 </script>
</head>

<body>
<?php
$admin_id = $_REQUEST['id'];
//echo $id;
?>
<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel</div>
    <div id="container">
        <div class="heading"><p class="heading_text"> Create New Article </p></div>
         <div id="content_form" align="center">
                    
          <div class="error" id="successmsg"> Please fill the below Fields</div>

         <form action="<?php echo "createarticle.php?id=$admin_id"; ?>" method="post" name="formcreate">
         
           <table width="585" cellpadding="10">
            <tr>
               <th>Title</th>
               <td width="140"><input type="text" name="title" id="title" size="45em" /></td>
             </tr>
             <tr>
               <th>Article Name</th>
               <td><input type="text" name="article_name" id="article_name" size="45 em"/></td>
             </tr>
             <tr>
               <th>Publish status</th>
               <td><label>
                 <select name="publish_status" id="publish_status" style="border-radius:5px;">
                   <option value="1">Publish</option>
                   <option value="0">Unpublish</option>
                 </select>
               </label></td>
             </tr>
             <tr>
               <th>Content</th>
               <td><textarea name="content" id="content" cols="30" rows="3"></textarea></td>
             </tr>
             <tr>
               <td colspan="2" align="center" style="width:40px"><input type="submit" name="create" id="create" value="Create" /><?php echo "<a href='managearticle.php?id=$admin_id' class = 'managearticle_link' > Go To List of Articles </a>" ?> </td>
             </tr>
           </table>
           </form>
        </div>
         
 
  </div>
    <div id="footer"> 
    	<p style="padding-top:2px;">
		<strong style="font-size:14px">Developed By:</strong> Rajkaran Chauhan 
		<br/>&copy; RK Designs
	</p> 
    </div>
</div>
</body>
</html>

<?php
require_once("../libraries/config.php");
//echo "raj";
if($_POST){

$title = $_POST['title'];
$article_name = $_POST['article_name'];
$publish_status = $_POST['publish_status'];
$content = $_POST['content'];

$query = "SELECT title FROM `articles` WHERE title = '".$title."'";
$result = mysql_query($query);
	//$r = mysql_num_rows($result);
	
if(mysql_num_rows($result) === 0){
	$inner_query = "INSERT INTO `articles` (`id`, `title`, `article_name`, `publish_status`, `content`, `date`) VALUES (NULL, '".$title."', '".$article_name."', '".$publish_status."', '".$content."', NOW()) ";
	
	
	
	$inner_result = mysql_query($inner_query);
	$inner_num = mysql_affected_rows();
	
	if($inner_num != 0){
		//echo "raj";
		//header("location:login.php");
		echo '<script type="text/javascript"> doDecryption(); </script>';
	}
	else{
		echo "check the entries";
		//echo "0";
	}
}
else{
	echo '<script type="text/javascript"> showerror(); </script>';
}

//echo $title . "<br />". $article_name . "<br />". $publish_status . "<br />". $content;
}
?>


