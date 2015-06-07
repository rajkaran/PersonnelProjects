<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Delete Article Window</title>
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

$query = "DELETE QUICK FROM credentials WHERE";
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
	echo '<script> location.replace("manageadmin.php?id=$admin_id"); </script>';

	//header("location:manageadmin.php?id=$admin_id");
}

?>
</body>
</html>