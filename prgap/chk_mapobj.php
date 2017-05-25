<?php

require('pr_config.php');
pg_connect($pg_connect);
$job_id = $_POST['job_id'];

$query = "select check_pk from check_mapobj where job_id = $job_id";
$result = pg_query($query);
$rows = pg_num_rows($result);
if($rows == 0){
	$check = "success";
} else {
	$check = "failure";
}
$ret =  json_encode(array("check"=>$check));
echo $ret;

?>