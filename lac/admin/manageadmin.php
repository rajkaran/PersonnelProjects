<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Manage Articles</title>
<link href="../css/managearticle.css" rel="stylesheet" type="text/css" />
</head>

<body>


<script type="text/javascript">
var id = "<?php echo $_REQUEST['id']; ?>";

function checkbox_id(operation){
	var boxes = "";
	boxes = document.articlelist.tick.length;
	var txt = "";
	for (i = 0; i < boxes; i++) {
		if (document.articlelist.tick[i].checked) {
			txt = txt + document.articlelist.tick[i].value + "+";
		}
	}
	if (txt == "") {
		Message = "No Boxes ticked";
	}
	else {
				window.location.href="deleteadmin.php?id=" + txt + "&adminid=" + id;
	}
}
</script>

<?php
if($_REQUEST){
$admin_id = $_REQUEST['id'];
//echo $admin_id;
}
?>

<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel</div>
    <div id="container">
        <div class="heading"><p class="heading_text"> Manage Admins </p></div>
        <div id="operator"><p >
        <a href="#" onclick="javascript:checkbox_id('delete');">Delete</a>|
        <a href="registration.php">New</a>|
        <?php echo "<a href='main.php?id=$admin_id'>Home</a>" ?>
        </p></div>
         <div id="content_form" align="center">
           <div align="center" class="tb">
           		<form name="articlelist" id="articlelist" >
                    <table width="100%" border="1" cellpadding="10">
                      <tr>
                        <th width="9%">&nbsp;</th>
                        <th width="25%">Name</th>
                        <th width="30%">Email Id</th>
                        <th width="10%">User Name</th>
                        <th width="15%">User Type</th>
                        <th width="10%">Id</th>
                      </tr>
                      
                      <?php 
					  require_once("../libraries/config.php");
					  $query = "SELECT * FROM `credentials`";
					  $result = mysql_query($query);
					  while($row  = mysql_fetch_assoc($result)){
					  $name = $row['fname'] ." ". $row['lname'];
                   	  echo "<tr>";
                        echo "<td><input name=\"tick\" type=\"checkbox\" value='".$row['id']."' /></td>";
                        echo "<td>".$name."</td>";
                        echo "<td>".$row['email']."</td>";
                        echo "<td>".$row['user_name']."</td>";
                        echo "<td>".$row['user_type']."</td>";
                        echo "<td>".$row['id']."</td>";
                      echo "</tr>";
					  }
					  echo "";
					  ?>
                      	
                </table>
                </form>
           <div class="error"> </div>
           </div>
        </div>
         
         
 
  </div>
    <div id="footer"> <p style="padding-top:2px;">
		<strong style="font-size:14px">Developed By:</strong> Rajkaran Chauhan 
		<br/>&copy; RK Designs
	</p> </div>
</div>

</body>
</html>