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
//alert("You are not allowed on this page, " + id);

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
		switch(operation){
			case 'publish':
				window.location.href="operation.php?id=" + txt + "&status=" + operation + "&adminid=" + id;
				break;
			
			case 'unpublish':
				window.location.href="operation.php?id=" + txt + "&status=" + operation + "&adminid=" + id;
				break;
			
			case 'delete':
				window.location.href="operation.php?id=" + txt + "&status=" + operation + "&adminid=" + id;
				break;
		}
	}
}

/*if($msg_id){
	var msg_id = " //echo $_REQUEST['msg']; ";
	switch(msg_id){
		case '0' :
			document.getElementById("successmsg").innerHTML="success: Selected rows have been Unpublished";
			break;
			
		case '1' :
			document.getElementById("successmsg").innerHTML="success: Selected rows have been published";
			break;
			
		case '2' :
			document.getElementById("successmsg").innerHTML="success: Selected rows have been deleted";
			break;
	}
}*/
</script>




<?php
if($_REQUEST){
$admin_id = $_REQUEST['id'];
//$msg_id = $_REQUEST['msg'];
//echo $admin_id;
}
?>

<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel</div>
    <div id="container">
        <div class="heading"><p class="heading_text"> Manage Articles </p></div>
        <div id="operator"><p >
        <a href="#" onclick="javascript:checkbox_id('publish');">Publish</a>|
        <a href="#" onclick="javascript:checkbox_id('unpublish');">Unpublish</a>|
        <a href="#" onclick="javascript:checkbox_id('delete');">Delete</a>|
        <?php echo "<a href='createarticle.php?id=$admin_id' >New</a> " ?>|
        <?php echo "<a href='main.php?id=$admin_id'>Home</a>" ?>
        </p></div>
        <div id="successmsg">  </div>
         <div id="content_form" align="center">
           <div align="center" class="tb">
           		<form name="articlelist" id="articlelist" >
                    <table width="100%" border="1" cellpadding="10">
                      <tr>
                        <th width="9%">&nbsp;</th>
                        <th width="25%">Title</th>
                        <th width="30%">Article Name</th>
                        <th width="10%">Published Status</th>
                        <th width="15%">Creation Date</th>
                        <th width="10%">Id</th>
                      </tr>
                      
                      <?php 
					  require_once("../libraries/config.php");
					  $query = "SELECT * FROM `articles`";
					  $result = mysql_query($query);
					  echo "";
					  while($row  = mysql_fetch_assoc($result)){
					  
                   	  echo "<tr>";
                        echo "<td><input name=\"tick\" type=\"checkbox\" value='".$row['id']."' /></td>";
                        echo "<td>".$row['title']."</td>";
                        echo "<td>".$row['article_name']."</td>";
                        echo "<td>".$row['publish_status']."</td>";
                        echo "<td>".$row['date']."</td>";
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