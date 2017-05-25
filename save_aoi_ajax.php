<?php
session_start();

require("pr_config.php");
//$dsn = "pgsql:dbname=prgap;host=127.0.0.1";
$user = "postgres";
$password ="";

try{
	$dbh = new PDO($pdo_dsn, $user, $password);
} catch(Exception $e) {
	echo "PDO connection error"; 
}

$user = $_SESSION['username'];

$desc = trim($_POST['desc']);
$aoiname = $_POST['aoiname'];
$del = $_POST['del'];

//echo json_encode(array("desc"=>$desc, "aoiname"=>$aoiname, "del"=>$del));die();

if ($del == 'delete'){
	$query = "update aoi set username = NULL where name = :aoiname";
	$stmt = $dbh->prepare($query);
	$stmt->bindParam(':aoiname', $aoiname);
	$stmt->execute();

}elseif (!empty($desc)){
	$query1 = "update aoi set username = :user where name = :aoiname";
	$stmt = $dbh->prepare($query1);
	$stmt->bindParam(':aoiname', $aoiname);
	$stmt->bindParam(':user', $user);
	$stmt->execute();

	$query2 = "update aoi set description = cast( :desc as character(100)) where name = :aoiname";
	$stmt = $dbh->prepare($query2);
	$stmt->bindParam(':desc', $desc);
	$stmt->bindParam(':aoiname', $aoiname);
	$stmt->execute();
} else {
	echo json_encode(array("success"=>false));die();
}

echo json_encode(array("success"=>true));die();


?>