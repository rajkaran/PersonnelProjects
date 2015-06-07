<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Publish Article Window</title>
</head>

<body>
<?php
require_once("../libraries/config.php");
$id = trim($_REQUEST['id']);
$admin_id = trim($_REQUEST['adminid']);
$operation_performed = trim($_REQUEST['status']);
$id_array = explode(" ",$id);
//var_dump($id_array);
//echo "Publish Article" . $status;

if ($operation_performed === "delete"){
	$query = "DELETE QUICK FROM articles WHERE";
	$j="0";
	for($i=0;$i <= sizeof($id_array)-1 ;$i++){
		$query .= " `id` = '".$id_array[$i]."' ";
		if(sizeof($id_array) > 1 && $j <= sizeof($id_array)-2){
			$query .= " OR ";
			$j++;
		} 
	}
	//echo $query ;
	$result = mysql_query($query);
	$num = mysql_affected_rows();
	if($num != 0){
	    echo '<script> location.replace("managearticle.php?id=$admin_id"); </script>';
	//header("location:managearticle.php?id=$admin_id&msg=2");
	//header("location:managearticle.php?id=$admin_id");
	}
}

else{
	$query = "UPDATE `articles` SET";
	if($operation_performed === "publish"){
		$query .= "`publish_status`= '1' WHERE";
	}
	else if($operation_performed === "unpublish"){
		$query .= "`publish_status`= '0' WHERE";
	}
	$j="0";
	for($i=0;$i <= sizeof($id_array)-1 ;$i++){
		$query .= " `id` = '".$id_array[$i]."' ";
		if(sizeof($id_array) > 1 && $j <= sizeof($id_array)-2){
			$query .= " OR ";
			$j++;
		} 
	}
	//echo $query ;
	$result = mysql_query($query);
	$num = mysql_affected_rows();
	if($num != 0){
		//header("location:managearticle.php?id=$admin_id");
		
		if($operation_performed === "publish"){	
			echo '<script> location.replace("managearticle.php?id=$admin_id&msg=1"); </script>';
			//header("location:managearticle.php?id=$admin_id&msg=1");
		}
		else if($operation_performed === "unpublish"){
			echo '<script> location.replace("managearticle.php?id=$admin_id&msg=0"); </script>';
			//header("location:managearticle.php?id=$admin_id&msg=0");
		}
		
	}else echo '<script> location.replace("managearticle.php?id=$admin_id"); </script>';
}

 //WHERE `id` = 1 OR `id` = 3
/*else if (){
	echo "its unpublish condition";
}

else if (){
	echo "its delete condition";
}
*/
?>

</body>
</html>