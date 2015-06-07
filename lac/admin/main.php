<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Main Application Window</title>
<link href="../css/main.css" rel="stylesheet" type="text/css" />
</head>

<body>
<?php
$admin_id = $_REQUEST['id'];
//echo $id;
?>
<div align="center">
    <div id="sitename">LearnAboutcms.com</div>
    <div id="site_ends">Administrator Panel <a href="login.php" id="login"> Log Out</a></div>
    <div id="container">
        <div id="navigation" align="left"> 
            <div class="heading"><p class="heading_text" style="text-align:center;"> Main Menu </p></div>
            <p style="padding-left:7px;"><?php echo "<a href='myprofile.php?id=$admin_id' ><img src=\"../images/myprofile.jpg\" /></a>" ?></p>
            <p style="padding-left:7px;"><?php echo "<a href='myprofile.php?id=$admin_id' ><img src=\"../images/changepw.jpg\" /></a>" ?></p>
            <p style="padding-left:7px;"><?php echo "<a href='managearticle.php?id=$admin_id' ><img src=\"../images/managearticle.jpg\" /?</a>" ?></p>
            <p style="padding-left:7px;"><?php echo "<a href='manageadmin.php?id=$admin_id' ><img src=\"../images/manageadmin.jpg\" /?</a>" ?></p>
            
                
        </div>
        <div id="content" align="right"  style="padding-top:2px"> 
            <div class="heading"><p class="heading_text"> Dash Board </p></div>
            <div>
          			<div class="img" style="padding-left:10px">
                    <?php echo "<a href='myprofile.php?id=$admin_id' ><img src=\"../images/my_profile.png\" width=\"180\" height=\"180\" \/></a>" ?><p>My Profile</p>
                    </div> 
                    <div class="img" style="padding-left:70px">
                    <?php echo "<a href='myprofile.php?id=$admin_id' > <img src=\"../images/change_password.png\" width=\"180\" height=\"180\" /></a>" ?><p>Change Password</p>
          			</div>
                    <div class="img" style="padding-left:80px">
                    <?php echo "<a href='managearticle.php?id=$admin_id' > <img src=\"../images/manage_article.png\" width=\"180\" height=\"180\" /></a>" ?><p>Manage Articles</p>
         			</div>
                    <div class="img" style="padding-left:105px;">
                    <?php echo "<a href='manageadmin.php?id=$admin_id' > <img src=\"../images/manage_admin.png\" width=\"180\" height=\"180\" /></a>" ?><p>Manage Admins</p>
                    </div>
              </div>
              <div align="center" class="tb">
                    <table border="1" cellpadding="10">
                      <tr>
                        <td colspan="7" class="table_head" ><label>List of Articles</label></td>
                      </tr>
                      <tr>
                        <th scope="col">Title</th>
                        <th scope="col">Article Name</th>
                        <th scope="col">Published Status</th>
                        <th scope="col">Creation Date</th>
                        <th scope="col">Id</th>
                      </tr>
                   	  <?php 
					  require_once("../libraries/config.php");
					  $query = "SELECT * FROM `articles` LIMIT 0,3";
					  $result = mysql_query($query);
					  echo "";
					  while($row  = mysql_fetch_assoc($result)){
					  
                   	  echo "<tr>";
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
       	  </div>
        </div>
    </div>
    <div id="footer"> <p style="padding-top:2px;">
		<strong style="font-size:14px">Developed By:</strong> Rajkaran Chauhan 
		<br/>&copy; RK Designs
	</p></div>
</div>


</body>
</html>
